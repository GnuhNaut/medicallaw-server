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

// WEBHOOK
Route::post('/vietqr-callback', [RegistrationController::class, 'handleVietQRCallback']);