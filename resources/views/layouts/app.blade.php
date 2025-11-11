<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Quản lý thực tập')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->

    <style>
    .avatar-initials {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        width: 40px;
        height: 40px;
        font-weight: 600;
    }

    .sidebar-collapsed .nav-text {
        display: none;
    }

    .sidebar-collapsed {
        width: 80px;
    }

    .sidebar-collapsed .logo-text,
    .sidebar-collapsed .user-info {
        display: none;
    }

    .stat-card {
        background: linear-gradient(135deg, #4A7FA7 0%, #5a93c1 100%);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(74, 127, 167, 0.3);
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .nav-item {
        transition: all 0.2s ease;
    }

    .nav-item:hover {
        background-color: rgba(74, 127, 167, 0.1);
        border-left: 4px solid #4A7FA7;
    }

    .nav-item.active {
        background-color: rgba(74, 127, 167, 0.15);
        border-left: 4px solid #4A7FA7;
        color: #4A7FA7;
    }

    .scroll-to-top {
        transition: all 0.3s ease;
        opacity: 0;
        pointer-events: none;
    }

    .scroll-to-top.show {
        opacity: 1;
        pointer-events: all;
    }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex">

        <!-- SIDEBAR -->
        <aside id="sidebar" class="bg-white border-r shadow-lg transition-all duration-300 hidden md:block">
            <a href="{{ route('sinhvien.dashboard') }}" class="block no-underline text-inherit">
                <div class="p-4 border-b cursor-pointer hover:bg-gray-100 transition">
                    <div class="flex items-center gap-1">
                        <!-- Logo -->
                        <img src="{{ asset('images/logo1.png') }}" alt="Logo Trường Đại học Đồng Tháp"
                            class="h-12 w-auto cursor-pointer select-none">

                        <!-- Văn bản -->
                        <div class="logo-text leading-tight">
                            <h1 class="text-sm font-bold" style="color:#f44336;">
                                TRƯỜNG ĐẠI HỌC ĐỒNG THÁP
                            </h1>
                            <p class="text-xs font-semibold" style="color: #122c4f;">
                                Hệ thống Quản lý Thực tập Doanh nghiệp
                            </p>
                        </div>

                    </div>

                </div>
            </a>

            <nav class="p-3 flex-1 overflow-y-auto">
                <ul class="space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('sinhvien.dashboard') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('sinhvien.dashboard') }}">
                            <i class="fas fa-home w-5 text-lg"></i>
                            <span class="nav-text">Tổng quan</span>
                        </a>
                    </li>

                    <!-- Vị trí thực tập -->
                    <li>
                        <a href="{{ route('sinhvien.vitri_sinhvien.list') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('sinhvien.vitri_sinhvien.list') }}">
                            <i class="fas fa-briefcase w-5 text-lg"></i>
                            <span class="nav-text">Vị trí thực tập</span>
                        </a>
                    </li>

                    <!-- Đăng ký của tôi -->
                    <li>
                        <a href="{{ route('sinhvien.dangkythuctap.index') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('sinhvien.dangkythuctap.index') }}">
                            <i class="fas fa-file-signature w-5 text-lg"></i>
                            <span class="nav-text">Đăng ký của tôi</span>
                        </a>
                    </li>

                    <!-- Tiến độ thực tập -->
                    <li>
                        <a href="{{ route('sinhvien.tiendo.index') }}" class=" nav-item flex items-center gap-3
                            p-3 rounded-lg" data-route="{{ route('sinhvien.tiendo.index') }}">
                            <i class="fas fa-tasks w-5 text-lg"></i>
                            <span class="nav-text">Tiến độ thực tập</span>
                        </a>
                    </li>

                    <!-- Báo cáo -->
                    <li>
                        <a href="{{ route('sinhvien.baocao.index') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('sinhvien.baocao.index') }}">
                            <i class="fas fa-file-alt w-5 text-lg"></i>
                            <span class="nav-text">Báo cáo</span>
                        </a>
                    </li>

                    <!-- Đánh giá -->
                    <li>
                        <a href="{{ route('sinhvien.danhgia.index') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('sinhvien.danhgia.index') }}">
                            <i class="fas fa-star w-5 text-lg"></i>
                            <span class="nav-text">Đánh giá</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t mt-auto">
                <div class="flex items-center gap-3 hover:bg-gray-50 p-2 rounded-lg transition">
                    <div class="avatar-initials bg-[#4A7FA7] text-white">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="text-sm user-info">
                        <div class="font-semibold text-gray-800">
                            {{ Auth::user()->sinhvien->ho_ten ?? Auth::user()->username }}</div>
                        <div class="text-xs text-gray-500">
                            {{ Auth::user()->sinhvien->nganh ?? '' }} - {{ Auth::user()->sinhvien->lop ?? '' }}
                        </div>
                    </div>
                </div>
            </div>


        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- HEADER -->
            <header class="bg-white border-b shadow-sm p-4 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button id="btn-toggle-sidebar" class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-[#4A7FA7] text-xl"></i>
                    </button>
                    <!-- <h2 class="text-xl font-bold text-[#4A7FA7]">@yield('page_title', 'Dashboard')</h2> -->
                </div>

                <div class="flex items-center gap-4">


                    <!-- <button id="btn-thongbao" class="relative p-2 rounded-lg hover:bg-gray-100 transition"
                        title="Thông báo">
                        <i class="fas fa-bell text-[#4A7FA7] text-xl"></i>
                        <span id="badge-thongbao"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold hidden">0</span>
                    </button> -->

                    <button id="btn-thongbao" class="relative p-2 rounded-lg hover:bg-gray-100 transition"
                        title="Thông báo">
                        <i class="fas fa-bell text-[#4A7FA7] text-xl"></i>
                        <span id="badge-thongbao"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold hidden">0</span>
                    </button>



                    <div class="relative">
                        <button id="user-menu-btn"
                            class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-9 h-9 rounded-full overflow-hidden border border-gray-200 shadow-sm">
                                <img src="{{ Auth::user()->avatar 
                ? asset('storage/upload/avatar/' . Auth::user()->avatar) 
                : asset('images/default.png') }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>

                            <span class="hidden sm:inline font-medium text-gray-700">
                                {{ Auth::user()->sinhvien->ho_ten ?? Auth::user()->username }}
                            </span>

                        </button>
                        <div id="user-menu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden z-50">


                            <!-- Hồ sơ -->
                            <a href="{{ route('sinhvien.hoso') }}"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-user text-gray-400 text-lg"></i>
                                <span class="font-medium">Hồ sơ</span>
                            </a>


                            <!-- Đổi mật khẩu -->
                            <a href="{{ route('sinhvien.hoso.doimatkhau') }}"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-lock text-gray-400 text-lg"></i>
                                <span class="font-medium">Đổi mật khẩu</span>
                            </a>


                            <!-- Đăng xuất -->
                            <a href="{{ route('logout') }}"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-sign-out-alt text-gray-400 text-lg"></i>
                                <span class="font-medium">Đăng xuất</span>
                            </a>
                        </div>


                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main id="main-content" class="p-6 flex-1 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: <?= json_encode(session('success')) ?>,
            confirmButtonColor: '#4A7FA7'
        });
        <?php endif; ?>

        <?php if (session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: <?= json_encode(session('error')) ?>,
            confirmButtonColor: '#4A7FA7'
        });
        <?php endif; ?>

        <?php if (session('warning')): ?>
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo!',
            text: <?= json_encode(session('warning')) ?>,
            confirmButtonColor: '#4A7FA7'
        });
        <?php endif; ?>

        <?php if (session('info')): ?>
        Swal.fire({
            icon: 'info',
            title: 'Thông báo!',
            text: <?= json_encode(session('info')) ?>,
            confirmButtonColor: '#4A7FA7'
        });
        <?php endif; ?>
    });
    </script>
    <script>
    const sidebar = document.getElementById('sidebar');
    const btnToggle = document.getElementById('btn-toggle-sidebar');
    const userMenuBtn = document.getElementById('user-menu-btn');
    const userMenu = document.getElementById('user-menu');

    btnToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-collapsed');
    });

    userMenuBtn?.addEventListener('click', () => {
        userMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.add('hidden');
        }
    });
    </script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const badge = document.getElementById('badge-thongbao');
        const apiUrl = "{{ route('sinhvien.thongbao.chua-doc') }}";

        // Gọi API ngay khi load trang
        fetch(apiUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin' // 🔹 giúp gửi cookie xác thực
            })
            .then(response => {
                if (!response.ok) throw new Error('API lỗi: ' + response.status);
                return response.json();
            })
            .then(data => {
                console.log('Kết quả API:', data);
                const count = data.unread_count ?? 0;

                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Không thể tải thông báo:', error);
            });
    });
    </script>

    <!-- Script tự động đánh dấu active menu -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.href;
        const menuItems = document.querySelectorAll('.nav-item');

        menuItems.forEach(item => {
            const route = item.getAttribute('data-route');
            if (route && currentUrl.startsWith(route)) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnThongBao = document.getElementById('btn-thongbao');
        if (btnThongBao) {
            btnThongBao.addEventListener('click', function() {
                window.location.href = "{{ route('sinhvien.thongbao.danhsach') }}";
            });
        }
    });
    </script>



    @yield('scripts')





</body>

</html>