@extends('layouts.dngv')



@section('content')
<div class="container mt-4" style="max-width: 420px;">
    <div class="card modern-card">
        <div class="card-header-modern">
            <div class="header-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h4 class="header-title">Đổi mật khẩu</h4>
            <p class="header-subtitle">Cập nhật mật khẩu để bảo mật tài khoản</p>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('giangvien.hoso.doiMatKhau') }}" method="POST">
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
                        <i class="fas fa-info-circle me-1"></i>Tối thiểu 8 ký tự
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

    // Input focus effect
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
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Card */
.modern-card {
    border: none;
    border-radius: 14px;
    overflow: hidden;
    background: white;
    box-shadow: var(--shadow-md);
}

/* Header */
.card-header-modern {
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-light));
    padding: 1.5rem 1rem;
    text-align: center;
}

.header-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.8rem;
}

.header-icon i {
    font-size: 22px;
    color: white;
}

.header-title {
    font-size: 20px;
    font-weight: 700;
    color: white;
    margin-bottom: 0.3rem;
}

.header-subtitle {
    font-size: 13px;
    color: #f0f9ff;
}

/* Labels */
.modern-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
}

/* Input wrapper */
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 14px;
    font-size: 14px;
    color: var(--text-gray);
}

.input-wrapper.input-focused .input-icon {
    color: var(--primary-blue);
}

/* Input */
.modern-input {
    padding: 12px 42px;
    border-radius: 10px;
    border: 2px solid var(--border-color);
    font-size: 14px;
    background: white;
    transition: all 0.3s ease;
}

.modern-input:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(74, 127, 167, 0.15);
}

/* Toggle Password */
.toggle-password {
    position: absolute;
    right: 14px;
    font-size: 14px;
    cursor: pointer;
    color: var(--text-gray);
}

/* Error */
.error-message {
    color: #ef4444;
    font-size: 12px;
    margin-top: 0.4rem;
}

/* Buttons */
.button-group {
    display: flex;
    gap: 10px;
    margin-top: 1.5rem;
}

.btn-cancel,
.btn-primary-modern {
    padding: 12px;
    font-size: 14px;
    border-radius: 10px;
    font-weight: 600;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Cancel button */
.btn-cancel {
    background: white;
    border: 2px solid var(--border-color);
    color: var(--text-gray);
}

.btn-cancel:hover {
    background: var(--bg-light);
    color: var(--text-dark);
}

/* Primary button */
.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-light));
    color: white;
    border: none;
    margin-left: 120px;
}

.btn-primary-modern:hover {
    background: linear-gradient(135deg, var(--primary-blue-dark), var(--primary-blue));
}

/* Text helper */
.text-muted {
    font-size: 12px;
}
</style>
@endsection