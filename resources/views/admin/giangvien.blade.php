@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom" style="color: #4A7FA7">
        <h2 class=" fw-bold mb-0"><i class="bi bi-person-workspace me-2"></i> Quản lý Giảng viên</h2>
        <button class="btn rounded-3 text-white" data-bs-toggle="modal" data-bs-target="#modalAdd"
            style="background-color: #4A7FA7; border: none;">
            <i class="bi bi-plus-circle me-1 text-white"></i> Thêm giảng viên
        </button>


    </div>


    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
            <i class="bi bi-list-ul me-2"></i> Danh sách giảng viên
        </div>

        <!-- Bộ lọc -->
        <form method="GET" action="{{ route('admin.giangvien.index') }}"
            class="row g-2 align-items-center mb-3 mt-2 px-3">
            <div class="col-md-4">
                <select name="bo_mon" class="form-select border-2 rounded-3" style="border-color: #4A7FA7;">
                    <option value="">-- Lọc theo Bộ môn --</option>
                    @foreach($boMons as $bm)
                    <option value="{{ $bm }}" {{ request('bo_mon') == $bm ? 'selected' : '' }}>
                        {{ $bm }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5">
                <input type="text" name="keyword" class="form-control border-2 rounded-3" style="border-color: #4A7FA7;"
                    placeholder="Nhập tên giảng viên..." value="{{ request('keyword') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn text-white flex-fill" style="background-color: #4A7FA7;">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.giangvien.index') }}" class="btn btn-outline-secondary flex-fill">
                    <i class="bi bi-arrow-repeat"></i> Làm mới
                </a>
            </div>
        </form>

        <!-- Bảng danh sách -->
        <div class="table-responsive px-3 pb-3">
            <table class="table table-hover align-middle text-center mb-0 shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Mã GV</th>
                        <th>Họ tên</th>
                        <th>Bộ môn</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($giangviens as $index => $gv)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $gv->ma_gv }}</td>
                        <td>{{ $gv->ho_ten }}</td>
                        <td>{{ $gv->bo_mon }}</td>
                        <td>{{ $gv->email }}</td>
                        <td>{{ $gv->sdt }}</td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                data-bs-target="#modalView{{ $gv->gv_id }}"><i class="bi bi-eye"></i></button>

                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $gv->gv_id }}"><i class="bi bi-pencil-square"></i></button>

                            <form action="{{ route('admin.giangvien.destroy', $gv->gv_id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalView{{ $gv->gv_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4" style="border: 2px solid #4A7FA7;">

                                <!-- Header -->
                                <div class="modal-header text-white" style="background-color: #4A7FA7;">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-person-vcard me-2"></i> Chi tiết thông tin giảng viên
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Body -->
                                <div class="modal-body">
                                    <div class="px-2">
                                        <div class="py-2 border-bottom">
                                            <i class="bi bi-upc-scan text-theme me-2" style="color: #4A7FA7;"></i>
                                            <strong>Mã GV:</strong> {{ $gv->ma_gv }}
                                        </div>
                                        <div class="py-2 border-bottom">
                                            <i class="bi bi-person-fill text-theme me-2" style="color: #4A7FA7;"></i>
                                            <strong>Họ tên:</strong> {{ $gv->ho_ten }}
                                        </div>
                                        <div class="py-2 border-bottom">
                                            <i class="bi bi-journal-text text-theme me-2" style="color: #4A7FA7;"></i>
                                            <strong>Bộ môn:</strong> {{ $gv->bo_mon }}
                                        </div>
                                        <div class="py-2 border-bottom">
                                            <i class="bi bi-envelope-at-fill text-theme me-2"
                                                style="color: #4A7FA7;"></i>
                                            <strong>Email:</strong> {{ $gv->email }}
                                        </div>
                                        <div class="py-2">
                                            <i class="bi bi-telephone-fill text-theme me-2" style="color: #4A7FA7;"></i>
                                            <strong>SĐT:</strong> {{ $gv->sdt }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="modal-footer justify-content-end border-0">
                                    <button type="button" class="btn text-white px-4" data-bs-dismiss="modal"
                                        style="background-color: #4A7FA7; border-radius: 12px;">
                                        <i class="bi bi-x-circle me-1"></i> Đóng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="modal fade" id="modalEdit{{ $gv->gv_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <form action="{{ route('admin.giangvien.update', $gv->gv_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="edit_gv_id" value="{{ $gv->gv_id }}">
                                <div class="modal-content border-0 shadow-lg rounded-4"
                                    style="border: 2px solid #4A7FA7;">

                                    <!-- Header -->
                                    <div class="modal-header text-white" style="background-color: #4A7FA7;">
                                        <h5 class="modal-title fw-bold d-flex align-items-center">
                                            <i class="bi bi-pencil-square me-2"></i> Chỉnh thông tin giảng viên
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <!-- Body -->
                                    <div class="modal-body px-4 py-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-secondary">
                                                    <i class="bi bi-person me-2 text-primary"></i> Họ tên
                                                </label>
                                                <input type="text" name="ho_ten"
                                                    class="form-control border-0 border-bottom rounded-0"
                                                    value="{{ $gv->ho_ten }}" data-origin="{{ $gv->ho_ten }}">

                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-secondary">
                                                    <i class="bi bi-journal-text me-2 text-primary"></i> Bộ môn
                                                </label>
                                                <input type="text" name="bo_mon"
                                                    class="form-control border-0 border-bottom rounded-0"
                                                    value="{{ $gv->bo_mon }}" data-origin="{{ $gv->bo_mon }}">

                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-secondary">
                                                    <i class="bi bi-envelope-at me-2 text-primary"></i> Email
                                                </label>
                                                <input type="email" name="email"
                                                    class="form-control border-0 border-bottom rounded-0 @error('email') is-invalid @enderror"
                                                    value="{{ $gv->email }}" data-origin="{{ $gv->email }}">


                                                @error('email')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror

                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-secondary">
                                                    <i class="bi bi-telephone me-2 text-primary"></i> Số điện thoại
                                                </label>
                                                <input type="text" name="sdt"
                                                    class="form-control border-0 border-bottom rounded-0 @error('sdt') is-invalid @enderror"
                                                    value="{{ $gv->sdt }}" data-origin="{{ $gv->sdt }}">

                                                @error('sdt')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="modal-footer d-flex justify-content-between px-4 border-0">
                                        <button type="button" class="btn btn-outline-secondary px-4"
                                            data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-1"></i> Hủy
                                        </button>
                                        <button type="submit" class="btn px-4 text-white"
                                            style="background-color: #4A7FA7;">
                                            <i class="bi bi-save2 me-1"></i> Lưu thay đổi
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


<!-- Modal thêm -->
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('admin.giangvien.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- Header -->
                <div class="modal-header text-white" style="background-color: #4A7FA7;">
                    <h5 class="modal-title fw-bold d-flex align-items-center">
                        <i class="bi bi-plus-circle me-2"></i> Thêm giảng viên mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body px-4 py-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-person-badge me-2 text-primary"></i> Mã giảng viên
                            </label>
                            <input type="text" name="ma_gv" class="form-control border-0 border-bottom rounded-0"
                                value="{{ $newMaGV }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-person me-2 text-primary"></i> Họ tên
                            </label>
                            <input type="text" name="ho_ten" value="{{ old('ho_ten') }}" class="form-control border-0 border-bottom rounded-0
              @error('ho_ten') is-invalid @enderror">

                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-journal-text me-2 text-primary"></i> Bộ môn
                            </label>
                            <input type="text" name="bo_mon" class="form-control border-0 border-bottom rounded-0"
                                value="{{ old('bo_mon') }}">

                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-envelope-at me-2 text-primary"></i> Email
                            </label>

                            <input type="email" name="email" value="{{ old('email') }}" class="form-control border-0 border-bottom rounded-0
              @error('email') is-invalid @enderror">

                            <div class="invalid-feedback d-block">
                                @error('email') {{ $message }} @enderror
                            </div>


                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-telephone me-2 text-primary"></i> Số điện thoại
                            </label>

                            <input type="text" name="sdt" value="{{ old('sdt') }}" class="form-control border-0 border-bottom rounded-0
              @error('sdt') is-invalid @enderror">

                            <div class="invalid-feedback d-block">
                                @error('sdt') {{ $message }} @enderror
                            </div>

                            <!-- <input type="text" name="sdt" class="form-control border-0 border-bottom rounded-0"> -->
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between px-4">
                    <button type="submit" class="btn px-4 text-white" style="background-color: #4A7FA7;">
                        <i class="bi bi-check-circle me-1"></i> Thêm mới
                    </button>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal"
                        id="btnCancelAdd">
                        <i class="bi bi-x-circle me-1"></i> Hủy
                    </button>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    function validateEmail(email) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
    }

    function validatePhone(sdt) {
        const pattern = /^(\+84|0)\d{9}$/;
        return sdt === '' || pattern.test(sdt);
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
        const sdtInput = form.querySelector('input[name="sdt"]');

        if (!emailInput || !sdtInput) return;

        emailInput.addEventListener('input', function() {
            showInlineError(emailInput, validateEmail(emailInput.value) ? '' :
                'Email không đúng định dạng.');
        });

        sdtInput.addEventListener('input', function() {
            showInlineError(sdtInput, validatePhone(sdtInput.value) ? '' :
                'Số điện thoại không hợp lệ.');
        });

        form.addEventListener('submit', function(e) {
            let valid = true;
            if (!validateEmail(emailInput.value)) {
                showInlineError(emailInput, 'Email không đúng định dạng.');
                valid = false;
            }
            if (!validatePhone(sdtInput.value)) {
                showInlineError(sdtInput, 'Số điện thoại không hợp lệ.');
                valid = false;
            }
            if (!valid) e.preventDefault();
        });
    }

    // Modal Add
    const addForm = document.querySelector('#modalAdd form');
    if (addForm) attachValidation(addForm);

    // Modal Edit
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
<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('[id^="modalEdit"]').forEach(modal => {

        modal.addEventListener('hidden.bs.modal', function() {

            const form = modal.querySelector('form');

            // Reset về dữ liệu gốc từ DB
            form.querySelectorAll('input, textarea, select').forEach(el => {
                if (el.dataset.origin !== undefined) {
                    el.value = el.dataset.origin;
                }
                el.classList.remove('is-invalid');
            });

            // Xóa thông báo lỗi
            form.querySelectorAll('.invalid-feedback').forEach(el => {
                el.textContent = '';
            });

        });
    });

});
</script>

<!-- SweetAlert Thông báo & Xác nhận -->
<script>
const successMessage = "{{ session('success') }}";
const errorMessage = "{{ session('error') }}";

document.addEventListener('DOMContentLoaded', () => {

    // 🧩 Thông báo thành công
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: successMessage,
            showConfirmButton: false,
            timer: 1800
        });
    }

    // 🧩 Thông báo lỗi
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: errorMessage,
            confirmButtonText: 'Đóng'
        });
    }

    // 🧩 Xác nhận xóa giảng viên
    document.querySelectorAll('form.d-inline').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc muốn xóa?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa ngay',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
<div id="validation-data" data-haserror="{{ $errors->any() ? '1' : '0' }}" data-gvid="{{ old('edit_gv_id') }}">
</div>

<!-- 
@if ($errors->any())
<script>
document.addEventListener("DOMContentLoaded", function() {
    let modal = new bootstrap.Modal(document.getElementById('modalAdd'));
    modal.show();
});
</script>
@endif -->



<script>
document.addEventListener('DOMContentLoaded', function() {
    let el = document.getElementById('validation-data');
    let hasError = el.dataset.haserror === "1";
    let editGvId = el.dataset.gvid;

    if (hasError && editGvId) {
        let modalId = "modalEdit" + editGvId;
        let modalEl = document.getElementById(modalId);

        if (modalEl) {
            let modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalAdd = document.getElementById('modalAdd');

    modalAdd.addEventListener('hidden.bs.modal', function() {
        const form = modalAdd.querySelector('form');

        form.querySelectorAll('input, textarea, select').forEach(el => {

            // KHÔNG xóa mã giảng viên
            if (el.name === 'ma_gv') return;

            el.value = '';
            el.classList.remove('is-invalid');
        });

        // Clear message lỗi
        form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
        });
    });
});
</script>




<style>
.text-theme {
    color: #4A7FA7 !important;
}

.btn-theme {
    background-color: #4A7FA7;
    color: white;
}

.btn-theme:hover {
    background-color: #3b6a8e;
}

/* Giữ kiểu border-bottom dù có lỗi */
.form-control {
    border: none !important;
    border-bottom: 1px solid #ccc !important;
    border-radius: 0 !important;
}

/* Khi focus */
.form-control:focus {
    box-shadow: none !important;
    border-bottom: 1px solid #0d6efd !important;
}

/* Khi có lỗi (Bootstrap thêm .is-invalid) */
.form-control.is-invalid {
    border-bottom: 1px solid #dc3545 !important;
    /* gạch dưới đỏ */
    background-image: none !important;
}

.form-control {
    border: none !important;
    border-bottom: 1px solid #ccc !important;
    border-radius: 0 !important;
    padding-bottom: 6px !important;
    padding-top: 6px !important;
}

.form-control:focus {
    box-shadow: none !important;
    border-bottom: 2px solid #0d6efd !important;
}

.form-control.is-invalid {
    border-bottom: 2px solid #dc3545 !important;
}
</style>

@endsection