<?php

namespace App\Livewire\Sale;

use App\Models\Sale;
use Filament\Actions\Action;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Tables\Columns\TextColumn;

class ListSale extends Component implements HasActions, HasSchemas, HasTable
{
	use InteractsWithActions;
	use InteractsWithTable;
	use InteractsWithSchemas;

	public function table(Table $table): Table
	{
		return $table
			->query(fn(): Builder => Sale::query())
			->columns([
				TextColumn::make('customer.name')
					->searchable(),
				TextColumn::make('salesItem.item.name')
					->label('Sold Item')
					->bulleted()
					->limitList(2)
					->expandableLimitedList(),
				TextColumn::make('total')
					->label('Total')
					->prefix("$ "),
				TextColumn::make('discount')
					->prefix("$ "),
				TextColumn::make('paid_amount')
					->label('Total Paid')
					->prefix("$ "),
				TextColumn::make('payment_method.name')->searchable(),
			])
			->filters([
				//
			])
			->headerActions([
				//
			])
			->recordActions([
				Action::make('delete')
					->requiresConfirmation()
					->action(fn(Sale $record) => $record->delete())
					->successNotificationTitle('Deleted Sale Successfylly'),
			])
			->toolbarActions([
				BulkActionGroup::make([
					//
				]),
			]);
	}

	public function render(): View
	{
		return view('livewire.sale.list-sale');
	}
}
