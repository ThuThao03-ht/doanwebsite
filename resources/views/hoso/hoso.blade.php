@extends('layouts.admin')

@section('content')

<!-- Icon gradient đẹp -->
<style>
/* Icon thông tin */
.info-icon {
    background: linear-gradient(135deg, #4a7fa7, #6c5ce7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 1.15rem;
}

/* Icon tiêu đề */
.title-icon {
    background: linear-gradient(135deg, #4a7fa7, #3a6a92);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>

<meta name="success-message" content="{{ session('success') }}">
<meta name="error-message" content="{{ session('error') }}">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0" style="color:#4a7fa7; font-size: 28px;">
            <i class="bi bi-person-circle me-2 title-icon"></i> Hồ sơ cá nhân
        </h3>
    </div>

    <div class="card shadow border-0 rounded-5 overflow-hidden"
        style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);">
        <div class="row g-0">

            <!-- Avatar -->
            <div class="col-md-4 d-flex flex-column align-items-center py-5 position-relative"
                style="background: linear-gradient(135deg, #e8f1f8 0%, #f0f6fb 100%);">

                <div class="position-relative">
                    <div
                        style="position: relative; width: 140px; height: 140px; display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset($user->avatar ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png') }}"
                            class="rounded-circle shadow" width="140" height="140" id="avatarPreview"
                            style="object-fit: cover; border: 5px solid #ffffff; box-shadow: 0 8px 20px rgba(74, 127, 167, 0.2);">

                        <form method="POST" action="{{ route('admin.hoso.updateAvatar') }}"
                            enctype="multipart/form-data" id="avatarForm" class="position-absolute"
                            style="bottom: -5px; right: -5px;">
                            @csrf
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none"
                                onchange="document.getElementById('avatarForm').submit();">
                            <label for="avatarInput"
                                style="cursor:pointer; display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; background-color: #4a7fa7; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 12px rgba(74, 127, 167, 0.3);">
                                <i class="bi bi-camera-fill text-white" style="font-size: 18px;"></i>
                            </label>
                        </form>
                    </div>
                </div>

                <h5 class="mt-4 mb-2 fw-bold text-dark" style="font-size: 20px;">{{ $user->username }}</h5>

                <span class="text-muted mb-4 d-flex align-items-center" style="font-size: 14px;">
                    <i class="bi bi-person-badge me-2"></i>{{ $roleName }}
                </span>

                <button class="btn text-white fw-semibold px-4 py-2 rounded-3"
                    style="background: linear-gradient(135deg, #4a7fa7 0%, #3a6a92 100%); border: none; box-shadow: 0 4px 12px rgba(74, 127, 167, 0.3); transition: all 0.3s ease;"
                    onmouseover="this.style.boxShadow='0 6px 16px rgba(74, 127, 167, 0.4)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.boxShadow='0 4px 12px rgba(74, 127, 167, 0.3)'; this.style.transform='translateY(0)';"
                    data-bs-toggle="modal" data-bs-target="#modalCapNhat">
                    <i class="bi bi-pencil-square me-2 info-icon"></i> Cập nhật hồ sơ
                </button>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="col-md-8 p-5">

                <h5 class="fw-bold mb-4 pb-3"
                    style="color:#4a7fa7; font-size: 18px; border-bottom: 2px solid #e0e7ff; padding-bottom: 12px;">
                    <i class="bi bi-info-circle me-2 title-icon"></i> Thông tin chi tiết
                </h5>

                <table class="table table-borderless align-middle">

                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <th class="text-secondary fw-normal py-3" width="35%">
                            <i class="bi bi-person me-2 info-icon"></i>Tên đăng nhập
                        </th>
                        <td class="fw-semibold py-3" style="color: #2c3e50;">{{ $user->username }}</td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <th class="text-secondary fw-normal py-3">
                            <i class="bi bi-envelope me-2 info-icon"></i>Email
                        </th>
                        <td class="py-3" style="color: #555;">{{ $thongtin->email ?? 'Chưa có' }}</td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <th class="text-secondary fw-normal py-3">
                            <i class="bi bi-telephone me-2 info-icon"></i>Số điện thoại
                        </th>
                        <td class="py-3" style="color: #555;">
                            {{ $thongtin->sdt ?? $thongtin->lien_he ?? 'Chưa có' }}
                        </td>
                    </tr>

                    @if ($roleName === 'SinhVien')
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <th class="text-secondary fw-normal py-3">
                            <i class="bi bi-mortarboard me-2 info-icon"></i>Lớp
                        </th>
                        <td class="py-3" style="color: #555;">{{ $thongtin->lop ?? '---' }}</td>
                    </tr>

                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <th class="text-secondary fw-normal py-3">
                            <i class="bi bi-book me-2 info-icon"></i>Ngành
                        </th>
                        <td class="py-3" style="color: #555;">{{ $thongtin->nganh ?? '---' }}</td>
                    </tr>

                    @elseif ($roleName === 'GiangVien')
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <th class="text-secondary fw-normal py-3">
                            <i class="bi bi-journal-bookmark me-2 info-icon"></i>Bộ môn
                        </th>
                        <td class="py-3" style="color: #555;">{{ $thongtin->bo_mon ?? '---' }}</td>
                    </tr>

                    @elseif ($roleName === 'DoanhNghiep')
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <th class="text-secondary fw-normal py-3">
                            <i class="bi bi-building me-2 info-icon"></i>Tên doanh nghiệp
                        </th>
                        <td class="py-3" style="color: #555;">{{ $thongtin->ten_dn ?? '---' }}</td>
                    </tr>

                    <tr>
                        <th class="text-secondary fw-normal py-3">
                            <i class="bi bi-geo-alt me-2 info-icon"></i>Địa chỉ
                        </th>
                        <td class="py-3" style="color: #555;">{{ $thongtin->dia_chi ?? '---' }}</td>
                    </tr>
                    @endif

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật -->
<div class="modal fade" id="modalCapNhat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.hoso.update') }}"
            class="modal-content rounded-4 border-0 shadow-lg">
            @csrf

            <div class="modal-header text-white rounded-top-4"
                style="background: linear-gradient(135deg, #4a7fa7 0%, #3a6a92 100%); border: none;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2 title-icon"></i> Cập nhật hồ sơ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <div class="mb-4">
                    <label class="form-label fw-semibold" style="color: #4a7fa7;">Tên đăng nhập</label>
                    <input type="text" name="username" value="{{ $user->username }}" class="form-control rounded-3 py-2"
                        style="border: 1px solid #e0e7ff; background-color: #f8fafc;" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold" style="color: #4a7fa7;">Email</label>
                    <input type="email" name="email" value="{{ $thongtin->email ?? '' }}"
                        class="form-control rounded-3 py-2"
                        style="border: 1px solid #e0e7ff; background-color: #f8fafc;" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #4a7fa7;">Số điện thoại</label>
                    <input type="text" name="sdt" value="{{ $thongtin->sdt ?? $thongtin->lien_he ?? '' }}"
                        class="form-control rounded-3 py-2"
                        style="border: 1px solid #e0e7ff; background-color: #f8fafc;">
                </div>

            </div>

            <div class="modal-footer" style="background-color: #f8fafc; border-top: 1px solid #e0e7ff;">
                <button type="button" class="btn btn-light border rounded-3 fw-semibold"
                    style="color: #6c757d; border-color: #d0d8e0;" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1 info-icon"></i> Đóng
                </button>
                <button type="submit" class="btn text-white fw-semibold rounded-3"
                    style="background: linear-gradient(135deg, #4a7fa7 0%, #3a6a92 100%); border: none;">
                    <i class="bi bi-check2-circle me-1 info-icon"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

@endsection