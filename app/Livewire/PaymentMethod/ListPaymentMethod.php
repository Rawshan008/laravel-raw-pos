<?php

namespace App\Livewire\PaymentMethod;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\PaymentMethod;
use Filament\Actions\Action;
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

class ListPaymentMethod extends Component implements HasActions, HasSchemas, HasTable
{
	use InteractsWithActions;
	use InteractsWithTable;
	use InteractsWithSchemas;

	public function table(Table $table): Table
	{
		return $table
			->query(fn(): Builder => PaymentMethod::query())
			->columns([
				TextColumn::make('name')
					->label('Payment Method Name')
					->searchable(),
				TextColumn::make('description')
					->label('Description')
			])
			->filters([
				//
			])
			->headerActions([
				Action::make('create')
					->label('Create Payment Method')
					->url(fn(): string => route('paymentmethods.create'))
			])
			->recordActions([
				Action::make('delete')
					->requiresConfirmation()
					->action(fn(PaymentMethod $record) => $record->delete())
					->successNotificationTitle('Deleted Payment Method'),
				Action::make('edit')
					->url(fn(PaymentMethod $record): string => route('paymentmethods.edit', $record))
			])
			->toolbarActions([
				BulkActionGroup::make([
					//
				]),
			]);
	}

	public function render(): View
	{
		return view('livewire.payment-method.list-payment-method');
	}
}
