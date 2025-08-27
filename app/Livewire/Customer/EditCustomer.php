<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditCustomer extends Component implements HasActions, HasSchemas
{
	use InteractsWithActions;
	use InteractsWithSchemas;

	public Customer $record;

	public ?array $data = [];

	public function mount(): void
	{
		$this->form->fill($this->record->attributesToArray());
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
							->unique(ignoreRecord: true)
							->email(),
						TextInput::make('phone')
							->label('Custome Phone Numer')
							->placeholder('087656577')
							->required()
							->unique(ignoreRecord: true)
							->tel()
					])
			])
			->statePath('data')
			->model($this->record);
	}

	public function save(): void
	{
		$data = $this->form->getState();

		$this->record->update($data);

		Notification::make()
			->success()
			->title('Update Customer!')
			->body('Update Customer Successfully')
			->send();

		$this->redirect(route('customers.index'), navigate: true);
	}

	public function render(): View
	{
		return view('livewire.customer.edit-customer');
	}
}
