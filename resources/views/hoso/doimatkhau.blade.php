@extends('layouts.admin')

@section('content')
<div class="container mt-5" style="max-width: 480px;">
    <div class="card modern-card">
        <div class="card-header-modern">
            <div class="header-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h4 class="header-title">Đổi mật khẩu</h4>
            <p class="header-subtitle">Cập nhật mật khẩu để bảo mật tài khoản</p>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('admin.hoso.doiMatKhau') }}" method="POST">
                @csrf

                {{-- Mật khẩu cũ --}}
                <div class="mb-4">
                    <label for="mat_khau_cu" class="modern-label">
                        Mật khẩu hiện tại <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-control modern-input" id="mat_khau_cu" name="mat_khau_cu"
                            placeholder="Nhập mật khẩu hiện tại" required>
                        <span class="toggle-password" data-target="mat_khau_cu">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('mat_khau_cu')
                    <small class="error-message"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                {{-- Mật khẩu mới --}}
                <div class="mb-4">
                    <label for="mat_khau_moi" class="modern-label">
                        Mật khẩu mới <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-key input-icon"></i>
                        <input type="password" class="form-control modern-input" id="mat_khau_moi" name="mat_khau_moi"
                            placeholder="Nhập mật khẩu mới" required>
                        <span class="toggle-password" data-target="mat_khau_moi">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('mat_khau_moi')
                    <small class="error-message"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                    @enderror
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle me-1"></i>Tối thiểu 6 ký tự
                    </small>
                </div>

                {{-- Xác nhận mật khẩu --}}
                <div class="mb-4">
                    <label for="mat_khau_moi_confirmation" class="modern-label">
                        Xác nhận mật khẩu mới <span class="required-mark">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-check-circle input-icon"></i>
                        <input type="password" class="form-control modern-input" id="mat_khau_moi_confirmation"
                            name="mat_khau_moi_confirmation" placeholder="Nhập lại mật khẩu mới" required>
                        <span class="toggle-password" data-target="mat_khau_moi_confirmation">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('admin.hoso.index') }}" class="btn btn-cancel">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-check me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert & Toggle Password -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert with modern design
    const success = "{{ session('success') }}";
    const error = "{{ session('error') }}";

    if (success) {
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: success,
            confirmButtonColor: '#4a7fa7',
            confirmButtonText: 'Đồng ý'
        });
    }
    if (error) {
        Swal.fire({
            icon: 'error',
            title: 'Có lỗi xảy ra',
            text: error,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Đóng'
        });
    }

    // Toggle show/hide password
    const toggles = document.querySelectorAll('.toggle-password');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Add input focus effects
    const inputs = document.querySelectorAll('.modern-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('input-focused');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('input-focused');
        });
    });
});
</script>

<style>
:root {
    --primary-blue: #4a7fa7;
    --primary-blue-dark: #3a6b8f;
    --primary-blue-light: #5a92bc;
    --text-dark: #1e293b;
    --text-gray: #64748b;
    --border-color: #e2e8f0;
    --bg-light: #f8fafc;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Modern Card */
.modern-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    background: white;
}

/* Card Header Modern */
.card-header-modern {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
    padding: 2rem;
    text-align: center;
    border: none;
}

.header-icon {
    width: 64px;
    height: 64px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    backdrop-filter: blur(10px);
}

.header-icon i {
    font-size: 28px;
    color: white;
}

.header-title {
    color: white;
    font-weight: 700;
    font-size: 24px;
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.5px;
}

.header-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
    margin: 0;
    font-weight: 400;
}

/* Modern Label */
.modern-label {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
    margin-bottom: 0.5rem;
    display: block;
}

.required-mark {
    color: #ef4444;
    font-weight: 700;
}

/* Input Wrapper */
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 16px;
    color: var(--text-gray);
    font-size: 16px;
    z-index: 1;
    transition: color 0.3s ease;
}

.input-wrapper.input-focused .input-icon {
    color: var(--primary-blue);
}

/* Modern Input */
.modern-input {
    padding: 14px 48px 14px 48px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: white;
    color: var(--text-dark);
}

.modern-input:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 4px rgba(74, 127, 167, 0.1);
    outline: none;
    background: white;
}

.modern-input::placeholder {
    color: #cbd5e1;
}

/* Toggle Password */
.toggle-password {
    position: absolute;
    right: 16px;
    cursor: pointer;
    color: var(--text-gray);
    transition: color 0.3s ease;
    z-index: 1;
    padding: 4px;
}

.toggle-password:hover {
    color: var(--primary-blue);
}

/* Error Message */
.error-message {
    color: #ef4444;
    font-size: 13px;
    display: block;
    margin-top: 0.5rem;
    font-weight: 500;
}

/* Button Group */
.button-group {
    display: flex;
    gap: 12px;
    margin-top: 2rem;
}

/* Modern Buttons */
.btn-cancel {
    flex: 1;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    border: 2px solid var(--border-color);
    background: white;
    color: var(--text-gray);
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-cancel:hover {
    background: var(--bg-light);
    border-color: var(--text-gray);
    color: var(--text-dark);
    transform: translateY(-1px);
}

.btn-primary-modern {
    flex: 1;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary-modern:hover {
    background: linear-gradient(135deg, var(--primary-blue-dark) 0%, var(--primary-blue) 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(74, 127, 167, 0.3);
}

/* Text Muted Enhancement */
.text-muted {
    color: var(--text-gray);
    font-size: 13px;
}

/* Responsive */
@media (max-width: 576px) {
    .container {
        padding: 1rem;
    }

    .card-header-modern {
        padding: 1.5rem;
    }

    .header-icon {
        width: 56px;
        height: 56px;
    }

    .header-icon i {
        font-size: 24px;
    }

    .header-title {
        font-size: 20px;
    }

    .button-group {
        flex-direction: column;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: fadeInUp 0.5s ease;
}
</style>
@endsection