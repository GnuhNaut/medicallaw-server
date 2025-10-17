@extends('layouts.admin')

{{-- Đặt tiêu đề riêng cho trang này --}}
@section('title', 'Dashboard - Danh sách đăng ký')

{{-- Thêm CSS riêng cho trang này nếu cần --}}
@push('styles')
<style>
    /* CSS RIÊNG CHO TRANG DASHBOARD */
    h1 {
        color: #001f4d;
        margin-bottom: 2rem;
    }
    .table-wrapper {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        overflow-x: auto; /* Giúp bảng cuộn ngang trên màn hình nhỏ */
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
        white-space: nowrap;
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
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Thông tin Khách</th>
                <th>Liên hệ</th>
                <th>Số lượng</th>
                <th>Thanh toán</th>
                <th>Check-in</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registrations as $reg)
                <tr>
                    <td>
                        <strong>{{ $reg->name }}</strong><br>
                        <small style="color: #6c757d;">{{ $reg->position ?? 'Chưa có chức vụ' }}</small>
                    </td>
                    <td>
                        {{ $reg->email }}<br>
                        <small style="color: #6c757d;">{{ $reg->phone }}</small>
                    </td>
                    <td style="text-align: center;">{{ $reg->members }}</td>
                    <td>
                        <span class="status status-{{ $reg->payment_status }}">{{ $reg->payment_status }}</span>
                    </td>
                    <td>
                        {{-- Hiển thị "Đã sử dụng" hoặc "Chưa sử dụng" cho thân thiện hơn --}}
                        <span class="status status-{{ $reg->ticket_status }}">{{ str_replace('_', ' ', $reg->ticket_status) }}</span>
                    </td>
                    <td>
                        <div class="actions-group">
                            <form action="{{ route('admin.registrations.checkin', $reg->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-checkin" {{ $reg->payment_status != 'paid' || $reg->ticket_status == 'used' ? 'disabled' : '' }}>
                                    Check-in
                                </button>
                            </form>
                            <form action="{{ route('admin.registrations.resend_email', $reg->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-resend" {{ $reg->payment_status != 'paid' ? 'disabled' : '' }}>
                                    Gửi lại Mail
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Chưa có lượt đăng ký nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection