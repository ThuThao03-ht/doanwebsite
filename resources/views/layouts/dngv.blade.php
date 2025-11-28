<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Quản lý sinh viên')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    /* Giữ nguyên style từ layouts/app */
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
            <a href="{{ route('giangvien.dashboard') }}" class="block no-underline text-inherit">
                <div class="p-4 border-b cursor-pointer hover:bg-gray-100 transition">
                    <div class="flex items-center gap-1">
                        <!-- Logo -->
                        <img src="{{ asset('images/logo1.png') }}" alt="Logo Trường Đại học Đồng Tháp"
                            class="h-12 w-auto cursor-pointer select-none">

                        <!-- Văn bản -->
                        <div class="logo-text leading-tight">
                            <h1 class="text-base font-bold" style="color:#f44336; font-weight: bold;">
                                TRƯỜNG ĐẠI HỌC ĐỒNG THÁP
                            </h1>
                            <p class="text-sm font-semibold" style=" color: #122c4f; font-weight: bold;">
                                Hệ thống Quản lý Thực tập Doanh nghiệp
                            </p>
                        </div>
                    </div>
                </div>
            </a>

            <nav class="p-3 flex-1 overflow-y-auto">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('giangvien.dashboard') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('giangvien.dashboard') }}">
                            <i class="fas fa-home w-5 text-lg"></i>
                            <span class="nav-text">Tổng quan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('giangvien.qldangkythuctap') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('giangvien.qldangkythuctap') }}">
                            <i class="fas fa-file-signature w-5 text-lg"></i>
                            <span class="nav-text">Đăng ký sinh viên</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('giangvien.qltiendothuctap') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('giangvien.qltiendothuctap') }}">
                            <i class="fas fa-tasks w-5 text-lg"></i>
                            <span class="nav-text">Tiến độ sinh viên</span>
                        </a>
                    </li>

                    <!-- <li>
                        <a href="#" class="nav-item flex items-center gap-3 p-3 rounded-lg" data-route="#">
                            <i class="fas fa-file-alt w-5 text-lg"></i>
                            <span class="nav-text">Báo cáo sinh viên</span>
                        </a>
                    </li> -->

                    <li>
                        <a href="{{ route('giangvien.qldanhgiathuctap') }}"
                            class="nav-item flex items-center gap-3 p-3 rounded-lg"
                            data-route="{{ route('giangvien.qldanhgiathuctap') }}">
                            <i class="fas fa-star w-5 text-lg"></i>
                            <span class="nav-text">Đánh giá sinh viên</span>
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
                        @php
                        use Illuminate\Support\Facades\DB;
                        use Illuminate\Support\Facades\Auth;

                        $user = Auth::user();
                        $role = $user->role->role_name ?? null;

                        // Mặc định hiển thị username
                        $displayName = $user->username;
                        $extraInfo = '';

                        if ($role === 'GiangVien') {
                        $gv = DB::table('giangvien')->where('user_id', $user->user_id)->first();
                        if ($gv) {
                        $displayName = $gv->ho_ten;
                        $extraInfo = $gv->bo_mon;
                        }
                        } elseif ($role === 'DoanhNghiep') {
                        $dn = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();
                        if ($dn) {
                        $displayName = $dn->ten_dn;
                        $extraInfo = $dn->lien_he;
                        }
                        }
                        @endphp

                        <div class="font-semibold text-gray-800">
                            {{ $displayName }}
                        </div>

                        <div class="text-xs text-gray-500">{{ $extraInfo }}</div>
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
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('giangvien.thongbao') }}"
                        class="relative p-2 rounded-lg hover:bg-gray-100 transition" title="Thông báo">
                        <i class="fas fa-bell text-xl"></i>
                        @php
                        $countNew = DB::table('thongbao_user')
                        ->where('user_id', Auth::id())
                        ->where('da_doc', 0)
                        ->count();
                        @endphp
                        @if($countNew > 0)
                        <span
                            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                            {{ $countNew }}
                        </span>
                        @endif
                    </a>


                    <div class="relative">
                        <button id="user-menu-btn"
                            class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-9 h-9 rounded-full overflow-hidden border border-gray-200 shadow-sm">
                                <img src="{{ Auth::user()->avatar ? asset('storage/upload/avatar/' . Auth::user()->avatar) : asset('images/default.png') }}"
                                    alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            @php
                            $user = Auth::user();
                            $role = $user->role->role_name ?? null;

                            $displayName = $user->username; // mặc định username

                            if ($role == 'GiangVien') {
                            $gv = DB::table('giangvien')->where('user_id', $user->user_id)->first();
                            if ($gv) {
                            $displayName = $gv->ho_ten;
                            }
                            } elseif ($role == 'DoanhNghiep') {
                            $dn = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();
                            if ($dn) {
                            $displayName = $dn->ten_dn;
                            }
                            }
                            @endphp

                            <div class="font-semibold text-gray-800">
                                {{ $displayName }}
                            </div>

                        </button>
                        <div id="user-menu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden z-50">
                            <a href="{{ route('giangvien.hoso') }}"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-user text-gray-400 text-lg"></i>
                                <span class="font-medium">Hồ sơ</span>
                            </a>
                            <a href="{{ route('giangvien.hoso.doimatkhau') }}"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-lock text-gray-400 text-lg"></i>
                                <span class="font-medium">Đổi mật khẩu</span>
                            </a>
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

    <!-- Các script giống layouts/app -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });

    document.addEventListener('DOMContentLoaded', function() {
        const badge = document.getElementById('badge-thongbao');
        const apiUrl = "#";

        fetch(apiUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) throw new Error('API lỗi: ' + response.status);
                return response.json();
            })
            .then(data => {
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

    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.nav-item');
        const currentUrl = window.location.href;
        menuItems.forEach(item => {
            const route = item.getAttribute('data-route');
            if (route && currentUrl.startsWith(route)) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const btnThongBao = document.getElementById('btn-thongbao');
        if (btnThongBao) {
            btnThongBao.addEventListener('click', function() {
                window.location.href = "#";
            });
        }
    });
    </script>


    @yield('scripts')
</body>

</html>