<?php

namespace App\Livewire\Managment;

use App\Models\User;
use Dom\Text;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateUser extends Component implements HasActions, HasSchemas
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
                    ->label('New User Added')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Entre Your Name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Enter Your Email')
                            ->email()
                            ->unique('users', 'email')
                            ->required(),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                    ])
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $record = User::create($data);
        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('User Created!')
            ->success()
            ->body('User Create Successfully')
            ->send();

        $this->redirect(route('users.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.managment.create-user');
    }
}
