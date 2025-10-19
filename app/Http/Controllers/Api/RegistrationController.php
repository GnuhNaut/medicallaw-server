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

class RegistrationController extends Controller
{
    public function submitForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            // Bước 2: Gọi VietQR Service để tạo mã QR
            // $vietQRService = new VietQRService();
            // $amount = 100000; // Ví dụ số tiền vé là 100,000 VND
            // $qrData = $vietQRService->generateQRCode(
            //     $amount,
            //     $registration->ticket_id, // Nội dung chuyển khoản chính là ID vé
            //     config('services.vietqr.account_no'),
            //     config('services.vietqr.account_name')
            // );

            // Bước 3: Kiểm tra và cập nhật lại DB với dữ liệu QR
            // if ($qrData && $qrData['code'] == '00') {
            if (true) {
                // $registration->vietqr_data = 'qr'; // Lưu phần data trả về
                // $registration->vietqr_data = $qrData['data']; // Lưu phần data trả về
                $registration->save();
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
        // 1. Tìm lượt đăng ký trong database bằng ticket_id
        $registration = Registration::where('ticket_id', $ticket_id)->first();

        // 2. Nếu không tìm thấy, trả về lỗi 404 Not Found
        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin đăng ký.'
            ], 404);
        }

        // 3. Nếu tìm thấy, kiểm tra xem đã có dữ liệu VietQR chưa
        if (empty($registration->vietqr_data)) {
             return response()->json([
                'success' => false,
                'message' => 'Thông tin thanh toán chưa được tạo.'
            ], 404);
        }

        // 4. Trả về các thông tin cần thiết cho frontend
        return response()->json([
            'success' => true,
            'data' => [
                'ticket_id' => $registration->ticket_id,
                'name' => $registration->name,
                'email' => $registration->email,
                'payment_status' => $registration->payment_status,
                'qr_code_url' => $registration->vietqr_data['qrDataURL'] ?? null // Lấy URL ảnh QR
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
        // Lấy dữ liệu callback từ VietQR
        $callbackData = $request->json()->all();

        // Ghi lại toàn bộ dữ liệu callback để debug (rất quan trọng!)
        // Trong thực tế, bạn sẽ dùng Log::info()
        file_put_contents('vietqr_callback.log', json_encode($callbackData, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

        // **BẢO MẬT**: Bạn nên kiểm tra xem request có thực sự đến từ VietQR không.
        // Ví dụ: kiểm tra địa chỉ IP nguồn của request nếu VietQR cung cấp danh sách IP cố định.
        // $request->ip()

        // Lấy các thông tin cần thiết từ callback
        // Dựa vào tài liệu của VietQR, nội dung chuyển khoản là `description`
        $ticketId = $callbackData['description'] ?? null;
        $amount = $callbackData['amount'] ?? 0;
        $transactionId = $callbackData['transactionId'] ?? null; // Mã giao dịch của ngân hàng

        // --- Bắt đầu xác thực giao dịch ---

        // 1. Kiểm tra có ticketId không
        if (!$ticketId) {
            // Không có mã vé, không xử lý
            return response()->json(['success' => false, 'message' => 'Mô tả giao dịch không hợp lệ.']);
        }

        // 2. Tìm lượt đăng ký trong DB
        $registration = Registration::where('ticket_id', $ticketId)->first();
        if (!$registration) {
            // Không tìm thấy vé, có thể ai đó chuyển nhầm
            return response()->json(['success' => false, 'message' => 'Không tìm thấy mã đăng ký.']);
        }

        // 3. Kiểm tra xem đã thanh toán chưa để tránh xử lý nhiều lần
        if ($registration->payment_status === 'paid') {
            return response()->json(['success' => true, 'message' => 'Giao dịch đã được xử lý trước đó.']);
        }

        // 4. Kiểm tra số tiền
        $expectedAmount = 100000; // Số tiền vé dự kiến, bạn nên lưu giá trị này vào DB cùng lúc đăng ký
        if ($amount < $expectedAmount) {
            // Số tiền không khớp, có thể ghi nhận là thanh toán thiếu
            return response()->json(['success' => false, 'message' => 'Số tiền thanh toán không đủ.']);
        }

        // --- Giao dịch hợp lệ, cập nhật trạng thái ---
        try {
            $registration->payment_status = 'paid';
            $registration->save();

            // Ở bước tiếp theo, chúng ta sẽ kích hoạt gửi email cảm ơn kèm QR code vé từ đây.
            // Ví dụ: SendThankYouEmail::dispatch($registration);

            // Phản hồi lại cho VietQR rằng đã nhận và xử lý thành công
            Mail::to($registration->email)->send(new ThankYouMail($registration));
            
            $registration->email_sent_at = now();
            $registration->save();
            return response()->json([
                'success' => true,
                'message' => 'Thanh toán đã được xác nhận thành công.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật cơ sở dữ liệu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}