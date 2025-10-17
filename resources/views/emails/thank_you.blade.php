<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Cảm ơn bạn đã đăng ký tham dự</title>
</head>
<body style="font-family:sans-serif;background:#f8f9fa;padding:24px;">
  <div style="max-width:600px;margin:auto;background:#fff;border-radius:8px;border:1px solid #e0e0e0;box-shadow:0 2px 8px #0001;">
    <div style="color:#001f4d;padding:20px 24px 10px 24px;border-radius:8px 8px 0 0;text-align:center;">
      <img src="{{ $message->embed($logoPath) }}" alt="Medical Law Logo" style="max-width:180px;margin-bottom:16px;">
      <h2 style="margin:0;font-size:22px;">Cảm ơn bạn đã đăng ký tham dự!</h2>
    </div>
    <div style="padding:24px 24px 12px 24px;">
      <p style="font-size:16px;">Kính gửi Quý khách <b>{{ $registration->name }}</b>,</p>
      <p style="font-size:16px;">Ban Tổ Chức trân trọng cảm ơn Anh/Chị đã đăng ký tham dự<strong>HỘI NGHỊ QUỐC TẾ VỀ MUA BÁN VÀ SÁP NHẬP (M&A) TRONG NGÀNH Y TẾ NĂM 2025 - HIMA 2025 (Lần thứ 1)</strong> do <strong>Medicallaw</strong> tổ chức.</p>
      
      <div style="text-align:center; margin: 20px 0;">
          <h3 style="color:#001f4d;">VÉ THAM DỰ CỦA BẠN</h3>
          <p style="font-size:14px; margin-top: 10px; color:#666;">
            Mã QR Code của bạn đã được đính kèm trong email này.<br>
            Vui lòng tải xuống và sử dụng mã QR này để check-in tại sự kiện.
          </p>
      </div>

      <p style="font-size:16px;">
        <b>📌 Thông tin sự kiện:</b>
        <ul style="margin-left: 20px; font-size: 16px; line-height: 1.6;">
          <li><strong>Thời gian</strong>: 7:30 - 13:30, ngày 21 tháng 11 năm 2025</li>
          <li><strong>Địa điểm</strong>: Khách sạn Meliá, 44 Lý Thường Kiệt, Phường Cửa Nam, Hà Nội</li>
          <li><strong>Chủ đề</strong>: <i>Minh bạch pháp lý – Giá trị Y khoa</i></li>
        </ul>
      </p>
      <p style="font-size:16px;">Để biết thêm thông tin chi tiết về chương trình nghị sự, diễn giả và các thông tin hậu cần khác, vui lòng truy cập: <a href="https://event.medicallaw.vn/" target="_blank" style="color:#001f4d;">event.medicallaw.vn</a></p>
      <p style="font-size:16px;">
        Mọi thắc mắc xin liên hệ:<br>
        <p>
          📞 Hotline: +84 559.322.322 / +84 914.266.688 (Mrs. Huong) / + 84 911.833.899(Mr.Cuong)<br>
          📧 Email: info@medicallaw.vn
        </p>
      </p>
      <p style="font-size:15px;">Chúng tôi rất mong được chào đón Quý khách tại hội nghị.</p>
      <p style="font-size:15px;">Trân trọng,<br>Ban Tổ chức - CÔNG TY TNHH TƯ VẤN LUẬT Y TẾ VIỆT NAM (MEDICALLAW)</p>
    </div>

    <div style="border-top: 1px solid #e0e0e0; margin: 20px 0;"></div>

    <div style="padding:12px 24px 24px 24px;">
        <h2 style="margin:0;font-size:22px;text-align:center; color:#001f4d;">Thank you for your registration!</h2>
        <p style="font-size:16px;">Dear Mr./Ms. <b>{{ $registration->name }}</b>,</p>
        <p style="font-size:16px;">The Organizing Committee greatly appreciates your registration to attend <strong>THE INTERNATIONAL CONFERENCE ON MERGERS AND ACQUISITIONS (M&A) IN THE HEALTHCARE SECTOR 2025 -HIMA 2025 (1st Edition)</strong>, organized by <strong>Medicallaw</strong>.</p>

        <div style="text-align:center; margin: 20px 0;">
          <p style="font-size:14px; margin-top: 10px; color:#666;">
            Your QR Code is attached to this email.<br>
            Please download and use this QR code to check-in at the event.
          </p>
        </div>

        <p style="font-size:16px;">
            <b>📌 Event Information:</b>
            <ul style="margin-left: 20px; font-size: 16px; line-height: 1.6;">
              <li><strong>Time</strong>: 7:30 AM - 1:30 PM, November 21, 2025</li>
              <li><strong>Venue</strong>: Meliá Hotel, 44 Ly Thuong Kiet Street, Cua Nam Ward, Hanoi</li>
              <li><strong>Theme</strong>: <i>Legal Transparency - Healthcare Value</i></li>
            </ul>
        </p>
        <p style="font-size:16px;">For detailed agenda, speaker list, and logistical information, please visit: <a href="https://event.medicallaw.vn/" target="_blank" style="color:#001f4d;">event.medicallaw.vn</a></p>
        <p style="font-size:15px;">We look forward to welcoming you to the conference.</p>

        <p style="font-size:16px;">
          For any questions, please contact us:<br>
          <p>
            📞 Hotline: +84 559.322.322 / +84 914.266.688 (Mrs. Huong) / + 84 911.833.899(Mr.Cuong)<br>
            📧 Email: info@medicallaw.vn
          </p>
        </p>
        <p style="font-size:15px;">Sincerely,<br>The Organizing Committee - VIETNAM MEDICAL LAW CONSULTING COMPANY LIMITED (MEDICALLAW)</p>
    </div>

    <div style="background:#ffd700;color:#001f4d;padding:12px 24px;border-radius:0 0 8px 8px;font-size:13px; text-align: center;">
      Minh bạch pháp lý – Giá trị Y khoa | Legal Transparency - Healthcare Value
    </div>
  </div>
</body>
</html>
