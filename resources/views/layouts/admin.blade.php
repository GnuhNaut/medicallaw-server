<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Tiêu đề trang sẽ được các trang con định nghĩa --}}
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <style>
        /* CSS CHUNG CHO TOÀN BỘ TRANG ADMIN */
        body { font-family: 'Nunito', sans-serif; background-color: #f8f9fa; color: #333; margin: 0; }
        .admin-nav {
            background-color: #001f4d; /* Navy */
            padding: 0 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            color: #fff;
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }
        .nav-brand {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ffd700; /* Gold */
            text-decoration: none;
        }
        .nav-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
        }
        .nav-links a:hover, .nav-links a.active {
            color: #ffd700;
        }
        .nav-user #logout-form button {
            background: none; border: none; color: #fff; cursor: pointer;
            font-size: 1rem; font-family: 'Nunito', sans-serif;
        }
        .nav-user #logout-form button:hover {
            color: #ffd700;
        }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
    </style>
    {{-- Cho phép các trang con thêm CSS riêng nếu cần --}}
    @stack('styles')
</head>
<body>
    <nav class="admin-nav">
        <div class="nav-container">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="nav-brand">Medicallaw Admin</a>
            </div>
            <div class="nav-links">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.registrations.paid') }}" class="{{ request()->routeIs('admin.registrations.paid') ? 'active' : '' }}">
                        Đã thanh toán
                    </a>
                <a href="{{ route('admin.manual_send.form') }}" class="{{ request()->routeIs('admin.manual_send.form') ? 'active' : '' }}">Gửi Mail Thủ Công</a>
            </div>
            <div class="nav-user">
                <span>{{ Auth::user()->name }}</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline; margin-left: 15px;">
                    @csrf
                    <button type="submit">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        {{-- Nội dung chính của từng trang sẽ được đặt vào đây --}}
        @yield('content')
    </main>

</body>
</html>