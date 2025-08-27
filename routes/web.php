<?php

use App\Livewire\Items\CreateItem;
use App\Livewire\Items\EditItem;
use App\Livewire\Items\ListItems as ItemsListItems;
use App\Livewire\Managment\CreateUser;
use App\Livewire\Managment\EditUser;
use App\Livewire\Managment\ListUsers;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/manage-users', ListUsers::class)->name('users.index');
    Route::get('/create-users', CreateUser::class)->name('users.create');
    Route::get('/edit-users/{record}', EditUser::class)->name('users.edit');

    // Items 
    Route::get('/manage-items', ItemsListItems::class)->name('items.index');
    Route::get('/create-items', CreateItem::class)->name('items.create');
    Route::get('/edit-items/{record}', EditItem::class)->name('items.edit');
});

require __DIR__ . '/auth.php';
