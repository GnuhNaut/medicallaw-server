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

// WEBHOOK
Route::post('/vietqr-callback', [RegistrationController::class, 'handleVietQRCallback']);