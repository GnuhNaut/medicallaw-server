<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VietQRService
{
    protected $apiUrl = 'https://api.vietqr.vn/v2/generate';
    protected $clientId;
    protected $apiKey;

    public function __construct()
    {
        // Lấy thông tin cấu hình từ file .env
        $this->clientId = config('services.vietqr.client_id');
        $this->apiKey = config('services.vietqr.api_key');
    }

    /**
     * Tạo mã QR Code thanh toán.
     *
     * @param int $amount Số tiền cần thanh toán
     * @param string $message Nội dung chuyển khoản (thường là ticket_id)
     * @param string $accountNo Số tài khoản
     * @param string $accountName Tên chủ tài khoản
     * @return array|null
     */
    public function generateQRCode($amount, $message, $accountNo, $accountName)
    {
        $payload = [
            "accountNo" => $accountNo,
            "accountName" => $accountName,
            "acqId" => 970436, // Mã ngân hàng Techcombank, bạn có thể thay đổi
            "amount" => $amount,
            "addInfo" => $message,
            "format" => "text",
            "template" => config('services.vietqr.template', 'print'),
        ];

        $response = Http::withHeaders([
            'x-client-id' => $this->clientId,
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, $payload);

        if ($response->successful()) {
            return $response->json(); // Trả về dữ liệu JSON từ API
        }

        // Nếu có lỗi, bạn có thể log lại để debug
        // Log::error('VietQR API Error: ' . $response->body());
        return null;
    }
}