<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreateInventory extends Component implements HasActions, HasSchemas
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
					->label('Create Inventory')
					->schema([
						Select::make('item_id')
							->relationship('item', 'name')
							->searchable()
							->preload()
							->unique()
							->native(false),
						TextInput::make('quantity')
							->numeric()
							->placeholder('10.10')
					]),
			])
			->statePath('data')
			->model(Inventory::class);
	}

	public function create(): void
	{
		$data = $this->form->getState();

		$record = Inventory::create($data);

		$this->form->model($record)->saveRelationships();

		Notification::make()
			->success()
			->title('Inventory Created!')
			->body('Inventory Create Successfully')
			->send();

		$this->redirect(route('inventory.index'), navigate: true);
	}

	public function render(): View
	{
		return view('livewire.inventory.create-inventory');
	}
}
