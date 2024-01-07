<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();
Auth::routes(['verify' => true]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware(['auth'])->group(function () {
    Route::get('/approval', 'App\Http\Controllers\HomeController@approval')->name('approval');

    Route::middleware(['approved', 'verified'])->group(function () {
        Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
        // te były osobno, pierwsze jako przykład ról
        Route::resource('slowka', App\Http\Controllers\SlowkaController::class);
        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::resource('products', App\Http\Controllers\ProductController::class);
        Route::resource('words', App\Http\Controllers\WordController::class);
        Route::get('/usunzestaw', 'App\Http\Controllers\SlowkaController@usunzestaw')->name('slowka.usunzestaw');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/users', 'App\Http\Controllers\UserController@index')->name('admin.users.index');
        Route::get('/users/{user_id}/approve', 'App\Http\Controllers\UserController@approve')->name('admin.users.approve');
    });
});

// Route::group(['middleware' => ['auth', 'verified']], function() {
    // Route::resource('slowka', App\Http\Controllers\SlowkaController::class);
    // Route::resource('roles', App\Http\Controllers\RoleController::class);
    // Route::resource('/users', App\Http\Controllers\UserController::class);
    // Route::resource('products', App\Http\Controllers\ProductController::class);
    // Route::resource('words', App\Http\Controllers\WordController::class);
// to niżej całkiem wyłączyłem, nie trzeba było
    // Route::get('/slowka/create/{nrzestawu}/{robicdla}', [App\Http\Controllers\SlowkaController::class, 'create'])->name('slowka.create');
// });
