@extends('layouts.admin')

{{-- Đặt tiêu đề riêng cho trang này --}}
@section('title', 'Dashboard - Danh sách đăng ký')

{{-- Thêm CSS riêng cho trang này nếu cần --}}
@push('styles')
<style>
    /* CSS RIÊNG CHO TRANG DASHBOARD */
    h1 {
        color: #001f4d;
        margin-bottom: 1rem; /* Giảm margin 1 chút */
    }
    .table-wrapper {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        overflow-x: auto; /* Giúp bảng cuộn ngang trên màn hình nhỏ */
    }
    /* Thêm CSS cho khu vực tìm kiếm */
    .search-wrapper {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }
    .search-wrapper input[type="text"] {
        flex-grow: 1;
        max-width: 400px;
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .search-wrapper button {
        padding: 8px 15px;
        font-size: 0.9rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: middle;
    }
    th {
        background-color: #f8f9fa;
        font-weight: bold;
        white-space: nowrap; /* Giữ tiêu đề trên 1 hàng */
    }
    tr:last-child td {
        border-bottom: none;
    }
    tr:hover {
        background-color: #f8f9fa;
    }
    .status {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .status-paid { background-color: #d4edda; color: #155724; }
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-used { background-color: #d1ecf1; color: #0c5460; }
    .status-not_used { background-color: #e2e3e5; color: #383d41; }
    .btn {
        display: inline-block;
        cursor: pointer;
        border: none;
        padding: 6px 12px;
        font-size: 0.9rem;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        font-weight: 500;
        white-space: nowrap;
    }
    .btn-checkin { background-color: #28a745; }
    .btn-resend { background-color: #007bff; }
    /* Thêm màu cho nút Đánh dấu Thanh Toán */
    .btn-mark-paid { background-color: #ffc107; color: #212529; } 

    .btn:disabled { background-color: #6c757d; cursor: not-allowed; opacity: 0.65; }
    .actions-group {
        display: flex;
        gap: 5px;
    }
</style>
@endpush

{{-- Bắt đầu phần nội dung chính của trang --}}
@section('content')
<h1>Danh sách đăng ký</h1>

{{-- Form Tìm kiếm --}}
<div class="search-wrapper">
    <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; width: 100%; gap: 10px;">
        <input type="text" name="search" placeholder="Tìm theo ID, Ticket ID, Tên, Email, SĐT..." value="{{ $search ?? '' }}">
        <button type="submit" class="btn btn-resend">Tìm kiếm</button>
        <a href="{{ route('admin.dashboard') }}" class="btn" style="background-color: #6c757d;">Xóa lọc</a>
    </form>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                {{-- THÊM CỘT TICKET ID --}}
                <th>Ticket ID</th> 
                <th>Thông tin Khách</th>
                <th>Liên hệ</th>
                {{-- THÊM CỘT NGÀY ĐĂNG KÝ --}}
                <th>Ngày ĐK</th>
                <th>Số thành viên</th>
                <th>Thanh toán</th>
                <!-- <th>Check-in</th> -->
                {{-- THÊM CỘT TRẠNG THÁI EMAIL --}}
                <!-- <th>Email</th> -->
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registrations as $reg)
                <tr>
                    <td>
                        {{-- Hiển thị Ticket ID --}}
                        <strong style="font-family: monospace; color: #001f4d;">{{ $reg->ticket_id }}</strong>
                    </td>
                    <td>
                        <strong>{{ $reg->name }}</strong><br>
                        <small style="color: #6c757d;">{{ $reg->position ?? 'Chưa có chức vụ' }}</small>
                    </td>
                    <td>
                        {{ $reg->email }}<br>
                        <small style="color: #6c757d;">{{ $reg->phone }}</small> <br>
                        <small style="color: #6c757d;">{{ $reg->address }}</small>
                    </td>
                    <td>
                        {{-- Hiển thị ngày đăng ký (created_at) --}}
                        <small>{{ $reg->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        {{ $reg->members ?? 1 }}
                    </td>
                    <td>
                        <span class="status status-{{ $reg->payment_status }}">{{ $reg->payment_status }}</span>
                    </td>
                    <!-- <td>
                        <span class="status status-{{ $reg->ticket_status }}">{{ str_replace('_', ' ', $reg->ticket_status) }}</span>
                    </td> -->
                    <!-- <td>
                        {{-- Hiển thị trạng thái gửi email --}}
                        @if ($reg->email_sent_at)
                            <span class="status status-paid" title="Gửi lúc: {{ $reg->email_sent_at->format('d/m/Y H:i') }}">Đã gửi</span>
                        @else
                            <span class="status status-pending">Chưa gửi</span>
                        @endif
                    </td> -->
                    <td>
                        <div class="actions-group">
                            {{-- THÊM NÚT ĐÁNH DẤU THANH TOÁN --}}
                            <form action="{{ route('admin.registrations.mark_as_paid', $reg->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-mark-paid" {{ $reg->payment_status == 'paid' ? 'disabled' : '' }} title="Đánh dấu đã thanh toán">
                                    Thanh Toán
                                </button>
                            </form>

                            <!-- <form action="{{ route('admin.registrations.checkin', $reg->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-checkin" {{ $reg->payment_status != 'paid' || $reg->ticket_status == 'used' ? 'disabled' : '' }} title="Check-in tại sự kiện">
                                    Check-in
                                </button>
                            </form> -->
                            <!-- <form action="{{ route('admin.registrations.resend_email', $reg->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-resend" {{ $reg->payment_status != 'paid' ? 'disabled' : '' }} title="Gửi lại email vé mời">
                                    Gửi lại Mail
                                </button>
                            </form> -->
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    {{-- Cập nhật colspan thành 8 --}}
                    <td colspan="8" style="text-align: center; padding: 20px;">Không tìm thấy lượt đăng ký nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Thêm link phân trang --}}
<div class="pagination-wrapper" style="margin-top: 20px;">
    {{-- Thêm appends để giữ nguyên tham số search khi chuyển trang --}}
    {{ $registrations->appends(['search' => $search ?? ''])->links() }}
</div>

@endsection