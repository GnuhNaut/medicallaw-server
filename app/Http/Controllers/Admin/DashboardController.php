<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Mail\ThankYouMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request; // Đảm bảo đã import Request
use App\Services\VietQRService;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard với danh sách đăng ký (có tìm kiếm và phân trang).
     */
    public function index(Request $request) // Thêm Request $request
    {
        $query = Registration::query();
        $search = $request->input('search');

        // Nếu có tham số tìm kiếm
        if ($search) {
            // Tìm kiếm trên nhiều cột
            $query->where(function ($q) use ($search) {
                $q->where('id', $search) // Tìm theo ID
                  ->orWhere('ticket_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Lấy tất cả các lượt đăng ký, sắp xếp theo thời gian mới nhất và phân trang
        $registrations = $query->orderBy('created_at', 'desc')->paginate(20); // Dùng paginate(20)

        // Trả về view và truyền dữ liệu qua
        return view('admin.dashboard', [
            'registrations' => $registrations,
            'search' => $search // Trả $search về view để giữ giá trị trong ô input
        ]);
    }

    public function paidList(Request $request)
    {
        // 1. Bắt đầu query với điều kiện chỉ lấy 'paid'
        $query = Registration::where('payment_status', 'paid');
        $search = $request->input('search');

        // 2. Áp dụng logic tìm kiếm nếu có
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('ticket_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // 3. Sắp xếp và phân trang
        $registrations = $query->orderBy('created_at', 'desc')->paginate(20);

        // 4. Trả về một view MỚI
        return view('admin.paid_list', [
            'registrations' => $registrations,
            'search' => $search
        ]);
    }
    /**
     * Cập nhật trạng thái vé thành 'used' (đã check-in).
     */
    public function checkIn($id)
    {
        $registration = Registration::findOrFail($id);
        
        // Bỏ comment logic kiểm tra thanh toán
        if ($registration->payment_status === 'paid') {
            $registration->ticket_status = 'used';
            $registration->save();
            return back()->with('success', 'Check-in vé ' . $registration->ticket_id . ' thành công!');
        }

        return back()->with('error', 'Không thể check-in vé chưa thanh toán!');
    }
    
    /**
     * Gửi lại email cảm ơn và vé cho khách.
     */
    public function resendEmail($id)
    {
        $registration = Registration::findOrFail($id);
        
        // Bỏ comment logic kiểm tra thanh toán
        if ($registration->payment_status === 'paid') {
            try {
                Mail::to($registration->email)->send(new ThankYouMail($registration));
                
                // Cập nhật trạng thái đã gửi mail
                $registration->email_sent_at = now();
                $registration->save();

                return back()->with('success', 'Đã gửi lại email cho ' . $registration->email);
            } catch (\Exception $e) {
                return back()->with('error', 'Gửi email thất bại: ' . $e->getMessage());
            }
        }
        
        return back()->with('error', 'Không thể gửi email cho vé chưa thanh toán!');
    }

    /**
     * Thêm hàm mới: Đánh dấu đã thanh toán thủ công.
     */
    public function markAsPaid($id)
    {
        $registration = Registration::findOrFail($id);
        
        if ($registration->payment_status === 'paid') {
            return back()->with('info', 'Vé ' . $registration->ticket_id . ' đã được thanh toán trước đó.');
        }

        $registration->payment_status = 'paid';
        $registration->save();

        // Tự động gửi email cảm ơn khi đánh dấu đã thanh toán
        try {
            // Mail::to($registration->email)->send(new ThankYouMail($registration));
            // $registration->email_sent_at = now();
            // $registration->save();
            return back()->with('success', 'Đã đánh dấu vé ' . $registration->ticket_id . ' là ĐÃ THANH TOÁN');
        } catch (\Exception $e) {
            // Vẫn thành công nhưng báo lỗi gửi mail
            return back()->with('warning', 'Đã đánh dấu thanh toán thành công, nhưng gửi email thất bại: ' . $e->getMessage());
        }
    }

    public function showManualSendForm()
    {
        return view('admin.manual_send');
    }

    /**
     * Xử lý việc gửi email thủ công dựa trên Ticket ID hoặc Email.
     */
    public function handleManualSend(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate(['identifier' => 'required|string']);

        $identifier = $request->input('identifier');

        // 2. Tìm kiếm lượt đăng ký bằng email hoặc ticket_id
        $registration = Registration::where('email', $identifier)
                                      ->orWhere('ticket_id', $identifier)
                                      ->first();

        // 3. Nếu không tìm thấy, báo lỗi
        if (!$registration) {
            return back()->with('error', 'Không tìm thấy lượt đăng ký với thông tin cung cấp.');
        }

        // 4. Kiểm tra nếu chưa thanh toán thì báo lỗi
        if ($registration->payment_status !== 'paid') {
            return back()->with('error', 'Không thể gửi email cho vé chưa thanh toán. Vui lòng đánh dấu thanh toán trước.');
        }

        // 5. Nếu tìm thấy và đã thanh toán, gọi lại hàm gửi mail
        return $this->resendEmail($registration->id);
    }

    public function triggerTestPayment(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);
        
        if ($registration->payment_status !== 'pending') {
            return back()->with('error', 'Vé này đã được thanh toán, không thể test.');
        }

        try {
            $vietQRService = new VietQRService();
            $amount = (int)$registration->members * (int)config('services.vietqr.price_ticket');
            $content = $registration->ticket_id;

            $result = $vietQRService->triggerTestCallback($amount, $content);
            Log::info('Kết quả Test Payment cho đăng ký ID ' . $id . ': ', $result ?? []);
            if ($result['success']) {
                return back()->with('success', 'Đã gửi yêu cầu test. Vui lòng đợi (1-5 phút) hệ thống tự động cập nhật.');
            } else {
                return back()->with('error', 'Lỗi khi gọi API Test: ' . ($result['message'] ?? 'Không rõ'));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi ngoại lệ khi test: ' . $e->getMessage());
        }
    }

    public function regenerateQR(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);
        
        // Chỉ chạy khi vé chưa thanh toán
        if ($registration->payment_status === 'paid') {
            return back()->with('error', 'Vé đã thanh toán, không thể tạo lại QR.');
        }

        try {
            $vietQRService = new VietQRService();
            // Lấy thông tin từ config
            $amount = (int)$registration->members * (int)config('services.vietqr.price_ticket');
            $accountNo = config('services.vietqr.account_no');
            $accountName = config('services.vietqr.account_name');
            $content = $registration->ticket_id;

            // Gọi API tạo QR (đã hoàn thiện ở các bước trước)
            $qrData = $vietQRService->generateQRCode($amount, $content, $accountNo, $accountName);
            Log::info('Kết quả tạo lại QR cho đăng ký ID ' . $id . ': ', $qrData ?? []);
            // Kiểm tra kết quả (theo response của API mới)
            if ($qrData && isset($qrData['qrCode'])) {
                $registration->vietqr_data = $qrData; // Lưu toàn bộ JSON response
                $registration->save();
                return back()->with('success', 'Đã tạo lại mã QR thành công!');
            } else {
                Log::error('Admin test tạo QR thất bại: ' . $registration->ticket_id, $qrData ?? []);
                return back()->with('error', 'Tạo lại QR thất bại. Vui lòng kiểm tra log.');
            }

        } catch (\Exception $e) {
            Log::error('Lỗi ngoại lệ khi admin tạo QR: ' . $e->getMessage());
            return back()->with('error', 'Lỗi ngoại lệ: ' . $e->getMessage());
        }
    }
}