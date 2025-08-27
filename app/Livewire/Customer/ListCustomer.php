<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
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

class ListCustomer extends Component implements HasActions, HasSchemas, HasTable
{
	use InteractsWithActions;
	use InteractsWithTable;
	use InteractsWithSchemas;

	public function table(Table $table): Table
	{
		return $table
			->query(fn(): Builder => Customer::query())
			->columns([
				TextColumn::make('name')
					->label('Name')
					->searchable(),
				TextColumn::make('email')
					->label('Email')
					->searchable(),
				TextColumn::make('phone')
					->label('Phone Number')
					->searchable()
			])
			->filters([
				//
			])
			->headerActions([
				Action::make('create')
					->label('Create Customer')
					->url(fn(): string => route('customers.create'))
			])
			->recordActions([
				Action::make('edit')
					->url(fn(Customer $record): string => route('customers.edit', $record)),

				Action::make('delete')
					->requiresConfirmation()
					->action(fn(Customer $record) => $record->delete())
			])
			->toolbarActions([
				BulkActionGroup::make([
					//
				]),
			]);
	}

	public function render(): View
	{
		return view('livewire.customer.list-customer');
	}
}
