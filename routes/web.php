<?php

use App\Livewire\Customer\CreateCustomer;
use App\Livewire\Customer\EditCustomer;
use App\Livewire\Customer\ListCustomer;
use App\Livewire\Inventory\CreateInventory;
use App\Livewire\Inventory\EditInventory;
use App\Livewire\Inventory\ListInventory;
use App\Livewire\Items\CreateItem;
use App\Livewire\Items\EditItem;
use App\Livewire\Items\ListItems as ItemsListItems;
use App\Livewire\Managment\CreateUser;
use App\Livewire\Managment\EditUser;
use App\Livewire\Managment\ListUsers;
use App\Livewire\PaymentMethod\CreatePaymentMethod;
use App\Livewire\PaymentMethod\EditPaymentMethod;
use App\Livewire\PaymentMethod\ListPaymentMethod;
use App\Livewire\POS;
use App\Livewire\Sale\ListSale;
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

    // Inventory
    Route::get('/manage-inventory', ListInventory::class)->name('inventory.index');
    Route::get('/create-inventory', CreateInventory::class)->name('inventory.create');
    Route::get('/edit-inventory/{record}', EditInventory::class)->name('inventory.edit');

    // Customer 
    Route::get('/manage-customer', ListCustomer::class)->name('customers.index');
    Route::get('/create-customer', CreateCustomer::class)->name('customers.create');
    Route::get('/edit-customer/{record}', EditCustomer::class)->name('customers.edit');

    // Payment method 
    Route::get('/manage-paymentmethod', ListPaymentMethod::class)->name('paymentmethods.index');
    Route::get('/create-paymentmethod', CreatePaymentMethod::class)->name('paymentmethods.create');
    Route::get('/edit-paymentmethod/{record}', EditPaymentMethod::class)->name('paymentmethods.edit');

    // Sale
    Route::get('/manage-sales', ListSale::class)->name('sales.index');

    // POS 
    Route::get('/manage-pos', POS::class)->name('pos.index');
});

require __DIR__ . '/auth.php';
