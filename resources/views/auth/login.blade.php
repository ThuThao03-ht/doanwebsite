<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>

    <meta name="success-message" content="{{ session('success') }}">
    <meta name="error-message" content="{{ session('error') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background-color: #f7f7f7;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Segoe UI', sans-serif;
    }

    /* Header */
    header {
        background-color: #4a7fa7;
        color: #fff;
        padding: 15px 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 20px;
        font-weight: bold;
        color: #fff;
    }

    .logo i {
        font-size: 50px;
        color: #ffffff;
    }

    /* Login Card */
    .login-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 400px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
        padding: 40px;
        animation: fadeIn 0.4s ease-in;
    }

    .login-card h3 {
        color: #4a7fa7;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
    }

    .btn-login {
        background-color: #4a7fa7;
        color: #fff;
        border-radius: 8px;
        transition: 0.3s;
    }

    .btn-login:hover {
        background-color: #3c6a8a;
    }

    .form-control:focus {
        border-color: #4a7fa7;
        box-shadow: 0 0 5px rgba(74, 127, 167, 0.3);
    }

    .forgot-password {
        display: block;
        text-align: right;
        font-size: 0.9rem;
        margin-top: 8px;
        color: #4a7fa7;
        text-decoration: none;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    /* Footer */
    footer {
        background-color: #4a7fa7;
        color: #fff;
        text-align: center;
        padding: 15px 0;
        font-size: 0.95rem;
    }

    footer a {
        color: #ffd966;
        text-decoration: none;
    }

    /* footer a:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    } */

    .form-label .text-danger {
        font-weight: bold;
        margin-left: 4px;
    }

    .logo-text small {
        font-size: 14px;
        font-weight: normal;
        color: #e0e0e0;
    }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center" style="gap: 2px;">

                <!-- Logo -->
                <img src="{{ asset('images/logo1.png') }}" alt="Logo Trường Đại học Đồng Tháp"
                    style="height: 80px; width: auto; cursor: pointer; margin-right: -6px;">

                <!-- Chữ -->
                <div class="logo-text" style="line-height: 1.1;">
                    <span style="font-size: 22px; font-weight: 700; color:#f44336;">
                        TRƯỜNG ĐẠI HỌC ĐỒNG THÁP
                    </span><br>
                    <small style="font-size: 18px; font-weight: 600; color:#122c4f;">
                        Hệ thống Quản lý Thực tập Doanh nghiệp
                    </small>
                </div>

            </div>

        </div>

    </header>


    <!-- LOGIN -->
    <div class="login-container">
        <div class="login-card">
            <h3>Đăng nhập hệ thống</h3>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">
                        Tên đăng nhập <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">
                        Mật khẩu <span class="text-danger">*</span>
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>


                <a href="#" class="forgot-password" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                    Quên mật khẩu?
                </a>

                <br>
                <!-- CAPTCHA -->
                <div class="mb-3 text-center">
                    {!! NoCaptcha::display() !!}
                </div>
                {!! NoCaptcha::renderJs('vi') !!}

                <!-- Nút đăng nhập -->
                <button type="submit" class="btn btn-login w-100 py-2 mt-3">Đăng nhập</button>
            </form>
        </div>
    </div>

    <!--  MODAL QUÊN MẬT KHẨU -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header" style="background-color:#4a7fa7; color:white;">
                    <h5 class="modal-title fw-bold" id="forgotPasswordModalLabel">
                        <i class="bi bi-envelope-at me-2"></i>Quên mật khẩu
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('forgot.password') }}">
                    @csrf
                    <div class="modal-body">
                        <p class="text-secondary mb-3">Nhập email bạn đã dùng để đăng ký thông tin của bạn. Hệ thống sẽ
                            gửi
                            mật
                            khẩu mới đến email của bạn.</p>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3"
                                placeholder="example@gmail.com" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Đóng
                        </button>
                        <button type="submit" class="btn" style="background-color:#4a7fa7; color:white;">
                            <i class="bi bi-send-check me-1"></i>Gửi mật khẩu mới
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--  FOOTER -->
    <footer>
        <footer>
            <div>
                © {{ date('Y') }} Hệ thống Quản lý Thực tập Doanh nghiệp - Trường Đại học Đồng Tháp
            </div>
            <div>
                783 Phạm Hữu Lầu, Phường Cao Lãnh, Đồng Tháp
            </div>
            <div>
                Email: <a href="dhdt@dthu.edu.vn" style="color: #ffe082;">dhdt@dthu.edu.vn</a>
                &nbsp;|&nbsp; Hotline: <a href="tel:(0277) 3881518" style="color: #ffe082;">(0277) 3881518
                </a>
            </div>
        </footer>

    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const success = document.querySelector('meta[name="success-message"]').getAttribute('content');
        const error = document.querySelector('meta[name="error-message"]').getAttribute('content');

        if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: success,
                timer: 2000,
                showConfirmButton: false
            });
        }
        if (error) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: error,
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
    </script>
</body>

</html>