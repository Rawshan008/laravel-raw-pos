<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditInventory extends Component implements HasActions, HasSchemas
{
	use InteractsWithActions;
	use InteractsWithSchemas;

	public Inventory $record;

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
					->label('Create Inventory')
					->schema([
						Select::make('item_id')
							->relationship('item', 'name')
							->searchable()
							->preload()
							->unique(ignoreRecord: true)
							->native(false),
						TextInput::make('quantity')
							->numeric()
							->placeholder('10.10')
					]),
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
			->title('Inventory Update!')
			->body('Inventory Update Successfully!')
			->send();

		$this->redirect(route('inventory.index'), navigate: true);
	}

	public function render(): View
	{
		return view('livewire.inventory.edit-inventory');
	}
}
