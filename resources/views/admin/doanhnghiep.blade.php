@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Tiêu đề và nút thêm nằm cùng hàng, phân cách bằng đường dọc -->
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2" style="color: #4A7FA7">
        <h2 class="fw-bold mb-0 d-flex align-items-center text-theme">
            <i class="bi bi-building me-2 text-primary"></i> Quản lý Doanh nghiệp
        </h2>

        <button class="btn text-white" style="background-color: #4A7FA7;" data-bs-toggle="modal"
            data-bs-target="#modalAdd">
            <i class="bi bi-plus-circle me-1"></i> Thêm doanh nghiệp
        </button>
    </div>
    <!-- Thanh tìm kiếm + nút làm mới cùng hàng -->
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
        <form action="{{ route('admin.doanhnghiep.index') }}" method="GET"
            class="d-flex align-items-center gap-2 mb-2 mb-md-0">
            <div class="input-group" style="max-width: 400px;">
                <input type="text" name="search" class="form-control" placeholder="Nhập tên doanh nghiệp..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn text-white" style="background-color: #4A7FA7;">
                    <i class="bi bi-search me-1"></i> Tìm kiếm
                </button>
            </div>
            <a href="{{ route('admin.doanhnghiep.index') }}" class="btn d-inline-flex align-items-center px-3 py-2"
                style="background-color: #ffffff; color: black; border: 1px solid black; white-space: nowrap;">
                <i class="bi bi-arrow-clockwise me-1"></i> Làm mới
            </a>


        </form>


    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
            <i class="bi bi-list-ul me-2"></i> Danh sách doanh nghiệp
        </div>
        <!-- Bảng danh sách doanh nghiệp -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên DN</th>
                        <th>Email</th>
                        <th>Liên hệ</th>
                        <th>Website</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doanhnghieps as $dn)
                    <tr id="row-{{ $dn->dn_id }}">
                        <td>{{ $dn->dn_id }}</td>
                        <td>{{ $dn->ten_dn }}</td>
                        <td>{{ $dn->email }}</td>
                        <td>{{ $dn->lien_he }}</td>
                        <td>{{ $dn->website }}</td>
                        <td>
                            <!-- Xem chi tiết -->
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                data-bs-target="#modalView{{ $dn->dn_id }}">
                                <i class="bi bi-eye"></i>
                            </button>

                            <!-- Sửa -->
                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $dn->dn_id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Xóa -->
                            <form action="{{ route('admin.doanhnghiep.destroy', $dn->dn_id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Xem chi tiết -->
                    <div class="modal fade" id="modalView{{ $dn->dn_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <!-- modal-ngắn hơn: modal-md -->
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <!-- Header với màu chủ đạo -->
                                <div class="modal-header text-white" style="background-color: #4A7FA7;">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-building me-2"></i> Chi tiết doanh nghiệp
                                    </h5>

                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-2 border-bottom pb-2"><strong>Tên DN:</strong> {{ $dn->ten_dn }}
                                    </div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Email:</strong> {{ $dn->email }}</div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Liên hệ:</strong> {{ $dn->lien_he }}
                                    </div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Địa chỉ:</strong> {{ $dn->dia_chi }}
                                    </div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Website:</strong> {{ $dn->website }}
                                    </div>
                                    <div class="mb-2"><strong>Mô tả:</strong> {{ $dn->mo_ta }}</div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light text-white"
                                        style="background-color: #4A7FA7;" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i> Đóng
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Modal Sửa -->
                    <!-- Modal Sửa -->
                    <div class="modal fade" id="modalEdit{{ $dn->dn_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <form action="{{ route('admin.doanhnghiep.update', $dn->dn_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow-lg rounded-4">

                                    <!-- Header -->
                                    <div class="modal-header text-white" style="background-color: #4A7FA7;">
                                        <h5 class="modal-title fw-bold">
                                            <i class="bi bi-pencil-square me-2"></i> Sửa doanh nghiệp
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <!-- Hàng 1 -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-semibold">Tên doanh nghiệp</label>
                                                <input type="text" name="ten_dn" class="form-control"
                                                    value="{{ $dn->ten_dn }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-semibold">Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ $dn->email }}" required>
                                            </div>
                                        </div>
                                        <hr>

                                        <!-- Hàng 2 -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-semibold">Liên hệ</label>
                                                <input type="text" name="lien_he" class="form-control"
                                                    value="{{ $dn->lien_he }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-semibold">Địa chỉ</label>
                                                <input type="text" name="dia_chi" class="form-control"
                                                    value="{{ $dn->dia_chi }}">
                                            </div>
                                        </div>
                                        <hr>

                                        <!-- Hàng 3 -->
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-semibold">Website</label>
                                                <input type="text" name="website" class="form-control"
                                                    value="{{ $dn->website }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-semibold">Mô tả</label>
                                                <textarea name="mo_ta" class="form-control"
                                                    rows="1">{{ $dn->mo_ta }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer d-flex justify-content-between">
                                        <button type="submit" class="btn text-white" style="background-color: #4A7FA7;">
                                            <i class="bi bi-check-circle me-1"></i> Cập nhật
                                        </button>
                                        <button type="button" class="btn text-white" style="background-color: #6c757d;"
                                            data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-1"></i> Đóng
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>


                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Doanh Nghiệp -->
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form action="{{ route('admin.doanhnghiep.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- Header -->
                <div class="modal-header text-white" style="background-color: #4A7FA7;">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-building-add me-2"></i> Thêm doanh nghiệp
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <!-- Hàng 1 -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Tên doanh nghiệp</label>
                            <input type="text" name="ten_dn" class="form-control" placeholder="Nhập tên doanh nghiệp"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Email</label>

                            <input type="email" name="email" class="form-control form-control-sm"
                                placeholder="Nhập email" value="">
                            <div class="invalid-feedback"></div>
                            <!-- <input type="email" name="email" class="form-control" placeholder="Nhập email" required> -->
                        </div>
                    </div>
                    <hr>

                    <!-- Hàng 2 -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Liên hệ</label>
                            <input type="text" name="lien_he" class="form-control" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Địa chỉ</label>
                            <input type="text" name="dia_chi" class="form-control" placeholder="Nhập địa chỉ">
                        </div>
                    </div>
                    <hr>

                    <!-- Hàng 3 -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="fw-semibold">Website</label>
                            <input type="text" name="website" class="form-control" placeholder="Nhập website (nếu có)">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold">Mô tả</label>
                            <textarea name="mo_ta" class="form-control" rows="1"
                                placeholder="Mô tả ngắn về doanh nghiệp"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="submit" class="btn text-white" style="background-color: #4A7FA7;">
                        <i class="bi bi-plus-circle me-1"></i> Thêm mới
                    </button>
                    <button type="button" class="btn text-white" style="background-color: #6c757d;"
                        data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Đóng
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Hiển thị thông báo success từ session
const flashMessage = "{{ session('success') }}";
if (flashMessage) {
    Swal.fire({
        icon: 'success',
        title: 'Thành công',
        text: flashMessage,
        confirmButtonColor: '#3085d6'
    });
}

// Xác nhận xóa doanh nghiệp
document.querySelectorAll('form.d-inline').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    function validateEmail(email) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
    }

    function showInlineError(input, message) {
        const errorDiv = input.nextElementSibling;
        if (!errorDiv) return;
        if (message) {
            input.classList.add('is-invalid');
            errorDiv.textContent = message;
        } else {
            input.classList.remove('is-invalid');
            errorDiv.textContent = '';
        }
    }

    function attachValidation(form) {
        const emailInput = form.querySelector('input[name="email"]');
        if (!emailInput) return;

        // Kiểm tra khi đang nhập
        emailInput.addEventListener('input', function() {
            showInlineError(
                emailInput,
                validateEmail(emailInput.value) ? '' : 'Email không đúng định dạng.'
            );
        });

        // Kiểm tra khi submit
        form.addEventListener('submit', function(e) {
            if (!validateEmail(emailInput.value)) {
                showInlineError(emailInput, 'Email không đúng định dạng.');
                e.preventDefault();
            }
        });
    }

    // Áp dụng cho modal Add
    const addForm = document.querySelector('#modalAdd form');
    if (addForm) attachValidation(addForm);

    // Áp dụng cho các modal Edit
    document.querySelectorAll('[id^="modalEdit"] form').forEach(form => attachValidation(form));

    // Reset lỗi khi đóng modal
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                'is-invalid'));
            this.querySelectorAll('.invalid-feedback').forEach(div => div.textContent = '');
        });
    });
});
</script>

@endsection