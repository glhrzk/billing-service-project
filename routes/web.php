<?php

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


Auth::routes();

//Route::get('/home', [function () {
//    return view('home')->middleware('auth');
//
//}]);

Route::get('/home', [function () {
    return view('content');
}])->middleware('auth');;


Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {

    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

    Route::get('/profile/show', [\App\Http\Controllers\User\ProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile/edit/password', [\App\Http\Controllers\User\ProfileController::class, 'editPassword'])->name('profile.password.edit');

    Route::put('/profile/update/password',[\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
