<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FoodController;

Route::get('/', function () {
    return view('welcome');
});

//    Route::get('/customers', [customerController::class, 'index'])->name('customers.index');

//     Route::get('/customers/create',[customerController::class, 'create'])->name('customers.create');
    
//     Route::get('/customers/{customer}', [customerController::class, 'show'])->name('customers.show');

//     Route::post('/customers', [customerController::class, 'store'])->name('customers.store');


//    Route::get('/customers/{customer}/edit', [customerController::class, 'edit'])->name('customers.edit');
    
//    Route::delete('/customers/{customer}', [customerController::class, 'destroy'])->name('customers.destroy');



Route::resource('orders', OrderController::class);

Route::resource('customers', CustomerController::class);

Route::resource('foods', FoodController::class);