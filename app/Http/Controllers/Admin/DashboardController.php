<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Mail\ThankYouMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard với danh sách đăng ký.
     */
    public function index()
    {
        // Lấy tất cả các lượt đăng ký, sắp xếp theo thời gian mới nhất
        $registrations = Registration::orderBy('created_at', 'desc')->get();

        // Trả về view và truyền dữ liệu qua
        return view('admin.dashboard', ['registrations' => $registrations]);
    }

    /**
     * Cập nhật trạng thái vé thành 'used' (đã check-in).
     */
    public function checkIn($id)
    {
        $registration = Registration::findOrFail($id);
        
        // Chỉ check-in khi đã thanh toán
        // if ($registration->payment_status === 'paid') {
            $registration->ticket_status = 'used';
            $registration->save();
            return back()->with('success', 'Check-in vé ' . $registration->ticket_id . ' thành công!');
        // }

        // return back()->with('error', 'Không thể check-in vé chưa thanh toán!');
    }
    
    /**
     * Gửi lại email cảm ơn và vé cho khách.
     */
    public function resendEmail($id)
    {
        $registration = Registration::findOrFail($id);
        
        // Chỉ gửi lại email cho vé đã thanh toán
        // if ($registration->payment_status === 'paid') {
            try {
                Mail::to($registration->email)->send(new ThankYouMail($registration));
                return back()->with('success', 'Đã gửi lại email cho ' . $registration->email);
            } catch (\Exception $e) {
                return back()->with('error', 'Gửi email thất bại: ' . $e->getMessage());
            }
        // }
        
        // return back()->with('error', 'Không thể gửi email cho vé chưa thanh toán!');
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

        // 4. Nếu tìm thấy, gọi lại hàm gửi mail đã có
        return $this->resendEmail($registration->id);
    }
}