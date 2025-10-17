<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký hội nghị mới</title>
</head>
<body style="font-family: sans-serif; background: #f8f9fa; padding: 24px;">
    <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; border: 1px solid #e0e0e0; box-shadow: 0 2px 8px #0001;">
        <div style="background:#001f4d; color:#ffd700; padding: 20px 24px; border-radius: 8px 8px 0 0;">
            <h2 style="margin:0; font-size:22px;">Có lượt đăng ký mới</h2>
            <p style="margin:0; font-size:14px;">Hội nghị Quốc tế M&A Y Tế Việt Nam 2025</p>
        </div>
        <div style="padding:24px;">
            <h3 style="color:#001f4d;">Thông tin chi tiết:</h3>
            <ul style="font-size:16px; line-height:1.8; list-style:none; padding-left:0;">
                <li><strong>Họ và tên:</strong> {{ $registration->name }}</li>
                <li><strong>Chức vụ/Đơn vị:</strong> {{ $registration->position ?? 'N/A' }}</li>
                <li><strong>Email:</strong> {{ $registration->email }}</li>
                <li><strong>Số điện thoại:</strong> {{ $registration->phone }}</li>
                <li><strong>Địa chỉ:</strong> {{ $registration->address }}</li>
                <li><strong>Số lượng:</strong> {{ $registration->members }}</li>
                <li><strong>Lĩnh vực:</strong> {{ $registration->field ?? 'N/A' }}</li>
                <li><strong>Nhu cầu tham dự:</strong> {{ $registration->guest_type ?? 'N/A' }}</li>
                <li><strong>Câu hỏi:</strong> {{ $registration->question ?? 'N/A' }}</li>
                <li><strong>Mã vé:</strong> {{ $registration->ticket_id }}</li>
                <li><strong>Thời gian:</strong> {{ $registration->created_at->format('H:i:s d/m/Y') }}</li>
            </ul>
        </div>
        <div style="background:#f0f0f0; color:#555; padding:12px 24px; border-radius:0 0 8px 8px; text-align:center; font-size:13px;">
            Đây là email tự động. Vui lòng không trả lời.
        </div>
    </div>
</body>
</html>