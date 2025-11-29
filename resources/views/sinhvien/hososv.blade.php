@extends('layouts.app')

@section('content')


<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0" style="color:#4a7fa7;">
            <i class="bi bi-person-circle me-2"></i> Hồ sơ cá nhân
        </h3>
    </div>

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            <!-- Cột trái: Avatar -->
            <div class="col-md-4 bg-light d-flex flex-column align-items-center py-4 position-relative">
                <div class="position-relative">
                    <img src="{{ $user->avatar 
    ? asset('storage/upload/avatar/' . $user->avatar) 
    : 'https://i.pinimg.com/736x/2f/c9/5a/2fc95af4700a971da5024ac5f260bcb8.jpg' }}" class="rounded-circle shadow-sm"
                        width="130" height="130" id="avatarPreview"
                        style="object-fit: cover; border: 4px solid #ffffff;">


                    <form method="POST" action="{{ route('sinhvien.hoso.updateAvatar') }}" enctype="multipart/form-data"
                        id="avatarForm" class="position-absolute" style="bottom: 0; right: 9px;">
                        @csrf
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none"
                            onchange="document.getElementById('avatarForm').submit();">
                        <label for="avatarInput" style="cursor:pointer;">
                            <i class="bi bi-camera-fill text-secondary fs-5"></i>
                        </label>
                    </form>
                </div>

                <h5 class="mt-3 mb-0 fw-semibold text-dark">{{ $user->username }}</h5>
                <span class="text-muted mb-3"><i class="bi bi-person-badge me-1"></i>{{ $roleName }}</span>

                <button class="btn btn-sm px-3 text-white" style="background-color:#4a7fa7;" data-bs-toggle="modal"
                    data-bs-target="#modalCapNhat">
                    <i class="bi bi-pencil-square me-1"></i> Cập nhật hồ sơ
                </button>
            </div>

            <!-- Cột phải: Thông tin chi tiết -->
            <div class="col-md-8 p-4">
                <h5 class="fw-bold mb-3" style="color:#4a7fa7;">
                    <i class="bi bi-info-circle me-1"></i> Thông tin chi tiết
                </h5>
                <table class="table table-borderless align-middle">
                    <tr>
                        <th class="text-secondary fw-normal" width="35%">
                            <i class="bi bi-person me-2 text-primary"></i>Tên đăng nhập
                        </th>
                        <td class="fw-semibold">{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal">
                            <i class="bi bi-envelope me-2 text-danger"></i>Email
                        </th>
                        <td>{{ $thongtin->email ?? 'Chưa có' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal">
                            <i class="bi bi-telephone me-2 text-success"></i>Số điện thoại
                        </th>
                        <td>{{ $thongtin->sdt ?? $thongtin->lien_he ?? 'Chưa có' }}</td>
                    </tr>

                    @if ($roleName === 'SinhVien')
                    <tr>
                        <th class="text-secondary fw-normal">
                            <i class="bi bi-mortarboard me-2 text-info"></i>Lớp
                        </th>
                        <td>{{ $thongtin->lop ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal">
                            <i class="bi bi-book me-2 text-warning"></i>Ngành
                        </th>
                        <td>{{ $thongtin->nganh ?? '---' }}</td>
                    </tr>
                    @elseif ($roleName === 'GiangVien')
                    <tr>
                        <th class="text-secondary fw-normal">
                            <i class="bi bi-journal-bookmark me-2 text-warning"></i>Bộ môn
                        </th>
                        <td>{{ $thongtin->bo_mon ?? '---' }}</td>
                    </tr>
                    @elseif ($roleName === 'DoanhNghiep')
                    <tr>
                        <th class="text-secondary fw-normal">
                            <i class="bi bi-building me-2 text-info"></i>Tên doanh nghiệp
                        </th>
                        <td>{{ $thongtin->ten_dn ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal">
                            <i class="bi bi-geo-alt me-2 text-danger"></i>Địa chỉ
                        </th>
                        <td>{{ $thongtin->dia_chi ?? '---' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật thông tin -->
<div class="modal fade" id="modalCapNhat" tabindex="-1" aria-labelledby="modalCapNhatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('sinhvien.hoso.update') }}"
            class="modal-content rounded-4 border-0 shadow">
            @csrf
            <div class="modal-header text-white rounded-top-4" style="background-color:#4a7fa7;">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Cập nhật hồ sơ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên đăng nhập</label>
                    <input type="text" name="username" value="{{ $user->username }}" class="form-control rounded-3"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ $thongtin->email ?? '' }}" class="form-control rounded-3"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Số điện thoại</label>
                    <input type="text" name="sdt" value="{{ $thongtin->sdt ?? $thongtin->lien_he ?? '' }}"
                        class="form-control rounded-3">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Đóng
                </button>
                <button type="submit" class="btn text-white" style="background-color:#4a7fa7;">
                    <i class="bi bi-check2-circle me-1"></i> Lưu thay đổi
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