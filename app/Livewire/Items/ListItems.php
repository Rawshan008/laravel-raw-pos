<?php

namespace App\Livewire\Items;

use App\Models\Item;
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
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class ListItems extends Component implements HasActions, HasSchemas, HasTable
{
	use InteractsWithActions;
	use InteractsWithTable;
	use InteractsWithSchemas;

	public function table(Table $table): Table
	{
		return $table
			->query(fn(): Builder => Item::query())
			->columns([
				ImageColumn::make('image')
					->circular(),
				TextColumn::make('name'),
				TextColumn::make('sku'),
				TextColumn::make('price'),
				TextColumn::make('price')
					->prefix('$'),
				TextColumn::make('status')
					->badge()
					->color(fn(string $state): string => match ($state) {
						'active' => 'success',
						'inactive' => 'danger',
					}),
			])
			->filters([
				// 
			])
			->headerActions([
				Action::make('create')
					->label('Create Item')
					->url(fn(): string => route('items.create'))
			])
			->recordActions([
				Action::make('edit')
					->url(fn(Item $record): string => route('items.edit', $record)),

				Action::make('delete')
					->requiresConfirmation()
					->action(fn(Item $record) => $record->delete())
			])
			->toolbarActions([
				BulkActionGroup::make([
					//
				]),
			]);
	}

	public function render(): View
	{
		return view('livewire.items.list-items');
	}
}
