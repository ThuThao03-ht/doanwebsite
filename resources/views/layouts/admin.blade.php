<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý Thực tập Doanh nghiệp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- jQuery (nếu dùng AJAX như trong ví dụ trước) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f6fa;
        color: #2c3e50;
    }

    /* Header */
    .header {
        background: linear-gradient(135deg, #4A7FA7 0%, #6AA3CA 100%);
        color: white;
        padding: 0 30px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .menu-toggle {
        font-size: 24px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .menu-toggle:hover {
        transform: scale(1.1);
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 20px;
        font-weight: bold;
    }

    .logo i {
        font-size: 28px;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 25px;
        position: relative;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 8px 15px;
        border-radius: 25px;
        transition: background 0.3s;
    }

    .user-info:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4A7FA7;
        font-weight: bold;
        border: 2px solid white;
    }

    /* Dropdown menu */
    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 70px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        z-index: 2000;
    }

    .dropdown-menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        color: #2c3e50;
        text-decoration: none;
        transition: background 0.3s;
        font-size: 14px;
    }

    .dropdown-menu a:hover {
        background: #f5f6fa;
        color: #4A7FA7;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        left: 0;
        top: 70px;
        width: 280px;
        height: calc(100vh - 70px);
        background: white;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        overflow-y: auto;
        transition: transform 0.3s ease;
        z-index: 999;
    }

    .sidebar.collapsed {
        transform: translateX(-280px);
    }

    .sidebar-menu {
        padding: 20px 0;
    }

    .menu-section {
        margin-bottom: 25px;
    }

    .menu-section-title {
        padding: 0 25px 10px;
        font-size: 11px;
        text-transform: uppercase;
        color: #95a5a6;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .menu-item {
        padding: 14px 25px;
        display: flex;
        align-items: center;
        gap: 15px;
        color: #2c3e50;
        text-decoration: none;
        transition: all 0.3s;
        position: relative;
    }

    .menu-item:hover {
        background: #f8f9fa;
        color: #4A7FA7;
    }

    .menu-item.active {
        background: linear-gradient(90deg, rgba(74, 127, 167, 0.1) 0%, transparent 100%);
        color: #4A7FA7;
        border-left: 4px solid #4A7FA7;
    }

    .menu-item i {
        font-size: 18px;
        width: 25px;
        text-align: center;
    }

    .menu-item .badge-menu {
        margin-left: auto;
        background: #e74c3c;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }

    /* Main Content */
    .main-content {
        margin-left: 280px;
        margin-top: 70px;
        padding: 30px;
        transition: margin-left 0.3s ease;
        min-height: calc(100vh - 70px);
    }

    .main-content.expanded {
        margin-left: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-280px);
        }

        .sidebar.mobile-open {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .header-right .user-name {
            display: none;
        }
    }

    .menu-item.active {
        background: linear-gradient(90deg, rgba(74, 127, 167, 0.1) 0%, transparent 100%);
        color: #4A7FA7;
        border-left: 4px solid #4A7FA7;
    }

    .sub-text {
        font-weight: 400;
        /* không in đậm */
        color: rgba(255, 255, 255, 0.7);
        /* trắng nhạt 70% */
        font-size: 0.85rem;
        /* nhỏ hơn một chút */
        line-height: 1.2;
        /* khoảng cách dòng hợp lý */
    }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">



            <div class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>

            </div>

            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo"
                    style="height: 60px; width: auto; cursor: pointer;">
                <div class="fw-bold text-center">
                    <span style="color: #f44336; font-weight: bold;">TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</span><br>
                    <span style="color: #122c4f; font-weight: bold;">Hệ thống Quản lý Thực tập Doanh nghiệp</span>
                </div>

            </div>


        </div>
        <div class="header-right">
            <div class="user-info" onclick="toggleDropdown()">
                @php
                $avatarPath = Auth::user()->avatar
                ? asset('storage/upload/avatar/' . Auth::user()->avatar)
                : asset('public/storage/images/default.png');
                @endphp

                <div class="avatar me-2">
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="avatar"
                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">

                </div>



                <!-- Username & Role -->
                <div class="user-name me-2">
                    <div style="font-weight: 600; font-size: 14px;">
                        {{ Auth::user()->username ?? 'Người dùng' }}
                    </div>
                    <div style="font-size: 12px; opacity: 0.7;">
                        {{ Auth::user()->role->role_name ?? 'Quyền không xác định' }}
                    </div>
                </div>


                <i class="fas fa-chevron-down"></i>
            </div>

            <div class="dropdown-menu" id="userDropdown">
                <a href="{{ route('admin.hoso.index') }}"><i class="fas fa-id-card"></i> Hồ sơ</a>

                <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Dashboard</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <i class="fas fa-home"></i><span>Tổng quan</span>
                </a>
            </div>


            <div class="menu-section">
                <div class="menu-section-title">Quản lý tài khoản</div>
                <a href="{{ route('admin.taikhoan.index') }}" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Người dùng</span>
                </a>

                <a href="{{ route('admin.sinhvien.index') }}" class="menu-item">
                    <i class="fas fa-user-graduate"></i>
                    <span>Sinh viên</span>
                </a>

                <a href="{{ route('admin.giangvien.index') }}" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Giảng viên</span>
                </a>

                <a href="{{ route('admin.doanhnghiep.index') }}" class="menu-item"><i
                        class="fas fa-building"></i><span>Doanh nghiệp</span></a>
                <a href="{{ route('admin.roles.index') }}" class="menu-item"><i class="fas fa-shield-alt"></i><span>Phân
                        quyền</span></a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Quản lý thực tập</div>
                <a href="{{ route('admin.vitrithuctap.index') }}" class="menu-item"><i
                        class="fas fa-briefcase"></i><span>Vị trí thực tập</span></a>
                <a href="{{ route('admin.dangkythuctap.index') }}" class="menu-item">
                    <i class="fas fa-file-signature"></i>
                    <span>Đăng ký thực tập</span>
                    <span class="badge-menu" id="badgeDangKy">0</span>
                </a>
                <a href="{{ route('admin.phanconggiangvien.index') }}" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Phân công giảng viên</span>
                </a>
                <a href="{{ route('admin.tiendo.index') }}" class="menu-item"><i class="fas fa-tasks"></i><span>Tiến độ
                        thực
                        tập</span></a>
                <a href="{{ route('admin.baocaothuctap.index') }}" class="menu-item"><i
                        class="fas fa-file-alt"></i><span>Báo
                        cáo thực tập</span></a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Đánh giá</div>
                <a href="{{ route('admin.giangviendanhgia.index') }}" class="menu-item"><i
                        class="fas fa-star"></i><span>Đánh giá giảng viên</span></a>
                <a href="{{ route('admin.doanhnghiepdanhgia.index') }}" class="menu-item"><i
                        class="fas fa-award"></i><span>Đánh giá doanh nghiệp</span></a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Hệ thống</div>
                <a href="{{ route('admin.thongbao.index') }}" class="menu-item"><i
                        class="fas fa-bullhorn"></i><span>Thông báo</span></a>


                <!-- Menu Cài đặt -->
                <div class="menuSetting-container">
                    <a class="menuSetting-btn d-flex align-items-center" href="#" id="menuSettingBtn">
                        <i class="fas fa-cog me-2"></i> <span>Cài đặt</span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>

                    <ul class="menuSetting-list" id="menuSettingList">
                        <li>
                            <a class="menuSetting-item" href="{{ route('admin.hoso.index') }}">
                                <i class="fas fa-user-circle me-2"></i> Hồ sơ
                            </a>
                        </li>

                        <li class="menuSetting-subParent">
                            <a class="menuSetting-item" href="#">Khôi phục dữ liệu ▸</a>
                            <ul class="menuSetting-subList">
                                <li><a class="menuSetting-item" href="{{ route('admin.khoiphuc.sinhvien') }}">Sinh
                                        viên</a></li>
                                <li><a class="menuSetting-item" href="{{ route('admin.khoiphuc.giangvien') }}">Giảng
                                        viên</a></li>
                                <li><a class="menuSetting-item" href="{{ route('admin.khoiphuc.doanhnghiep') }}">Doanh
                                        nghiệp</a></li>
                                <li><a class="menuSetting-item" href="{{ route('admin.khoiphuc.vitri') }}">Vị trí thực
                                        tập</a></li>
                                <li><a class="menuSetting-item" href="{{ route('admin.khoiphuc.dangky') }}">Đăng ký thực
                                        tập</a></li>
                            </ul>
                        </li>

                        <li>
                            <hr>
                        </li>

                        <li>
                            <a class="menuSetting-item" href="{{ route('admin.hoso.doimatkhau') }}">
                                <i class="fas fa-key me-2"></i> Đổi mật khẩu
                            </a>


                        </li>
                    </ul>
                </div>

                <!-- JS xử lý mở/đóng menu -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const menuBtn = document.getElementById('menuSettingBtn');
                    const menuList = document.getElementById('menuSettingList');

                    // Toggle menu chính
                    menuBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        menuList.classList.toggle('active');
                    });

                    // Toggle submenu
                    document.querySelectorAll('.menuSetting-subParent > a').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            this.nextElementSibling.classList.toggle('active');
                        });
                    });

                    // Đóng menu khi click ra ngoài
                    document.addEventListener('click', function(e) {
                        if (!menuBtn.contains(e.target) && !menuList.contains(e.target)) {
                            menuList.classList.remove('active');
                            document.querySelectorAll('.menuSetting-subList').forEach(sub => sub
                                .classList.remove('active'));
                        }
                    });
                });
                </script>


                <!-- CSS riêng -->
                <style>
                /* Ẩn mặc định */
                .menuSetting-list,
                .menuSetting-subList {
                    display: none;
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    background: #f8f9fa;
                    border-radius: 8px;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                }

                /* Khi hiển thị */
                .menuSetting-list.active,
                .menuSetting-subList.active {
                    display: block;
                }

                /* Nút chính */
                .menuSetting-btn {
                    display: block;
                    padding: 10px 15px;
                    color: #2c3e50;
                    text-decoration: none;
                    border-radius: 8px;
                    transition: background 0.2s;
                }

                .menuSetting-btn:hover {
                    background: #e9ecef;
                    color: #4A7FA7;
                }

                /* Item trong menu */
                .menuSetting-item {
                    display: block;
                    padding: 10px 20px;
                    color: #2c3e50;
                    text-decoration: none;
                    transition: background 0.2s;
                }

                .menuSetting-item:hover {
                    background: #e9ecef;
                    color: #4A7FA7;
                }

                /* Submenu */
                .menuSetting-subParent {
                    position: relative;
                }

                .menuSetting-subList {
                    position: relative;
                    margin-left: 20px;
                    border-left: 2px solid #dee2e6;
                    padding-left: 10px;
                }

                /* Giúp icon và text trong menu căn thẳng hàng nhau */
                .menu-item,
                .menuSetting-btn {
                    display: flex !important;
                    align-items: center;
                    gap: 10px;
                    /* khoảng cách giữa icon và text */
                    padding: 10px 15px;
                    color: #2c3e50;
                    text-decoration: none;
                    border-radius: 8px;
                    transition: background 0.2s;
                }

                /* Giữ style hover thống nhất */
                .menu-item:hover,
                .menuSetting-btn:hover {
                    background: #e9ecef;
                    color: #4A7FA7;
                }

                /* Đảm bảo icon có cùng kích thước và căn đều nhau */
                .menu-item i,
                .menuSetting-btn i {
                    width: 20px;
                    text-align: center;
                }
                </style>



            </div>

        </div>

        <div class="menu-section">
            <a href="{{ route('logout') }}" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Đăng
                    xuất</span></a>
        </div>
    </div>
    </div>


    <!-- Thêm Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <div class="main-content" id="mainContent">


        @yield('content')
    </div>


    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        if (window.innerWidth <= 768) sidebar.classList.toggle('mobile-open');
    }

    function toggleDropdown() {
        document.getElementById("userDropdown").classList.toggle("show");
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('userDropdown');
        const userInfo = document.querySelector('.user-info');
        if (!userInfo.contains(e.target)) dropdown.classList.remove('show');
    });
    </script>

    <style>
    .dropdown-menu.show {
        display: block;
    }
    </style>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("/api/dangkythuctap/count")
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById("badgeDangKy");
                badge.textContent = data.tong ?? 0;
            })
            .catch(err => console.error("Lỗi load số lượng:", err));
    });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.menu-item');
        const currentUrl = window.location.href;

        // Loại bỏ "active" khỏi tất cả menu
        menuItems.forEach(item => item.classList.remove('active'));

        // Đặt "active" theo URL hiện tại (nếu trùng khớp)
        menuItems.forEach(item => {
            if (item.href === currentUrl) {
                item.classList.add('active');
            }
        });

        // Lưu lại menu đã click vào localStorage
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                localStorage.setItem('activeMenu', this.href);
            });
        });

        // Khôi phục menu đã chọn sau khi reload
        const savedActive = localStorage.getItem('activeMenu');
        if (savedActive) {
            menuItems.forEach(item => {
                if (item.href === savedActive) {
                    item.classList.add('active');
                }
            });
        }

        // Nếu chưa có gì trong localStorage => đánh dấu "Tổng quan" mặc định
        if (!localStorage.getItem('activeMenu')) {
            const defaultMenu = document.querySelector('.menu-item[href*="dashboard"]');
            if (defaultMenu) defaultMenu.classList.add('active');
        }
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const allLinks = document.querySelectorAll('.menu-item, .menuSetting-item');
        const menuSettingList = document.getElementById('menuSettingList');

        // ===== 1. Giữ vị trí cuộn sidebar =====
        sidebar.addEventListener('scroll', () => {
            localStorage.setItem('sidebarScrollTop', sidebar.scrollTop);
        });
        const savedScroll = localStorage.getItem('sidebarScrollTop');
        if (savedScroll) sidebar.scrollTop = savedScroll;

        // ===== 2. Khi click vào link, lưu URL và loại menu =====
        allLinks.forEach(link => {
            link.addEventListener('click', function() {
                localStorage.setItem('activeMenu', this.href);
                if (menuSettingList && menuSettingList.contains(this)) {
                    localStorage.setItem('menuSettingOpen', 'true');
                } else {
                    localStorage.removeItem('menuSettingOpen');
                }
            });
        });

        // ===== 3. Khôi phục trạng thái active sau khi reload =====
        const currentUrl = window.location.href;
        const savedActive = localStorage.getItem('activeMenu');
        const savedMenuSettingOpen = localStorage.getItem('menuSettingOpen');

        // Xóa hết active trước
        allLinks.forEach(link => link.classList.remove('active'));

        // Kiểm tra URL hiện tại hoặc URL đã lưu
        const matchUrl = savedActive || currentUrl;

        allLinks.forEach(link => {
            if (link.href === matchUrl) {
                link.classList.add('active');
                // Nếu là mục con trong dropdown => mở menu Cài đặt
                if (menuSettingList && menuSettingList.contains(link)) {
                    menuSettingList.classList.add('active');
                    const parentSubList = link.closest('.menuSetting-subList');
                    if (parentSubList) parentSubList.classList.add('active');
                }
            }
        });

        // Nếu trước đó dropdown Cài đặt được mở => mở lại
        if (savedMenuSettingOpen === 'true' && menuSettingList) {
            menuSettingList.classList.add('active');
        }
    });
    </script>



    @yield('scripts')

</body>

</html>