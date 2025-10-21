<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class VietQRService
{
    protected string $apiUrl;
    protected string $clientId;
    protected string $apiKey;
    protected string $bin;
    protected string $accountNo;

    public function __construct()
    {
        $this->apiUrl = config('services.vietqr.api_url');
        $this->clientId = config('services.vietqr.client_id');
        $this->apiKey = config('services.vietqr.api_key');
        $this->bin = config('services.vietqr.bin');
        $this->accountNo = config('services.vietqr.account_no');
    }

    /**
     * Lấy Access Token (Outbound) - Link 3
     */
    protected function getAccessToken(): ?string
    {
        $cacheKey = 'vietqr_outbound_access_token';

        return Cache::remember($cacheKey, 290, function () { // Lưu 290 giây
            try {
                // Sửa Endpoint theo tài liệu
                $tokenGenerateUrl = $this->apiUrl . '/vqr/api/token_generate'; 

                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->withBasicAuth($this->clientId, $this->apiKey) 
                ->post($tokenGenerateUrl);

                // Sửa: Tài liệu mới không có 'code' và 'data',
                // nó trả về token ngay ở cấp đầu tiên.
                if ($response->successful() && $response->json()['access_token']) {
                    return $response->json()['access_token'];
                }
                
                Log::error('Lỗi khi lấy Access Token VietQR (Outbound): ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('Ngoại lệ khi lấy Access Token VietQR: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Tạo mã QR (Outbound) - Link 4
     */
    public function generateQRCode(int $amount, string $content, string $accountNo, string $accountName): ?array
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::error('Không thể lấy Access Token (Outbound) để tạo QR');
            return null; // Không lấy được token
        }

        // Lấy đúng endpoint
        $generateUrl = $this->apiUrl . '/vqr/api/qr/generate-customer';

        // Chuẩn bị payload CHÍNH XÁC theo tài liệu (QR Động)
        $payload = [
            'bankCode' => $this->bin,        
            'bankAccount' => $accountNo,    
            'userBankName' => $accountName, 
            
            'amount' => $amount,       
            'content' => $content,   
            'orderId' => $content,     
            'qrType' => 0,        
            'transType' => 'C',   
        ];

        try {
            $response = Http::withToken($accessToken) // Gửi Bearer Token
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($generateUrl, $payload); // Gửi payload đã sửa

            // Tài liệu mới trả về HTTP 200 là thành công
            if ($response->successful() && isset($response->json()['qrCode'])) {
                // Trả về toàn bộ JSON (chứa qrCode, qrLink, imgId, ...)
                return $response->json(); 
            }
            
            // Nếu thất bại (ví dụ: 400)
            Log::error('Lỗi khi tạo QR VietQR (Outbound): ' . $response->body(), $payload);
            return null;
        } catch (\Exception $e) {
            Log::error('Ngoại lệ khi tạo QR VietQR: ' . $e->getMessage(), $payload);
            return null;
        }
    }

    public function triggerTestCallback(int $amount, string $content): ?array
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return ['success' => false, 'message' => 'Không thể lấy Access Token (Outbound)'];
        }

        $testUrl = $this->apiUrl . '/vqr/bank/api/test/transaction-callback';

        $payload = [
            'bankAccount' => $this->accountNo,   
            'content'     => $content,        
            'amount'      => $amount,       
            'bankCode'    => $this->bin,       
            'transType'   => 'C',           
        ];

        try {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($testUrl, $payload); // Gửi payload đã sửa

            // Tài liệu nói trả về 200 và { "status": "SUCCESS" }
            if ($response->successful() && $response->json()['status'] === 'SUCCESS') {
                return ['success' => true, 'data' => $response->json()];
            }
            
            Log::error('Lỗi khi gọi Test Callback: ' . $response->body(), $payload);
            return ['success' => false, 'message' => $response->body() ?? 'Lỗi không rõ'];
        } catch (\Exception $e) {
            Log::error('Ngoại lệ khi gọi Test Callback: ' . $e->getMessage(), $payload);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}