<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Xuân Media</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Custom styles để tinh chỉnh */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #121212; /* Màu nền tối hơn một chút */
            color: #E0E0E0; /* Màu chữ chính */
        }
        /* Tạo hiệu ứng gradient cho accent color */
        .accent-gradient {
            background: linear-gradient(90deg, #10B981, #34D399);
        }
        .accent-text {
            color: #10B981;
        }
        .card-bg {
            background-color: #1E1E1E; /* Màu nền cho các thẻ card */
            border: 1px solid #2d2d2d;
        }
        .card-bg:hover {
            border-color: #10B981;
            transform: translateY(-5px);
        }
        .section-title {
            position: relative;
            display: inline-block;
            padding-bottom: 0.5rem;
            margin-bottom: 2rem;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: #10B981;
            border-radius: 2px;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Container chính -->
    <div class="container mx-auto max-w-5xl px-6 py-12 md:py-20">

        <!-- ===== HERO SECTION ===== -->
        <header class="text-center flex flex-col items-center">
            <!-- Ảnh đại diện -->
            <img 
                src="https://xuan.media/wp-content/uploads/2024/07/LOGO-XUAN-MEDIA-1-e1720601225789.webp" 
                alt="Ảnh đại diện Xuân Media" 
                class="rounded-lg border-4 border-emerald-500 object-cover shadow-lg mb-6"
                onerror="this.onerror=null;this.src='https://placehold.co/128x128/CCCCCC/FFFFFF?text=Error';"
            >
            
            <!-- Tên và chức danh -->
            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight">
                Xuân Media
            </h1>
            <p class="mt-2 text-lg md:text-xl font-medium text-gray-300">
                WEBSITE DESIGNER & DEVELOPER
            </p>
            
            <!-- Các liên kết mạng xã hội -->
            <div class="flex items-center justify-center space-x-5 mt-6">
                <a href="https://www.facebook.com/xuan.media.official" class="text-gray-400 hover:text-emerald-400 transition-colors duration-300">
                    <i class="fab fa-facebook-f fa-lg"></i>
                </a>
                <a href="https://www.facebook.com/xuan.media.official" class="text-gray-400 hover:text-emerald-400 transition-colors duration-300">
                    <i class="fab fa-facebook-messenger fa-lg"></i> <!-- Giả lập Zalo icon -->
                </a>
                <a href="tel:0961266266" class="text-gray-400 hover:text-emerald-400 transition-colors duration-300">
                    <i class="fas fa-phone fa-lg"></i>
                </a>
                <a href="xuan.media" class="text-gray-400 hover:text-emerald-400 transition-colors duration-300">
                    <i class="fas fa-envelope fa-lg"></i>
                </a>
            </div>

            <!-- Nút CTA -->
            <a href="#contact" class="mt-8 px-8 py-3 rounded-full accent-gradient text-white font-bold text-lg shadow-lg hover:shadow-emerald-500/50 transform hover:scale-105 transition-all duration-300">
                LIÊN HỆ
            </a>
        </header>

        <main class="mt-20 md:mt-28">
            <!-- ===== SERVICE SECTION ===== -->
            <section id="services" class="scroll-mt-20">
                <h2 class="section-title text-3xl font-bold text-center text-white">DỊCH VỤ CỦA TÔI</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Service Card 1 -->
                    <div class="card-bg p-6 rounded-lg text-center transition-all duration-300">
                        <div class="text-4xl accent-text mb-4"><i class="fas fa-laptop-code"></i></div>
                        <h3 class="text-xl font-bold text-white mb-2">Thiết kế Website</h3>
                        <p class="text-gray-400">Tạo ra các trang web chuyên nghiệp, chuẩn SEO, và tương thích với mọi thiết bị.</p>
                    </div>
                    <!-- Service Card 2 -->
                    <div class="card-bg p-6 rounded-lg text-center transition-all duration-300">
                        <div class="text-4xl accent-text mb-4"><i class="fas fa-camera-retro"></i></div>
                        <h3 class="text-xl font-bold text-white mb-2">Chụp ảnh sản phẩm</h3>
                        <p class="text-gray-400">Hình ảnh sản phẩm sắc nét, ấn tượng, giúp tăng tỉ lệ chuyển đổi.</p>
                    </div>
                    <!-- Service Card 3 -->
                    <div class="card-bg p-6 rounded-lg text-center transition-all duration-300">
                        <div class="text-4xl accent-text mb-4"><i class="fas fa-drafting-compass"></i></div>
                        <h3 class="text-xl font-bold text-white mb-2">Thiết kế Logo</h3>
                        <p class="text-gray-400">Sáng tạo logo độc đáo, thể hiện đúng tinh thần và giá trị thương hiệu của bạn.</p>
                    </div>
                    <!-- Service Card 4 -->
                    <div class="card-bg p-6 rounded-lg text-center transition-all duration-300">
                        <div class="text-4xl accent-text mb-4"><i class="fas fa-video"></i></div>
                        <h3 class="text-xl font-bold text-white mb-2">Quay dựng Video</h3>
                        <p class="text-gray-400">Sản xuất video quảng cáo, giới thiệu sản phẩm chuyên nghiệp và thu hút.</p>
                    </div>
                    <!-- Service Card 5 -->
                    <div class="card-bg p-6 rounded-lg text-center transition-all duration-300">
                        <div class="text-4xl accent-text mb-4"><i class="fas fa-pen-nib"></i></div>
                        <h3 class="text-xl font-bold text-white mb-2">Viết Content Chuẩn SEO</h3>
                        <p class="text-gray-400">Nội dung hấp dẫn, tối ưu hóa cho công cụ tìm kiếm để tiếp cận đúng khách hàng.</p>
                    </div>
                    <!-- Service Card 6 -->
                    <div class="card-bg p-6 rounded-lg text-center transition-all duration-300">
                        <div class="text-4xl accent-text mb-4"><i class="fas fa-tasks"></i></div>
                        <h3 class="text-xl font-bold text-white mb-2">Quản trị Website</h3>
                        <p class="text-gray-400">Chăm sóc, cập nhật và bảo trì website của bạn để hoạt động luôn ổn định.</p>
                    </div>
                </div>
            </section>

            <!-- ===== PROJECT SECTION ===== -->
            <section id="projects" class="mt-20 md:mt-28 scroll-mt-20">
                <h2 class="section-title text-3xl font-bold text-center text-white">DỰ ÁN CỦA TÔI</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Project Card 1 -->
                    <div class="card-bg rounded-lg overflow-hidden group transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1585160994348-3d59a7276369?q=80&w=600&h=400&auto=format&fit=crop" alt="Dự án Website Thương mại điện tử" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">Website Thương mại điện tử</h3>
                            <p class="text-gray-400 mt-2">Xây dựng trang web bán hàng online với đầy đủ tính năng giỏ hàng, thanh toán.</p>
                        </div>
                    </div>
                    <!-- Project Card 2 -->
                    <div class="card-bg rounded-lg overflow-hidden group transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1542744095-291d1f67b221?q=80&w=600&h=400&auto=format&fit=crop" alt="Dự án Landing Page Sự kiện" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">Landing Page Sự kiện</h3>
                            <p class="text-gray-400 mt-2">Thiết kế landing page cho sự kiện ra mắt sản phẩm mới, tối ưu hóa chuyển đổi.</p>
                        </div>
                    </div>
                     <!-- Project Card 3 -->
                    <div class="card-bg rounded-lg overflow-hidden group transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1502945015378-0e284ca1a5be?q=80&w=600&h=400&auto=format&fit=crop" alt="Dự án Website Portfolio" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">Website Portfolio Cá nhân</h3>
                            <p class="text-gray-400 mt-2">Trang web giới thiệu bản thân và các dự án đã thực hiện cho một nhiếp ảnh gia.</p>
                        </div>
                    </div>
                     <!-- Project Card 4 -->
                    <div class="card-bg rounded-lg overflow-hidden group transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-155782583a-89c6a84000a6?q=80&w=600&h=400&auto=format&fit=crop" alt="Dự án Bộ nhận diện thương hiệu" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">Bộ nhận diện thương hiệu</h3>
                            <p class="text-gray-400 mt-2">Thiết kế logo và các ấn phẩm văn phòng cho một công ty khởi nghiệp.</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- ===== CONTACT SECTION ===== -->
            <section id="contact" class="mt-20 md:mt-28 scroll-mt-20">
                <h2 class="section-title text-3xl font-bold text-center text-white">THÔNG TIN LIÊN HỆ</h2>
                <div class="card-bg max-w-lg mx-auto p-8 rounded-lg">
                    <ul class="space-y-4 text-lg">
                        <li class="flex items-center">
                            <i class="fas fa-phone w-6 accent-text"></i>
                            <a href="tel:0961266266" class="text-gray-300 hover:text-white transition-colors">0961.266.266</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope w-6 accent-text"></i>
                            <a href="mailto:contact@xuan.media" class="text-gray-300 hover:text-white transition-colors">contact@xuan.media</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt w-6 accent-text"></i>
                            <span class="text-gray-300">Hà Nội, Việt Nam</span>
                        </li>
                    </ul>
                </div>
            </section>

        </main>
        
        <!-- ===== FOOTER ===== -->
        <footer class="text-center mt-20 md:mt-28 border-t border-gray-800 pt-8">
            <p class="text-gray-500">&copy; 2024 Xuân Media. All rights reserved.</p>
        </footer>

    </div>

</body>
</html>

