<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', function () {
    // Chuyển hướng từ /home mặc định sang /admin/dashboard
    return redirect()->route('admin.dashboard');
})->middleware('auth');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Route hiển thị dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // === THÊM ROUTE MỚI ===
    // Route hiển thị danh sách ĐÃ THANH TOÁN
    Route::get('/paid-registrations', [DashboardController::class, 'paidList'])->name('registrations.paid');
    // === KẾT THÚC THÊM MỚI ===

    // Route để xử lý check-in vé
    Route::post('/registrations/{id}/checkin', [DashboardController::class, 'checkIn'])->name('registrations.checkin');
    
    // Route để gửi lại email
    Route::post('/registrations/{id}/resend-email', [DashboardController::class, 'resendEmail'])->name('registrations.resend_email');

    // Route để đánh dấu đã thanh toán
    Route::post('/registrations/{id}/mark-as-paid', [DashboardController::class, 'markAsPaid'])->name('registrations.mark_as_paid');

    Route::get('/manual-send', [DashboardController::class, 'showManualSendForm'])->name('manual_send.form');
    // Route để xử lý việc gửi mail thủ công
    Route::post('/manual-send', [DashboardController::class, 'handleManualSend'])->name('manual_send.submit');
});