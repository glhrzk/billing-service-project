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
    Route::put('/profile/update/password', [\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/package/show', [\App\Http\Controllers\User\PackageController::class, 'show'])->name('package.show');
    Route::get('/package/history', [\App\Http\Controllers\User\PackageController::class, 'history'])->name('package.history');

    Route::get('/bill/show', [\App\Http\Controllers\User\UserBillController::class, 'show'])->name('bill.show');
    Route::get('/bill/history', [\App\Http\Controllers\User\UserBillController::class, 'history'])->name('bill.history');
    Route::put('bill/{bill}/pay', [\App\Http\Controllers\User\UserBillController::class, 'pay'])->name('bill.pay');

    Route::get('/invoice/{bill}/show', [\App\Http\Controllers\User\InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{bill}/download', [\App\Http\Controllers\User\InvoiceController::class, 'download'])->name('invoice.download');

    Route::get('/ticket/index', [\App\Http\Controllers\User\TicketController::class, 'index'])->name('ticket.index');
    Route::get('/ticket/create', [\App\Http\Controllers\User\TicketController::class, 'create'])->name('ticket.create');
    Route::post('/ticket/store', [\App\Http\Controllers\User\TicketController::class, 'store'])->name('ticket.store');


    Route::get('/ticket/{ticket}/show', [\App\Http\Controllers\User\TicketReplyController::class, 'show'])->name('ticket.reply.show');
    Route::post('/ticket/{ticket}/reply', [\App\Http\Controllers\User\TicketReplyController::class, 'store'])->name('ticket.reply.store');
});
