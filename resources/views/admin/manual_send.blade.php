@extends('layouts.admin')

@section('title', 'Gửi Email Thủ Công')

@push('styles')
<style>
    /* CSS RIÊNG CHO TRANG NÀY */
    .card { background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); width: 100%; max-width: 500px; margin: 2rem auto; }
    .card-header { background-color: #001f4d; color: #ffd700; font-size: 1.25rem; font-weight: bold; padding: 1rem 1.25rem; border-top-left-radius: 8px; border-top-right-radius: 8px; }
    .card-body { padding: 2rem; }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; font-weight: bold; color: #495057; }
    .form-control { display: block; width: 100%; padding: 0.75rem; font-size: 1rem; border: 1px solid #ced4da; border-radius: 0.25rem; box-sizing: border-box; }
    .btn { display: inline-block; cursor: pointer; background-color: #007bff; color: white; font-weight: bold; text-align: center; padding: 0.75rem 1.5rem; font-size: 1rem; border: 1px solid #007bff; border-radius: 0.25rem; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">Gửi Lại Email Vé Mời</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.manual_send.submit') }}">
            @csrf
            <div class="form-group">
                <label for="identifier">Nhập Ticket ID hoặc Email</label>
                <input id="identifier" type="text" class="form-control" name="identifier" required autofocus placeholder="Ví dụ: ABC123XYZ hoặc user@example.com">
            </div>

            <div class="form-group">
                <button type="submit" class="btn">
                    Gửi Email
                </button>
            </div>
        </form>
    </div>
</div>
@endsection