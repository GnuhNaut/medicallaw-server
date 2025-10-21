<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistrationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/submit-form', [RegistrationController::class, 'submitForm']);
Route::get('/payment-info/{ticket_id}', [RegistrationController::class, 'getPaymentInfo']);
Route::get('/member-info/{ticket_id}', [RegistrationController::class, 'getMemberInfo']);
Route::get('/check-payment-status/{ticket_id}', [RegistrationController::class, 'checkPaymentStatus']);

// WEBHOOK
Route::post('/api/token_generate', function (Request $request) {
    
    $username = $request->getUser();
    $password = $request->getPassword();

    $correctUser = config('services.vietqr.webhook_user');
    $correctPass = config('services.vietqr.webhook_pass');

    if (!$username || !$password || $username !== $correctUser || $password !== $correctPass) {
        return response()->json(['status' => 'FAILED', 'message' => 'Sai thông tin xác thực'], 401);
    }

    $token = Str::random(60);
    $expiresIn = 300;

    // Lưu token vào Cache (dùng key riêng cho inbound)
    Cache::put('vietqr_inbound_token', $token, $expiresIn);

    return response()->json([
        'access_token' => $token,
        'token_type'   => 'Bearer',
        'expires_in'   => $expiresIn
    ]);
});

Route::post('/bank/api/transaction-sync', [RegistrationController::class, 'handleVietQRCallback'])
     ->middleware('vietqr.auth'); 