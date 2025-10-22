<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InstanceController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function(){
    Route::view('/admin','dashboard/index')->name('dashboard');

    Route::view('/admin/profile','profile/index')->name('profile');
    Route::post('/admin/users/updateProfile/{user}', [UserController::class, 'update'])->name('users.updateProfile');

    Route::resource('/admin/instances',InstanceController::class);
    Route::post('/admin/instances/showCreate', [InstanceController::class, 'showCreate'])->name('instances.showCreate');
    Route::get('/admin/poll-instance-status', [InstanceController::class, 'status'])->name('instances.status');;
});

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
