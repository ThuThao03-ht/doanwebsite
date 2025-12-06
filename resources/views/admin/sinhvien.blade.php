@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <h2 class="fw-bolder mb-0 d-flex align-items-center text-theme">
            <i class="bi bi-people-fill me-2"></i> Quản lý Sinh viên
        </h2>




        <div class="d-flex gap-2">

            <div class="d-flex align-items-center gap-2">
                <select id="filterNganh" class="form-select" style="width: 250px;">
                    <option value="">-- Chọn ngành để xuất --</option>
                    @foreach($dsNganh as $nganh)
                    <option value="{{ $nganh }}">{{ $nganh }}</option>
                    @endforeach
                </select>

                <a id="btnExport" href="{{ route('admin.sinhvien.export') }}"
                    class="btn btn-success d-flex align-items-center px-3 shadow-sm">
                    <i class="bi bi-file-earmark-excel-fill me-2"></i> Xuất Excel
                </a>
            </div>
            <button class="btn btn-secondary d-flex align-items-center px-3 shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalImport">
                <i class="bi bi-upload me-2"></i> Nhập Excel
            </button>

            <button class="btn btn-theme d-flex align-items-center px-3 shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalAdd">
                <i class="bi bi-person-plus-fill me-2"></i> Thêm sinh viên
            </button>
        </div>
    </div>
    <div class="d-flex align-items-center mb-3" style="width: 100%; max-width: 280px; gap: 8px;">
        <!-- Ô tìm kiếm -->
        <div style="position: relative; flex-grow: 1;">
            <i class="bi bi-search"
                style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #888;"></i>
            <input type="text" id="searchName" class="form-control ps-5" placeholder="Tìm theo tên sinh viên...">
        </div>

        <!-- Nút làm mới -->
        <a href="{{ route('admin.sinhvien.index') }}" class="btn  d-inline-flex align-items-center"
            style="background-color: #ffffff; color: black; border: 1px solid black; white-space: nowrap;">
            <i class="bi bi-arrow-clockwise me-1"></i> Làm mới
        </a>

    </div>





    <!-- Bảng danh sách sinh viên -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
            <i class="bi bi-list-ul me-2"></i> Danh sách sinh viên
        </div>


        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px">#</th>
                        <th style="width:90px">Mã SV</th>
                        <th style="width:180px">Họ Tên</th>
                        <th style="width:90px">Lớp</th>
                        <th style="width:150px">Ngành</th>
                        <th style="width:200px">Email</th>
                        <th style="width:120px">SĐT</th>
                        <th class="text-center" style="width:120px">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sinhviens as $index => $sv)
                    <tr>
                        <td>{{ $sinhviens->firstItem() + $index }}</td>
                        <td class="text-center">{{ $sv->ma_sv }}</td>
                        <td>{{ $sv->ho_ten }}</td>
                        <td class="text-center">{{ $sv->lop }}</td>
                        <td class="nganh-cell">{{ $sv->nganh }}</td>
                        <td class="text-center">{{ $sv->email }}</td>
                        <td class="text-center">{{ $sv->sdt }}</td>
                        <!-- <td>{{ $sv->user ? $sv->user->username : '-' }}</td> -->
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm border-0" style="color:#0dcaf0; background:#e6f9fd"
                                    data-bs-toggle="modal" data-bs-target="#modalView{{ $sv->sv_id }}" title="Xem">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                <button class="btn btn-sm border-0" style="color:#ffc107; background:#fff8e1"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $sv->sv_id }}" title="Sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button class="btn btn-sm border-0 delete-btn" style="color:#dc3545; background:#fde8e8"
                                    data-id="{{ $sv->sv_id }}" data-name="{{ $sv->ho_ten }}" title="Xóa">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </td>


                    </tr>

                    <!-- Modal Xem -->
                    <div class="modal fade" id="modalView{{ $sv->sv_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-sm">
                                <div class="modal-header bg-theme text-white">
                                    <h5 class="modal-title fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Chi tiết
                                        thông
                                        tin
                                        sinh viên
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-card-text me-2 text-theme"></i>
                                        <strong>Mã SV:</strong> <span class="ms-2">{{ $sv->ma_sv }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-person-fill me-2 text-theme"></i>
                                        <strong>Họ tên:</strong> <span class="ms-2">{{ $sv->ho_ten }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-envelope-fill me-2 text-theme"></i>
                                        <strong>Email:</strong> <span class="ms-2">{{ $sv->email }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-building me-2 text-theme"></i>
                                        <strong>Lớp:</strong> <span class="ms-2">{{ $sv->lop }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-journal-text me-2 text-theme"></i>
                                        <strong>Ngành:</strong> <span class="ms-2">{{ $sv->nganh }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-telephone-fill me-2 text-theme"></i>
                                        <strong>SĐT:</strong> <span class="ms-2">{{ $sv->sdt }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge-fill me-2 text-theme"></i>
                                        <strong>Username:</strong> <span
                                            class="ms-2">{{ $sv->user ? $sv->user->username : '-' }}</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-theme" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i>Đóng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Sửa -->
                    <div class="modal fade" id="modalEdit{{ $sv->sv_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <form action="{{ route('admin.sinhvien.update', $sv->sv_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow rounded-3">

                                    <!-- Header -->
                                    <div class="modal-header py-2" style="background-color: #4A7FA7; color: white;">
                                        <h6 class="modal-title fw-bold">
                                            <i class="bi bi-pencil-square me-2"></i> Chỉnh thông tin sinh viên
                                        </h6>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <!-- Body -->
                                    <div class="modal-body p-3">
                                        <input type="hidden" name="ma_sv" value="{{ $sv->ma_sv }}">

                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary">
                                                <i class="bi bi-person-fill me-1 text-primary"></i> Họ tên
                                            </label>
                                            <input type="text" name="ho_ten" class="form-control form-control-sm"
                                                value="{{ $sv->ho_ten }}" required>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary">
                                                <i class="bi bi-envelope-fill me-1 text-primary"></i> Email
                                            </label>
                                            <input type="email" name="email" class="form-control form-control-sm"
                                                value="{{ $sv->email }}" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary">
                                                <i class="bi bi-building me-1 text-primary"></i> Lớp
                                            </label>
                                            <input type="text" name="lop" class="form-control form-control-sm"
                                                value="{{ $sv->lop }}">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary">
                                                <i class="bi bi-journal-text me-1 text-primary"></i> Ngành
                                            </label>
                                            <input type="text" name="nganh" class="form-control form-control-sm"
                                                value="{{ $sv->nganh }}">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold text-secondary">
                                                <i class="bi bi-telephone-fill me-1 text-primary"></i> Số điện thoại
                                            </label>
                                            <input type="text" name="sdt" class="form-control form-control-sm"
                                                value="{{ $sv->sdt }}" required>

                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="modal-footer py-2 px-3">
                                        <button class="btn btn-sm text-white" style="background-color: #4A7FA7;">
                                            <i class="bi bi-check-circle me-1"></i> Lưu
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-dismiss="modal">
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

            <div class="d-flex justify-content-center mt-3">
                {{ $sinhviens->links('pagination::bootstrap-5') }}
            </div>

        </div>

        <!-- Modal Thêm Sinh Viên -->
        <div class="modal fade" id="modalAdd" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <!-- Tăng kích thước -->
                <form action="{{ route('admin.sinhvien.store') }}" method="POST">
                    @csrf
                    <div class="modal-content border-0 shadow-lg rounded-4">

                        <!-- Header -->
                        <div class="modal-header" style="background-color: #4A7FA7; color: white;">
                            <h5 class="modal-title fw-bold d-flex align-items-center">
                                <i class="bi bi-person-plus me-2"></i> Thêm sinh viên mới
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Body -->
                        <div class="modal-body px-4 py-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary">
                                        <i class="bi bi-card-list me-2 text-primary"></i> Mã sinh viên
                                    </label>
                                    <input type="text" name="ma_sv"
                                        class="form-control border-0 border-bottom rounded-0" value="{{ $nextMaSV }}"
                                        readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary">
                                        <i class="bi bi-person me-2 text-primary"></i> Họ tên
                                    </label>
                                    <input type="text" name="ho_ten"
                                        class="form-control border-0 border-bottom rounded-0" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">
                                        <i class="bi bi-envelope-fill me-1 text-primary"></i> Email
                                    </label>
                                    <input type="email" name="email" class="form-control form-control-sm" value="">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary">
                                        <i class="bi bi-building me-2 text-primary"></i> Lớp
                                    </label>
                                    <input type="text" name="lop" class="form-control border-0 border-bottom rounded-0">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary">
                                        <i class="bi bi-book me-2 text-primary"></i> Ngành
                                    </label>
                                    <input type="text" name="nganh"
                                        class="form-control border-0 border-bottom rounded-0">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small fw-semibold text-secondary">
                                        <i class="bi bi-telephone-fill me-1 text-primary"></i> Số điện thoại
                                    </label>
                                    <input type="text" name="sdt" class="form-control form-control-sm" value="">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="modal-footer d-flex justify-content-between px-4">
                            <button type="submit" class="btn px-4 text-white" style="background-color: #4A7FA7;">
                                <i class="bi bi-check-circle me-1"></i> Thêm mới
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Hủy
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal Import -->
        <div class="modal fade" id="modalImport" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.sinhvien.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title d-flex align-items-center">
                                <i class="bi bi-upload me-2"></i> Nhập danh sách sinh viên từ Excel
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-file-earmark-excel me-2 text-success"></i> Chọn file Excel (.xlsx
                                    hoặc .xls)
                                </label>
                                <input type="file" name="file" class="form-control border-0 border-bottom rounded-0"
                                    required>
                                <small class="text-muted">Cột cần có: ma_sinh_vien, ho_ten, lop, nganh, email,
                                    sdt</small>
                            </div>
                        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // SweetAlert thông báo success/error/validation
        <?php
    if(session()->has('success')) {
        $msg = addslashes(session('success'));
        echo "Swal.fire({icon:'success',title:'Thành công!',text:'{$msg}',timer:2500,showConfirmButton:false});";
    }
    if(session()->has('error')) {
        $msg = addslashes(session('error'));
        echo "Swal.fire({icon:'error',title:'Lỗi!',text:'{$msg}',timer:2500,showConfirmButton:false});";
    }
    if($errors->any()) {
        $allErrors = '';
        foreach($errors->all() as $error) $allErrors .= addslashes($error)."\\n";
        echo "Swal.fire({icon:'error',title:'Lỗi nhập liệu',html:'{$allErrors}'.replace(/\\n/g,'<br>'),showConfirmButton:true});";
    }
    ?>

        // Xóa sinh viên với SweetAlert confirm
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let svId = this.dataset.id;
                let svName = this.dataset.name;
                Swal.fire({
                    title: 'Bạn có chắc?',
                    text: `Xóa sinh viên "${svName}" không? Hành động này không thể hoàn tác!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, xóa!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tạo form tạm và submit
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/sinhvien/' + svId; // route destroy
                        form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

    });
    </script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('filterNganh');
        const btnExport = document.getElementById('btnExport');
        const table = document.querySelector('table.table-hover tbody');
        const searchInput = document.getElementById('searchName');

        // Lọc theo ngành
        select.addEventListener('change', filterTable);
        // Lọc theo tên sinh viên
        searchInput.addEventListener('input', filterTable);

        function filterTable() {
            const nganh = select.value.trim().toLowerCase();
            const keyword = searchInput.value.trim().toLowerCase();
            const rows = table.querySelectorAll('tr');

            rows.forEach(row => {
                const nganhCell = row.querySelector('.nganh-cell');
                const nameCell = row.cells[2]; // cột Họ Tên là cột thứ 3 (index 2)
                const matchNganh = !nganh || (nganhCell && nganhCell.textContent.trim()
                    .toLowerCase() === nganh);
                const matchName = !keyword || (nameCell && nameCell.textContent.trim().toLowerCase()
                    .includes(keyword));

                if (matchNganh && matchName) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Xuất Excel
        btnExport.addEventListener('click', function(e) {
            e.preventDefault();
            let url = '{{ route("admin.sinhvien.export") }}';
            const nganh = select.value.trim();
            if (nganh) url += '?nganh=' + encodeURIComponent(nganh);
            window.location.href = url;
        });
    });
    </script>



    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('filterNganh');
        const btnExport = document.getElementById('btnExport');
        const table = document.querySelector('table.table-hover tbody');

        // Lọc table khi chọn ngành
        select.addEventListener('change', function() {
            const nganh = this.value.trim();
            const rows = table.querySelectorAll('tr');

            rows.forEach(row => {
                const cell = row.querySelector('.nganh-cell');
                if (!nganh || cell.textContent.trim() === nganh) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Xuất Excel
        btnExport.addEventListener('click', function(e) {
            e.preventDefault();
            let url = '{{ route("admin.sinhvien.export") }}';
            const nganh = select.value.trim();
            if (nganh) url += '?nganh=' + encodeURIComponent(nganh);
            window.location.href = url;
        });
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        function validateEmail(email) {
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return pattern.test(email);
        }

        function validatePhone(sdt) {
            return sdt === '' || /^\d{10}$/.test(sdt);
        }

        function showInlineError(input, message) {
            let errorDiv = input.parentNode.querySelector('.invalid-feedback');
            if (message) {
                input.classList.add('is-invalid');
                errorDiv.textContent = message;
            } else {
                input.classList.remove('is-invalid');
                errorDiv.textContent = '';
            }
        }


        function attachValidation(form) {
            const emailInput = form.querySelector('input[name="email"]'); // trong form riêng
            const sdtInput = form.querySelector('input[name="sdt"]'); // trong form riêng

            emailInput.addEventListener('input', function() {
                showInlineError(emailInput, validateEmail(emailInput.value) ? '' :
                    'Email không đúng định dạng.');
            });

            sdtInput.addEventListener('input', function() {
                showInlineError(sdtInput, validatePhone(sdtInput.value) ? '' :
                    'Số điện thoại phải đúng 10 chữ số.');
            });

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (!validateEmail(emailInput.value)) {
                    showInlineError(emailInput, 'Email không đúng định dạng.');
                    valid = false;
                }
                if (!validatePhone(sdtInput.value)) {
                    showInlineError(sdtInput, 'Số điện thoại phải đúng 10 chữ số.');
                    valid = false;
                }
                if (!valid) e.preventDefault(); // Ngăn submit nếu có lỗi
            });
        }


        // Áp dụng cho modal Add
        const addForm = document.querySelector('#modalAdd form');
        attachValidation(addForm);

        // Áp dụng cho tất cả modal Edit
        document.querySelectorAll('[id^="modalEdit"] form').forEach(form => attachValidation(form));

    });
    </script>



    <style>
    /* Hiệu ứng hover cho các nút hành động */
    /* Màu theme cho các nút hành động */
    /* Tinh chỉnh form trong modal */
    /* Màu chủ đạo */
    #searchName {
        border: 1px solid #d8e1e9;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    #searchName:focus {
        border-color: #4A7FA7;
        box-shadow: 0 0 4px rgba(74, 127, 167, 0.3);
    }

    .text-theme {
        color: #4A7FA7 !important;
    }

    .btn-theme {
        background-color: #4A7FA7;
        color: #fff;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-theme:hover {
        background-color: #3a6b8c;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    }

    .border-bottom {
        border-bottom: 2px solid #d8e1e9 !important;
    }

    .fw-bold {
        font-weight: 600 !important;
    }

    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .modal-content {
        font-family: 'Segoe UI', sans-serif;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #4A7FA7;
    }

    .form-label {
        font-size: 0.9rem;
    }

    .modal-lg {
        max-width: 800px;
        /* Modal to hơn */
    }

    .modal.fade .modal-dialog {
        transform: translate(0, -10%);
        transition: transform 0.25s ease-out;
    }

    .modal.fade.show .modal-dialog {
        transform: translate(0, 0);
    }

    .modal-content {
        border-radius: 0.5rem;
    }

    :root {
        --theme-color: #4A7FA7;
        --theme-hover: #3b6e93;
    }

    .bg-theme {
        background-color: var(--theme-color) !important;
    }

    .text-theme {
        color: var(--theme-color) !important;
    }

    .btn-theme {
        background-color: var(--theme-color);
        color: white;
        border: none;
        transition: 0.3s ease;
    }

    .btn-theme:hover {
        background-color: var(--theme-hover);
        box-shadow: 0 4px 12px rgba(74, 127, 167, 0.3);
    }

    .modal-content {
        font-family: 'Segoe UI', sans-serif;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #4A7FA7;
    }

    .form-label {
        font-size: 0.9rem;
    }

    .border-bottom {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .text-primary {
        color: #4A7FA7 !important;
    }

    .modal-lg {
        max-width: 750px;
    }

    /* Hiệu ứng mở modal mượt hơn */
    .modal.fade .modal-dialog {
        transform: translate(0, -10%);
        transition: transform 0.25s ease-out;
    }

    .modal.fade.show .modal-dialog {
        transform: translate(0, 0);
    }

    .modal-body div {
        font-size: 0.95rem;
    }

    :root {
        --view-color: #0d6efd;
        /* Bootstrap info */
        --view-hover: #0b5ed7;

        --edit-color: #ffc107;
        /* Bootstrap warning */
        --edit-hover: #e0a800;

        --delete-color: #dc3545;
        /* Bootstrap danger */
        --delete-hover: #bb2d3b;
    }

    /* Nút Xem */
    .btn-view {
        background-color: white;
        color: var(--view-color);
        border: 1px solid var(--view-color);
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        background-color: var(--view-color);
        color: white;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    /* Nút Sửa */
    .btn-edit {
        background-color: white;
        color: var(--edit-color);
        border: 1px solid var(--edit-color);
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        background-color: var(--edit-color);
        color: white;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }

    /* Nút Xóa */
    .btn-delete {
        background-color: white;
        color: var(--delete-color);
        border: 1px solid var(--delete-color);
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background-color: var(--delete-color);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }
    </style>
    <style>
    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .pagination .page-item.active .page-link {
        background-color: #4A7FA7;
        border-color: #4A7FA7;
        color: #fff;
    }

    .pagination .page-link {
        color: #4A7FA7;
    }

    .pagination .page-link:hover {
        background-color: #e8f0f6;
    }

    /* Ẩn mọi phần thông tin mô tả trong pagination */
    .pagination-info,
    .text-sm.text-gray-700.leading-5,
    .small.text-muted {
        display: none !important;
    }

    .table {
        table-layout: fixed;
        width: 100%;
    }

    .table th:nth-child(1) {
        width: 3%;
    }

    .table th:nth-child(2) {
        width: 6%;
    }

    .table th:nth-child(3) {
        width: 14%;
    }

    .table th:nth-child(4) {
        width: 6%;
    }

    .table th:nth-child(5) {
        width: 14%;
    }

    .table th:nth-child(6) {
        width: 20%;
    }

    .table th:nth-child(7) {
        width: 10%;
    }

    .table th:nth-child(8) {
        width: 10%;
    }

    .table td,
    .table th {
        white-space: nowrap;
    }

    .table thead th {
        font-size: 14px;
        font-weight: 600;
    }

    .table td {
        font-size: 14px;
    }
    </style>
    <input type="hidden" id="importError" value="{{ session('import_error') }}">

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var importError = document.getElementById("importError").value;
        if (importError) {
            Swal.fire({
                icon: 'warning',
                title: 'Lỗi import!',
                html: importError,
                confirmButtonColor: '#4A7FA7'
            });
        }
    });
    </script>




    @endsection