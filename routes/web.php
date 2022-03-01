<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CheckNipController;
use App\Http\Controllers\DevController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OpinionController;
use Illuminate\Support\Facades\Route;

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
    return redirect('home');
});

Route::get('/test-dev', [DevController::class, 'index']);
Route::get('/test-notification-payment', [DevController::class, 'notify_paynow_payment']);
//notify_paynow_payment

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/check-nip', CheckNipController::class)->name('check-nip');
    Route::post('/change-password', ChangePasswordController::class)->name('change-password');
    Route::resource('opinions', OpinionController::class);
});

Auth::routes();