<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Notifications\NewFollower;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
Route::post('/change-password', [HomeController::class, 'updatePassword'])->name('update-password');

Route::get('/u/{user}', [ProfileController::class, 'index'])->name('index');

Route::post('/home', [HomeController::class, 'postCreateMessage'])->name('postCreateMessage');
Route::get('/delete/{message_id}', [HomeController::class, 'getDeletePost'])->name('getDeletePost');

Route::get('/edit-message/{message}', [HomeController::class, 'postEditMessage'])->name('edit-message');
Route::post('/edit-message/{message_id}', [HomeController::class, 'postupdateMessage'])->name('update-message');

Route::post('/follow', [ProfileController::class, 'followOrUnfollowUser'])->name('followOrUnfollowUser');


