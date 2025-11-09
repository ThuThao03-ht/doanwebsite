@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2 gap-2">
        <h2 class="fw-bold mb-0 d-flex align-items-center" style="color: #4A7FA7;">
            <i class="bi bi-briefcase me-2"></i> Quản lý vị trí thực tập
        </h2>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.vitrithuctap.export', ['dn_id' => request()->dn_id]) }}" class="btn btn-success">
                <i class="bi bi-download me-1"></i> Xuất Excel
            </a>



            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalImport">
                <i class="bi bi-upload me-1"></i> Nhập Excel
            </button>



            <button class="btn text-white" style="background-color: #4A7FA7;" data-bs-toggle="modal"
                data-bs-target="#modalAdd">
                <i class="bi bi-plus-circle me-1"></i> Thêm vị trí
            </button>
        </div>
    </div>



    <form method="GET" action="{{ route('admin.vitrithuctap.index') }}" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên vị trí"
                value="{{ request()->search }}">
        </div>
        <div class="col-md-3">
            <select name="dn_id" class="form-select">
                <option value="">-- Lọc theo doanh nghiệp --</option>
                @foreach($doanhnghieps as $dn)
                <option value="{{ $dn->dn_id }}" {{ request()->dn_id == $dn->dn_id ? 'selected' : '' }}>
                    {{ $dn->ten_dn }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 d-flex gap-2">
            <button type="submit" class="btn btn-primary" style="background-color: #4A7FA7;"><i
                    class="bi bi-search me-1"></i> Tìm kiếm</button>
            <a href="{{ route('admin.vitrithuctap.index') }}" class="btn" style=" background-color: #ffffff; color: black;
                border: 1px solid black; white-space: nowrap;"><i class="bi bi-arrow-clockwise me-1"></i>
                Làm mới</a>

        </div>
    </form>



    <!-- Bảng danh sách -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
            <i class="bi bi-list-ul me-2"></i> Danh sách vị trí thực tập
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Doanh nghiệp</th>
                        <th>Mã vị trí</th>
                        <th>Tên vị trí</th>

                        <th>Số lượng</th>
                        <th>Số lượng đã đăng ký</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vitrithuctaps as $vt)
                    <tr id="row-{{ $vt->vitri_id }}">
                        <td>{{ $vt->vitri_id }}</td>
                        <td>{{ $vt->doanhnghiep->ten_dn ?? '' }}</td>
                        <td>{{ $vt->ma_vitri }}</td>
                        <td>{{ $vt->ten_vitri }}</td>

                        <td>{{ $vt->soluong }}</td>
                        <td>{{ $vt->so_luong_da_dangky ?? 0 }}</td>
                        <td>{{ $vt->trang_thai }}</td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                data-bs-target="#modalView{{ $vt->vitri_id }}"><i class="bi bi-eye"></i></button>

                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $vt->vitri_id }}"><i
                                    class="bi bi-pencil-square"></i></button>

                            <form action="{{ route('admin.vitrithuctap.destroy', $vt->vitri_id) }}" method="POST"
                                class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>

                        </td>
                    </tr>

                    <!-- Modal Xem chi tiết -->
                    <div class="modal fade" id="modalView{{ $vt->vitri_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-header text-white" style="background-color: #4A7FA7;">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-card-list me-2"></i> Chi tiết vị trí

                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2 border-bottom pb-2"><strong>Mã vị trí:</strong> {{ $vt->ma_vitri }}
                                    </div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Tên vị trí:</strong>
                                        {{ $vt->ten_vitri }}</div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Doanh nghiệp:</strong>
                                        {{ $vt->doanhnghiep->ten_dn ?? '' }}</div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Số lượng:</strong> {{ $vt->soluong }}
                                    </div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Số lượng đã đăng ký:</strong>
                                        {{ $vt->so_luong_da_dangky ?? 0 }}</div>

                                    <div class="mb-2 border-bottom pb-2"><strong>Trạng thái:</strong>
                                        {{ $vt->trang_thai }}</div>
                                    <div class="mb-2 border-bottom pb-2"><strong>Mô tả:</strong> {{ $vt->mo_ta }}</div>
                                    <div class="mb-2 "><strong>Yêu cầu:</strong> {{ $vt->yeu_cau }}</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn text-white" style="background-color: #4A7FA7;"
                                        data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i> Đóng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Sửa -->
                    <div class="modal fade" id="modalEdit{{ $vt->vitri_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <form action="{{ route('admin.vitrithuctap.update', $vt->vitri_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <!-- Header -->
                                    <div class="modal-header text-white" style="background-color: #4A7FA7;">
                                        <h5 class="modal-title fw-bold">
                                            <i class="bi bi-pencil-square me-2"></i> Sửa vị trí
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <!-- Body -->
                                    <div class="modal-body">
                                        <!-- Dòng 1: Doanh nghiệp + Mã vị trí -->
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label><i class="bi bi-building me-1"></i>Doanh nghiệp</label>
                                                <select name="dn_id" class="form-select">
                                                    @foreach($doanhnghieps as $dn)
                                                    <option value="{{ $dn->dn_id }}"
                                                        {{ $dn->dn_id == $vt->dn_id ? 'selected' : '' }}>
                                                        {{ $dn->ten_dn }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label><i class="bi bi-hash me-1"></i>Mã vị trí</label>
                                                <input type="text" class="form-control" value="{{ $vt->ma_vitri }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <!-- Dòng 2: Tên vị trí + Số lượng -->
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label><i class="bi bi-card-text me-1"></i>Tên vị trí</label>
                                                <input type="text" name="ten_vitri" class="form-control"
                                                    value="{{ $vt->ten_vitri }}" required>
                                            </div>
                                            <div class="col">
                                                <label><i class="bi bi-people me-1"></i>Số lượng</label>
                                                <input type="number" name="soluong" class="form-control"
                                                    value="{{ $vt->soluong }}">
                                            </div>
                                        </div>

                                        <!-- Mô tả -->
                                        <div class="mb-2">
                                            <label><i class="bi bi-journal-text me-1"></i>Mô tả</label>
                                            <textarea name="mo_ta" class="form-control">{{ $vt->mo_ta }}</textarea>
                                        </div>

                                        <!-- Yêu cầu -->
                                        <div class="mb-2">
                                            <label><i class="bi bi-list-check me-1"></i>Yêu cầu</label>
                                            <textarea name="yeu_cau" class="form-control">{{ $vt->yeu_cau }}</textarea>
                                        </div>

                                        <!-- Trạng thái -->
                                        <div class="mb-2">
                                            <label><i class="bi bi-toggle-on me-1"></i>Trạng thái</label>
                                            <select name="trang_thai" class="form-select">
                                                <option value="con_han"
                                                    {{ $vt->trang_thai=='con_han' ? 'selected' : '' }}>
                                                    Còn hạn</option>
                                                <option value="het_han"
                                                    {{ $vt->trang_thai=='het_han' ? 'selected' : '' }}>
                                                    Hết hạn</option>
                                            </select>
                                        </div>

                                        <!-- Số lượng đã đăng ký -->
                                        <div class="mb-2">
                                            <label><i class="bi bi-check2-circle me-1"></i>Số lượng đã đăng ký</label>
                                            <input type="number" name="so_luong_da_dangky" class="form-control"
                                                value="{{ $vt->so_luong_da_dangky ?? 0 }}">
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn text-white" style="background-color: #4A7FA7;">
                                            <i class="bi bi-save me-1"></i> Lưu
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-1"></i> Hủy
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
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $vitrithuctaps->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modal Thêm -->
    <div class="modal fade" id="modalAdd" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form action="{{ route('admin.vitrithuctap.store') }}" method="POST">
                @csrf
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <!-- Header -->
                    <div class="modal-header text-white" style="background-color: #4A7FA7;">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-plus-circle me-2"></i> Thêm vị trí mới
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <!-- Dòng 1: Doanh nghiệp + Mã vị trí -->
                        <div class="row mb-2">
                            <div class="col">
                                <label><i class="bi bi-building me-1"></i>Doanh nghiệp</label>
                                <select name="dn_id" class="form-select">
                                    @foreach($doanhnghieps as $dn)
                                    <option value="{{ $dn->dn_id }}">{{ $dn->ten_dn }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label><i class="bi bi-hash me-1"></i>Mã vị trí</label>
                                <!-- <input type="text" name="ma_vitri" class="form-control"
                                    value="{{ 'VT'.str_pad((\App\Models\VitriThuctap::max('vitri_id') + 1), 4, '0', STR_PAD_LEFT) }}"
                                    readonly> -->


                                <input type="text" name="ma_vitri" class="form-control" value="{{ $ma_vitri }}"
                                    readonly>
                            </div>
                        </div>

                        <!-- Dòng 2: Tên vị trí + Số lượng -->
                        <div class="row mb-2">
                            <div class="col">
                                <label><i class="bi bi-card-text me-1"></i>Tên vị trí</label>
                                <input type="text" name="ten_vitri" class="form-control" required>
                            </div>
                            <div class="col">
                                <label><i class="bi bi-people me-1"></i>Số lượng</label>
                                <input type="number" name="soluong" class="form-control" value="1">
                            </div>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-2">
                            <label><i class="bi bi-journal-text me-1"></i>Mô tả</label>
                            <textarea name="mo_ta" class="form-control"></textarea>
                        </div>

                        <!-- Yêu cầu -->
                        <div class="mb-2">
                            <label><i class="bi bi-list-check me-1"></i>Yêu cầu</label>
                            <textarea name="yeu_cau" class="form-control"></textarea>
                        </div>


                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn text-white" style="background-color: #4A7FA7;">
                            <i class="bi bi-save me-1"></i> Lưu
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Hủy
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Import vị trí thực tập -->
    <div class="modal fade" id="modalImport" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form action="{{ route('admin.vitrithuctap.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <!-- Header -->
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-upload me-2"></i> Nhập danh sách vị trí thực tập từ Excel
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-file-earmark-excel me-2 text-success"></i> Chọn file Excel (.xlsx hoặc
                                .xls)
                            </label>
                            <input type="file" name="file" class="form-control border-0 border-bottom rounded-0"
                                required>
                        </div>

                        <!-- Ghi chú cột -->
                        <div class="alert alert-light border border-secondary p-3 small">
                            <strong>Các cột cần có trong file Excel:</strong>
                            <ul class="mb-0 ps-3">
                                <li><code style="color:#0D6EFD;">dn_id</code> (bắt buộc) – ID doanh nghiệp phải có trong
                                    hệ thống</li>
                                <li><code style="color:#0D6EFD;">ma_vitri</code> (bắt buộc) – Mã vị trí thực tập</li>
                                <li><code style="color:#0D6EFD;">ten_vitri</code> (bắt buộc) – Tên vị trí thực tập</li>
                                <li><code style="color:#0D6EFD;">mo_ta</code> (tùy chọn) – Mô tả vị trí</li>
                                <li><code style="color:#0D6EFD;">yeu_cau</code> (tùy chọn) – Yêu cầu vị trí</li>
                                <li><code style="color:#0D6EFD;">soluong</code> (tùy chọn) – Số lượng, mặc định 1 nếu để
                                    trống</li>
                                <li><code style="color:#0D6EFD;">so_luong_da_dangky</code> (tùy chọn) – Số lượng đã đăng
                                    ký, mặc định 0</li>
                                <li><code style="color:#0D6EFD;">trang_thai</code> (tùy chọn) – Trạng thái, mặc định
                                    'con_han'</li>
                            </ul>

                            <small class="text-danger">
                                Lưu ý: Doanh nghiệp phải tồn tại trong hệ thống, nếu <code>dn_id</code> không đúng, dữ
                                liệu sẽ không được import.
                            </small>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer d-flex justify-content-between px-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-circle me-1"></i> Nhập
                        </button>
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Hủy
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



</div>




<!-- SweetAlert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hiển thị thông báo thành công


    // Xác nhận xóa
    // Chỉ xác nhận xóa cho form có class delete-form
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc chắn xóa không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});

// Lấy thông báo từ session, nếu null thì gán ''
var importError = "{{ session('import_error') ?? '' }}";
var importSuccess = "{{ session('success') ?? '' }}";

if (importError) {
    Swal.fire({
        icon: 'error',
        title: 'Lỗi Import',
        html: importError,
        confirmButtonText: 'Đóng'
    });
}

if (importSuccess) {
    Swal.fire({
        icon: 'success',
        title: 'Thành công',
        text: importSuccess,
        timer: 2000,
        showConfirmButton: false
    });
}
</script>
<style>
/* Ẩn mọi phần thông tin mô tả trong pagination */
.pagination-info,
.text-sm.text-gray-700.leading-5,
.small.text-muted {
    display: none !important;
}

/* Pagination màu chủ đạo */
.pagination .page-link {
    color: #4A7FA7;
    /* chữ màu */

}

.pagination .page-link:hover {
    background-color: #4A7FA7;
    color: #fff;
}

.pagination .page-item.active .page-link {
    background-color: #4A7FA7;
    border-color: #4A7FA7;
    color: #fff;
}

.pagination .page-link:focus {
    box-shadow: none;
}
</style>
@endsection