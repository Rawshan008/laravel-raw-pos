<?php

namespace App\Livewire\Items;

use components;
use App\Models\Item;
use Livewire\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\ToggleButtons;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditItem extends Component implements HasActions, HasSchemas
{
	use InteractsWithActions;
	use InteractsWithSchemas;

	public Item $record;

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
					->label('Update Item')
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
			->model($this->record);
	}

	public function save(): void
	{
		$data = $this->form->getState();

		$this->record->update($data);

		Notification::make()
			->success()
			->title('Item Updated!')
			->body('Item Update Successfully')
			->send();

		$this->redirect(route('items.index'), navigate: true);
	}

	public function render(): View
	{
		return view('livewire.items.edit-item');
	}
}
