<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RegisterController as UserController;
use App\Http\Controllers\Admin\RegisterController as AdminController;
use App\Http\Controllers\Auth\RedirectAuthenticatedUsersController;

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


// Customer Registration Routes
Route::get('/customer/register', [UserController::class, 'showRegistrationForm'])->name('customer.register');
Route::post('/customer/register', [UserController::class, 'register'])->name('customer.register.post');
Route::get('/customer/login', [UserController::class, 'showLoginForm'])->name('customer.login');
Route::post('/customer/login', [UserController::class, 'login'])->name('customer.login.post');



// Admin Registration Routes
Route::get('/admin/register', [AdminController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register.post');;
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware('auth')->group(function () {
    Route::get('/home', [RedirectAuthenticatedUsersController::class, 'home']);
    Route::group(['middleware' => 'checkRole:admin'], function() {
        // admin dashboard route€
        Route::get('/adminDashboard', [AdminController::class, 'adminDashboard'])->name('adminDashboard');
        // // 

        Route::get('logout', [AdminController::class,'logout'])->name('admin.logout');

    });

    // for customer
    Route::group(['middleware' => 'checkRole:customer'], function() {
        // admin dashboard route
        Route::get('/customerDashboard', [UserController::class, 'customerDashboard'])->name('customerDashboard');
        // // 
        
        Route::get('logout', [UserController::class,'logout'])->name('logout');

    });

});