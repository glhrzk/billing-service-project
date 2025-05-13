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
    Route::put('bill/{id}/pay', [\App\Http\Controllers\User\UserBillController::class, 'pay'])->name('bill.pay');

    Route::get('/invoice/{id}/show', [\App\Http\Controllers\User\InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/download', [\App\Http\Controllers\User\InvoiceController::class, 'download'])->name('invoice.download');

    Route::get('/ticket/index', [\App\Http\Controllers\User\TicketController::class, 'index'])->name('ticket.index');
    Route::get('/ticket/create', [\App\Http\Controllers\User\TicketController::class, 'create'])->name('ticket.create');
    Route::post('/ticket/store', [\App\Http\Controllers\User\TicketController::class, 'store'])->name('ticket.store');


    Route::get('/ticket/{id}/show', [\App\Http\Controllers\User\TicketReplyController::class, 'show'])->name('ticket.reply.show');
    Route::post('/ticket/{id}/reply', [\App\Http\Controllers\User\TicketReplyController::class, 'store'])->name('ticket.reply.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Manajemen User
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/show', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');

    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');

    // Manajemen Paket
    Route::get('/packages', [\App\Http\Controllers\Admin\PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/{id}/show', [\App\Http\Controllers\Admin\PackageController::class, 'show'])->name('packages.show');
    Route::put('/packages/{id}', [\App\Http\Controllers\Admin\PackageController::class, 'update'])->name('packages.update');
    Route::get('/packages/create', [\App\Http\Controllers\Admin\PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [\App\Http\Controllers\Admin\PackageController::class, 'store'])->name('packages.store');

    // Langganan User
    Route::get('/user-packages', [\App\Http\Controllers\Admin\UserPackageController::class, 'index'])->name('user-packages.index');
    Route::put('/user-packages/{id}/', [\App\Http\Controllers\Admin\UserPackageController::class, 'update'])->name('user-package.deactivate');
    Route::post('/user-packages/', [\App\Http\Controllers\Admin\UserPackageController::class, 'store'])->name('user-package.store');

    // Tagihan & Pembayaran
    Route::get('/bills', [\App\Http\Controllers\Admin\UserBillController::class, 'index'])->name('bills.index');
    Route::get('/bill/{id}/show', [\App\Http\Controllers\Admin\UserBillController::class, 'show'])->name('bills.show');



    Route::get('/bills/verification', [BillController::class, 'verification'])->name('bills.verification');
    Route::put('/bills/{id}/approve', [BillController::class, 'approve'])->name('bills.approve');
    Route::put('/bills/{id}/reject', [BillController::class, 'reject'])->name('bills.reject');

    // Pengeluaran
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    // Tiket Bantuan
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/pending', [TicketController::class, 'pending'])->name('tickets.pending');
    Route::get('/tickets/{id}/show', [TicketReplyController::class, 'show'])->name('tickets.reply.show');
    Route::post('/tickets/{id}/reply', [TicketReplyController::class, 'store'])->name('tickets.reply.store');

    // Laporan
    Route::get('/reports/incomes', [ReportController::class, 'incomes'])->name('reports.incomes');
    Route::get('/reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('/reports/bills', [ReportController::class, 'bills'])->name('reports.bills');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Profil Admin
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/update/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

});

