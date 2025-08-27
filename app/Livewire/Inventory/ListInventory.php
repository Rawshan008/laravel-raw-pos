<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Actions\Action;
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

class ListInventory extends Component implements HasActions, HasSchemas, HasTable
{
	use InteractsWithActions;
	use InteractsWithTable;
	use InteractsWithSchemas;

	public function table(Table $table): Table
	{
		return $table
			->query(fn(): Builder => Inventory::query())
			->columns([
				TextColumn::make('item.name')
					->searchable(),
				TextColumn::make('quantity')
			])
			->filters([
				//
			])
			->headerActions([
				Action::make('create')
					->label('Create Inventory')
					->url(fn(): string => route('inventory.create'))
			])
			->recordActions([
				Action::make('edit')
					->url(fn(Inventory $record): string => route('inventory.edit', $record)),

				Action::make('delete')
					->requiresConfirmation()
					->action(fn(Inventory $record) => $record->delete())
					->successNotificationTitle('Deleted Inventory Successfully!'),
			])
			->toolbarActions([
				BulkActionGroup::make([
					//
				]),
			]);
	}

	public function render(): View
	{
		return view('livewire.inventory.list-inventory');
	}
}
