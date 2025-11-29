@extends('layouts.dngv')

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="fw-bold mb-0" style="color:#4a7fa7;">
            <i class="bi bi-person-circle me-2"></i> Hồ sơ giảng viên
        </h2>
    </div>

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            <!-- Cột trái -->
            <div class="col-md-4 bg-light d-flex flex-column align-items-center py-4 position-relative">
                <div class="position-relative">
                    <img src="{{ $user->avatar 
                        ? asset('storage/upload/avatar/' . $user->avatar) 
                        : 'https://i.pinimg.com/736x/2f/c9/5a/2fc95af4700a971da5024ac5f260bcb8.jpg' }}"
                        class="rounded-circle shadow-sm" width="130" height="130" id="avatarPreview"
                        style="object-fit: cover; border: 4px solid #ffffff;">

                    <form method="POST" action="{{ route('giangvien.hoso.updateAvatar') }}"
                        enctype="multipart/form-data" id="avatarForm" class="position-absolute"
                        style="bottom: 0; right: 9px;">
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

            <!-- Cột phải -->
            <div class="col-md-8 p-4">
                <h5 class="fw-bold mb-3" style="color:#4a7fa7;">
                    <i class="bi bi-info-circle me-1"></i> Thông tin chi tiết
                </h5>

                @if ($roleName === 'GiangVien')
                <table class="table table-borderless align-middle">
                    <tr>
                        <th width="35%" class="text-secondary fw-normal"><i
                                class="bi bi-person me-2 text-primary"></i>Tên đăng nhập</th>
                        <td class="fw-semibold">{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal"><i class="bi bi-envelope me-2 text-danger"></i>Email</th>
                        <td>{{ $thongtin->email ?? 'Chưa có' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal"><i class="bi bi-telephone me-2 text-success"></i>Số điện
                            thoại</th>
                        <td>{{ $thongtin->sdt ?? 'Chưa có' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal"><i class="bi bi-journal-bookmark me-2 text-warning"></i>Bộ
                            môn</th>
                        <td>{{ $thongtin->bo_mon ?? '---' }}</td>
                    </tr>
                </table>

                @elseif ($roleName === 'DoanhNghiep')
                <table class="table table-borderless align-middle">
                    <tr>
                        <th width="35%" class="text-secondary fw-normal"><i
                                class="bi bi-building me-2 text-primary"></i>Tên doanh nghiệp</th>
                        <td class="fw-semibold">{{ $thongtin->ten_dn ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal"><i class="bi bi-envelope me-2 text-danger"></i>Email</th>
                        <td>{{ $thongtin->email ?? 'Chưa có' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal"><i class="bi bi-geo-alt me-2 text-success"></i>Địa chỉ</th>
                        <td>{{ $thongtin->dia_chi ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal"><i class="bi bi-link-45deg me-2 text-info"></i>Website</th>
                        <td>
                            @if (!empty($thongtin->website))
                            <a href="{{ $thongtin->website }}" target="_blank">{{ $thongtin->website }}</a>
                            @else
                            Chưa có
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal"><i
                                class="bi bi-person-lines-fill me-2 text-warning"></i>Người liên hệ</th>
                        <td>{{ $thongtin->lien_he ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary fw-normal"><i class="bi bi-card-text me-2 text-secondary"></i>Mô tả
                        </th>
                        <td>{{ $thongtin->mo_ta ?? '---' }}</td>
                    </tr>
                </table>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Modal cập nhật -->
<div class="modal fade" id="modalCapNhat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        @if ($roleName === 'GiangVien')
        <!-- Modal cập nhật hồ sơ giảng viên -->
        <form method="POST" action="{{ route('giangvien.hoso.update') }}"
            class="modal-content rounded-4 border-0 shadow">
            @csrf
            <div class="modal-header text-white rounded-top-4" style="background-color:#4a7fa7;">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Cập nhật hồ sơ giảng viên</h5>
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
                    <input type="text" name="sdt" value="{{ $thongtin->sdt ?? '' }}" class="form-control rounded-3">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Bộ môn</label>
                    <input type="text" name="bo_mon" value="{{ $thongtin->bo_mon ?? '' }}"
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

        @elseif ($roleName === 'DoanhNghiep')
        <!-- Modal cập nhật hồ sơ doanh nghiệp -->
        <form method="POST" action="{{ route('giangvien.hoso.update') }}" enctype="multipart/form-data"
            class="modal-content rounded-4 border-0 shadow">
            @csrf
            <div class="modal-header text-white rounded-top-4" style="background-color:#4a7fa7;">
                <h5 class="modal-title fw-bold"><i class="bi bi-building me-2"></i> Cập nhật hồ sơ doanh nghiệp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên doanh nghiệp</label>
                    <input type="text" name="ten_dn" value="{{ $thongtin->ten_dn ?? '' }}"
                        class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ $thongtin->email ?? '' }}" class="form-control rounded-3"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Địa chỉ</label>
                    <input type="text" name="dia_chi" value="{{ $thongtin->dia_chi ?? '' }}"
                        class="form-control rounded-3">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Website</label>
                    <input type="text" name="website" value="{{ $thongtin->website ?? '' }}"
                        class="form-control rounded-3">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Người liên hệ</label>
                    <input type="text" name="lien_he" value="{{ $thongtin->lien_he ?? '' }}"
                        class="form-control rounded-3">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="mo_ta" class="form-control rounded-3"
                        rows="3">{{ $thongtin->mo_ta ?? '' }}</textarea>
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
        @endif
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const success = "{{ session('success') }}";
    const error = "{{ session('error') }}";
    if (success) Swal.fire({
        icon: 'success',
        title: 'Thành công',
        text: success,
        timer: 2000,
        showConfirmButton: false
    });
    if (error) Swal.fire({
        icon: 'error',
        title: 'Lỗi',
        text: error,
        timer: 2000,
        showConfirmButton: false
    });
});
</script>
@endsection