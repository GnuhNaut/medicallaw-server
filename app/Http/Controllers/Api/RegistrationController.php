<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Services\VietQRService;
use App\Mail\AdminNotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ThankYouMail;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Schema;

class RegistrationController extends Controller
{
    public function submitForm(Request $request)
    {
        $data = $request->all();

        if (isset($data['members'])) {
            $data['members'] = (int) $data['members']; 
        }

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'position' => 'nullable|string|max:255',
            'members' => 'nullable|integer|min:1',
            'question' => 'nullable|string',
            'guestType' => 'nullable|string', // Tên key từ frontend là guestType
            'field' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        do {
            $ticketId = strtoupper(Str::random(8));
        } while (Registration::where('ticket_id', $ticketId)->exists());

        try {
            // Bước 1: Lưu thông tin cơ bản vào DB
            $registration = Registration::create([
                'ticket_id' => $ticketId,
                'name' => $request->input('name'),
                'position' => $request->input('position'),
                'members' => $request->input('members', 1), // Mặc định là 1 nếu không có
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'question' => $request->input('question'),
                'guest_type' => $request->input('guestType'), // Ánh xạ guestType vào guest_type
                'field' => $request->input('field'),
            ]);            
            $adminEmail = config('mail.admin_address');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminNotificationMail($registration));
            }
            
            $vietQRService = new VietQRService();
            $amount = (int)$registration->members * (int)config('services.vietqr.price_ticket');
            $accountNo = config('services.vietqr.account_no');
            $accountName = config('services.vietqr.account_name');
            $content = $registration->ticket_id;

            $qrData = $vietQRService->generateQRCode($amount, $content, $accountNo, $accountName);
            // Bước 3: Kiểm tra và cập nhật lại DB với dữ liệu QR
            // if ($qrData && $qrData['code'] == '00') {
            if ($qrData && isset($qrData['qrCode'])) {
                $registration->vietqr_data = $qrData; 
                $registration->save();
            // if(true) {
            } else {
                // Xử lý khi gọi API VietQR thất bại
                // Có thể xóa bản ghi vừa tạo hoặc đánh dấu là lỗi
                throw new \Exception('Không thể tạo mã thanh toán VietQR.');
            }

            // Bước 4: Trả về ticket_id cho frontend
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công, vui lòng thanh toán.',
                'ticket_id' => $registration->ticket_id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra phía server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentInfo($ticket_id)
    {
        $registration = Registration::where('ticket_id', $ticket_id)->first();
        if (!$registration) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thông tin đăng ký.'], 404);
        }
        // if (empty($registration->vietqr_data) || !isset($registration->vietqr_data['qrDataURL'])) {
        //      return response()->json(['success' => false, 'message' => 'Thông tin thanh toán (mã QR) chưa được tạo.'], 404);
        // }
        return response()->json([
            'success' => true,
            'data' => [
                'ticket_id' => $registration->ticket_id,
                'name' => $registration->name,
                'email' => $registration->email,
                'payment_status' => $registration->payment_status,
                'members' => $registration->members,
                'qr_code_data' => json_decode($registration->vietqr_data) ?? null // Trả về QR Code (Base64)
            ]
        ]);
    }
    
    public function getMemberInfo($ticket_id)
    {
        // 1. Tìm lượt đăng ký trong database bằng ticket_id
        $registration = Registration::where('ticket_id', $ticket_id)->first();

        // 2. Nếu không tìm thấy, trả về lỗi 404 Not Found
        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin đăng ký.'
            ], 404);
        }

        if ($registration->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa thanh toán phí đăng ký thành viên.'
            ], 404);
        }

        // 3. Nếu tìm thấy, kiểm tra xem đã có dữ liệu VietQR chưa
        // if (empty($registration->vietqr_data)) {
        //      return response()->json([
        //         'success' => false,
        //         'message' => 'Thông tin thanh toán chưa được tạo.'
        //     ], 404);
        // }

        // 4. Trả về các thông tin cần thiết cho frontend
        return response()->json([
            'success' => true,
            'data' => [
                'registration' => $registration
            ]
        ]);
    }

    public function handleVietQRCallback(Request $request)
    {
        Log::info('VietQR Callback Received:', $request->all());
        $callbackData = $request->json()->all();

        // Lấy các thông tin từ callback
        $ticketId = $callbackData['orderId'] ?? $callbackData['content'] ?? null;
        $amount = $callbackData['amount'] ?? 0;
        // Lấy transactionId để trả về cho VietQR (nếu có)
        $refTransactionId = $callbackData['transactionId'] ?? $callbackData['reftransactionid'] ?? $ticketId;

        // --- Định nghĩa Response Format (theo tài liệu) ---
        $responseError = function (string $reason, string $message, int $statusCode = 400) {
            return response()->json([
                'error' => true,
                'errorReason' => $reason,
                'toastMessage' => $message,
                'object' => null
            ], $statusCode);
        };

        $responseSuccess = function ($refId, string $message = "Giao dịch thành công") {
            return response()->json([
                'error' => false,
                'errorReason' => null,
                'toastMessage' => $message,
                'object' => [
                    'reftransactionid' => $refId
                ]
            ], 200);
        };
        // --------------------------------------------------


        if (!$ticketId) {
            Log::warning('VietQR Callback: Mô tả giao dịch không hợp lệ.', $callbackData);
            // Lỗi 400: Mô tả không hợp lệ
            return $responseError('INVALID_DESCRIPTION', 'Mô tả giao dịch không hợp lệ.');
        }

        $registration = Registration::where('ticket_id', $ticketId)->first();
        if (!$registration) {
            Log::warning('VietQR Callback: Không tìm thấy mã đăng ký.', $callbackData);
            // Lỗi 400: Không tìm thấy
            return $responseError('REGISTRATION_NOT_FOUND', 'Không tìm thấy mã đăng ký.');
        }
        
        if ($registration->payment_status === 'paid') {
            // Thành công 200: Giao dịch đã được xử lý
            return $responseSuccess($refTransactionId, 'Giao dịch đã được xử lý trước đó.');
        }

        // Lấy giá vé từ config (giả sử bạn đã thêm vào config/services.php)
        $pricePerTicket = config('services.vietqr.price_ticket', 2000000); // Mặc định 100k
        $expectedAmount = (int)$registration->members * (int)$pricePerTicket;

        if ($amount < $expectedAmount) {
            Log::warning('VietQR Callback: Số tiền không đủ.', $callbackData);
            // Lỗi 400: Số tiền không đủ
            return $responseError('INSUFFICIENT_AMOUNT', 'Số tiền thanh toán không đủ.');
        }

        try {
            $registration->payment_status = 'paid';
            $registration->save();

            Mail::to($registration->email)->send(new ThankYouMail($registration));
            
            // Cập nhật email_sent_at (nếu có cột)
            if (Schema::hasColumn('registrations', 'email_sent_at')) {
                $registration->email_sent_at = now();
                $registration->save();
            }
            
            // Thành công 200: Xử lý thành công
            return $responseSuccess($refTransactionId, 'Thanh toán đã được xác nhận thành công.');

        } catch (\Exception $e) {
            Log::error('Lỗi khi xử lý Callback VietQR: ' . $e->getMessage(), $callbackData);
            // Lỗi 400 (hoặc 500): Lỗi hệ thống nội bộ
            return $responseError('INTERNAL_ERROR', 'Lỗi khi cập nhật DB hoặc gửi email.', 500); 
            // Lưu ý: Tài liệu yêu cầu 400, nhưng 500 (Internal Server Error) 
            // có thể hợp lý hơn cho lỗi hệ thống. Bạn có thể đổi 500 thành 400 nếu muốn.
        }
    }

    public function checkPaymentStatus($ticket_id)
    {
        $registration = Registration::where('ticket_id', $ticket_id)->first(['payment_status', 'ticket_id']); // Chỉ lấy trường cần thiết

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin đăng ký.'
            ], 404);
        }

        // Trả về trạng thái hiện tại
        return response()->json([
            'success' => true,
            'ticket_id' => $registration->ticket_id,
            'payment_status' => $registration->payment_status ?? 'pending' // Giả sử mặc định là 'pending' nếu null
        ]);
    }
}