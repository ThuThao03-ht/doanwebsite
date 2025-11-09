@extends('layouts.dngv')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-theme text-white">
            <h4 class="mb-0 fw-bold"><i class="fas fa-key me-2"></i>Đổi mật khẩu</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('giangvien.hoso.doiMatKhau') }}" method="POST">
                @csrf

                {{-- Mật khẩu cũ --}}
                <div class="mb-3 position-relative">
                    <label for="mat_khau_cu" class="form-label">
                        Mật khẩu cũ <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control border-theme" id="mat_khau_cu" name="mat_khau_cu"
                        required>
                    <span class="toggle-password"
                        style="position:absolute; right:10px; top:38px; cursor:pointer; color:#4a7fa7;">
                        <i class="fas fa-eye"></i>
                    </span>
                    @error('mat_khau_cu')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Mật khẩu mới --}}
                <div class="mb-3 position-relative">
                    <label for="mat_khau_moi" class="form-label">
                        Mật khẩu mới <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control border-theme" id="mat_khau_moi" name="mat_khau_moi"
                        required>
                    <span class="toggle-password"
                        style="position:absolute; right:10px; top:38px; cursor:pointer; color:#4a7fa7;">
                        <i class="fas fa-eye"></i>
                    </span>
                    @error('mat_khau_moi')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Xác nhận mật khẩu mới --}}
                <div class="mb-3 position-relative">
                    <label for="mat_khau_moi_confirmation" class="form-label">
                        Xác nhận mật khẩu mới <span class="text-danger">*</span>
                    </label>
                    <input type="password" class="form-control border-theme" id="mat_khau_moi_confirmation"
                        name="mat_khau_moi_confirmation" required>
                    <span class="toggle-password"
                        style="position:absolute; right:10px; top:38px; cursor:pointer; color:#4a7fa7;">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('giangvien.hoso') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Hủy
                    </a>
                    <button type="submit" class="btn text-white" style="background-color:#4a7fa7;">
                        <i class="fas fa-save me-1"></i>Đổi mật khẩu
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
            title: 'Thành công',
            text: success
        });
    }
    if (error) {
        Swal.fire({
            icon: 'error',
            title: 'Thất bại',
            text: error
        });
    }

    // Toggle hiển thị/ẩn mật khẩu
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    });
});
</script>

<style>
.border-theme {
    border: 1px solid #4a7fa7;
    border-radius: 6px;
    padding-right: 2.5rem;
}

.bg-theme {
    background-color: #4a7fa7 !important;
}

.btn:hover {
    background-color: #356387 !important;
}

.text-danger {
    font-weight: bold;
}
</style>
@endsection