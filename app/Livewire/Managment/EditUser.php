<?php

namespace App\Livewire\Managment;

use App\Models\User;
use Livewire\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditUser extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public User $record;

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
                    ->label('New User Added')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name'),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->unique(ignoreRecord: true)
                    ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $this->record->update($data);

        Notification::make()
            ->title('User Updated!')
            ->success()
            ->body('User Update Successfully')
            ->send();

        $this->redirect(route('users.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.managment.edit-user');
    }
}
