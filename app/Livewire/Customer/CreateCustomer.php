<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreateCustomer extends Component implements HasActions, HasSchemas
{
	use InteractsWithActions;
	use InteractsWithSchemas;

	public ?array $data = [];

	public function mount(): void
	{
		$this->form->fill();
	}

	public function form(Schema $schema): Schema
	{
		return $schema
			->components([
				Section::make()
					->label('Create Customer')
					->schema([
						TextInput::make('name')
							->label('Customer Name')
							->placeholder('Jhon Rudy')
							->required(),
						TextInput::make('email')
							->label('Customer Email')
							->placeholder('email@email.com')
							->unique()
							->email(),
						TextInput::make('phone')
							->label('Custome Phone Numer')
							->placeholder('087656577')
							->required()
							->unique()
							->tel()
					])
			])
			->statePath('data')
			->model(Customer::class);
	}

	public function create(): void
	{
		$data = $this->form->getState();

		$record = Customer::create($data);

		$this->form->model($record)->saveRelationships();

		Notification::make()
			->success()
			->title('Customer Created!')
			->body('Customer Created Successfully')
			->send();

		$this->redirect(route('customers.index'), navigate: true);
	}

	public function render(): View
	{
		return view('livewire.customer.create-customer');
	}
}
