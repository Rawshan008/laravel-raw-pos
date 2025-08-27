<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SalesItem;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Filament\Notifications\Notification;

class POS extends Component
{

	// Property 
	public $items;

	public $customers;

	public $paymentMethods;

	public $search = '';

	public $cart = [];

	// Cart Property 
	public $customer_id = null;
	public $payment_method_id = null;
	public $paid_amount = 0;
	public $discount = 0;


	// Mount Function 
	public function mount()
	{
		$this->items = Item::with(['inventory'], function ($builder): void {
			$builder->where('quantity', '>', 0);
		})
			->where('status', 'active')
			->get();

		$this->customers = Customer::all();

		$this->paymentMethods = PaymentMethod::all();
	}

	#[Computed]
	public function filteredItems()
	{
		if (empty($this->search)) {
			return $this->items;
		}

		return $this->items->filter(function ($item) {
			$searchTerm = strtolower($this->search);

			return str_contains(strtolower($item->name), $searchTerm) ||
				str_contains(strtolower($item->sku), $searchTerm);
		});
	}

	#[Computed]
	public function subtotal()
	{
		return collect($this->cart)->sum(function ($item) {
			return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
		});
	}

	#[Computed]
	public function tax()
	{
		return $this->subtotal * 0.15;
	}

	#[Computed]
	public function totalBeforeDiscount()
	{
		return $this->subtotal + $this->tax;
	}

	#[Computed]
	public function total()
	{
		$discountedtotal = $this->totalBeforeDiscount - ($this->discount ?? 0);
		return max(0, $discountedtotal);
	}

	public function updatedDiscount($value)
	{
		$discount = (float) $value;

		if ($discount > $this->totalBeforeDiscount) {
			Notification::make()
				->title('Discount cannot be greater than total amount')
				->danger()
				->send();

			$this->discount = 0;
		} else {
			$this->discount = $discount;
		}
	}

	#[Computed]
	public function change()
	{
		if (($this->paid_amount ?? 0) > $this->total) {
			return $this->paid_amount - $this->total;
		}
		return 0;
	}

	public function addToCart($itemId)
	{
		$item = Item::find($itemId);

		$inventory = Inventory::where('item_id', $itemId)->first();

		if (!$inventory || $inventory->quantity <= 0) {
			Notification::make()
				->title('This item out of Stock')
				->danger()
				->send();
			return;
		}

		if (isset($this->cart[$itemId])) {
			$currentQuantity = $this->cart[$itemId]['quantity'];

			if ($currentQuantity >= $inventory->quantity) {
				Notification::make()
					->title("Cannot add more. Only {$inventory->quantity} in Stock")
					->danger()
					->send();
				return;
			}

			$this->cart[$itemId]['quantity']++;
		} else {
			$this->cart[$itemId] = [
				'id' => $item->id,
				'name' => $item->name,
				'sku' => $item->sku,
				'price' => $item->price,
				'quantity' => 1,
			];
		}
	}

	// Remove item form cart 
	public function removeFromCart($itemId)
	{
		unset($this->cart[$itemId]);
	}


	// Update Quantity 
	public function updateQuantity($itemId, $quantity)
	{
		$quantity = max(1, (int) $quantity);
		$inventory = Inventory::where('item_id', $itemId)->first();

		if ($quantity > $inventory->quantity) {
			Notification::make()
				->title("Cannot add more. Only {$inventory->quantity} in Stock")
				->danger()
				->send();
			$this->cart[$itemId]['quantity'] = $inventory->quantity;
		} else {
			$this->cart[$itemId]['quantity'] = $quantity;
		}
	}

	// Checkout 
	public function checkout()
	{
		if (empty($this->cart)) {
			Notification::make()
				->danger()
				->title('Failed Sale!')
				->body('Your Cart is Empty')
				->send();
			return;
		}

		if ($this->paid_amount < $this->total) {
			Notification::make()
				->title('Failed Sale!')
				->body('Paid Amount is less than total!')
				->danger()
				->send();
			return;
		}

		try {
			// Create sale 
			DB::beginTransaction();

			$sale = Sale::create([
				'customer_id' => $this->customer_id,
				'payment_method_id' => $this->payment_method_id,
				'paid_amount' => $this->total,
				'total' => $this->totalBeforeDiscount,
				'discount' => $this->discount,
			]);

			// Create Sale Item 

			foreach ($this->cart as $item) {
				SalesItem::create([
					'sale_id' => $sale->id,
					'item_id' => $item['id'],
					'quantity' => $item['quantity'],
					'price' => $item['price'],
				]);

				// Update Stock 
				$inventory = Inventory::where('item_id', $item['id'])->first();

				if ($inventory) {
					$inventory->quantity -= $item['quantity'];
					$inventory->save();
				}
			}

			DB::commit();

			$this->search = '';
			$this->cart = [];
			$this->customer_id = null;
			$this->payment_method_id = null;
			$this->paid_amount = 0;
			$this->discount = 0;

			Notification::make()
				->success()
				->title('Success Sale!')
				->body('Sale wad made Successfully')
				->send();
		} catch (\Exception $th) {
			DB::rollback();
			Notification::make()
				->title('Failed Sale!')
				->body('Failed to complete the sale, try again.')
				->danger()
				->send();
		}
	}



	public function render()
	{
		return view('livewire.p-o-s');
	}
}
