<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // Thêm Log
use Symfony\Component\HttpFoundation\Response;

class VerifyVietQRToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $storedToken = Cache::get('vietqr_inbound_token'); // Lấy key token (từ Bước 5)

        if (!$token || !$storedToken || $token !== $storedToken) {
            Log::warning('Truy cập bị từ chối (Token không hợp lệ): ' . $token);
            return response()->json(['success' => false, 'message' => 'Token không hợp lệ'], 401);
        }

        return $next($request);
    }
}