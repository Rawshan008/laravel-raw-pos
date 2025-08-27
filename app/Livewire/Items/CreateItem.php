<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreateItem extends Component implements HasActions, HasSchemas
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
					->label('Create New Item')
					->schema([
						TextInput::make('name')
							->placeholder('Item Name')
							->required(),
						TextInput::make('sku')
							->placeholder('SKU')
							->required()
							->unique(),
						TextInput::make('price')
							->placeholder('10.00')
							->required()
							->prefix('$')
							->numeric(),
						ToggleButtons::make('status')
							->label('Is this item active?')
							->options([
								'active' => 'Active',
								'inactive' => 'Inactive'
							])
							->default('active')
							->grouped(),
						FileUpload::make('image')
							->image()
							->directory('items')
					])
			])
			->statePath('data')
			->model(Item::class);
	}

	public function create(): void
	{
		$data = $this->form->getState();

		$record = Item::create($data);

		$this->form->model($record)->saveRelationships();

		Notification::make()
			->success()
			->title('Item Created!')
			->body('Item Created Successfullly')
			->send();

		$this->redirect(route('items.index'), navigate: true);
	}

	public function render(): View
	{
		return view('livewire.items.create-item');
	}
}
