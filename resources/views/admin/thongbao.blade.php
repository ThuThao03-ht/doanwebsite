@extends('layouts.admin')

@section('head')
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Title -->
        <h3 class="fw-bold mb-0" style="color:#4a7fa7;">
            <i class="bi bi-bell-fill"></i> Quản lý thông báo
        </h3>

        <div class="d-flex align-items-center gap-2">
            <form method="GET" action="{{ route('admin.thongbao.index') }}" class="d-flex gap-2 mb-0">

                <select name="doi_tuong" class="form-select w-auto">
                    <option value="all" {{ request('doi_tuong')=='all' ? 'selected' : '' }}>Tất cả</option>
                    <option value="sinhvien" {{ request('doi_tuong')=='sinhvien' ? 'selected' : '' }}>Sinh viên</option>
                    <option value="giangvien" {{ request('doi_tuong')=='giangvien' ? 'selected' : '' }}>Giảng viên
                    </option>
                    <option value="doanhnghiep" {{ request('doi_tuong')=='doanhnghiep' ? 'selected' : '' }}>Doanh nghiệp
                    </option>
                </select>

                <button class="btn text-white" style="background-color:#4a7fa7;">
                    <i class="bi bi-funnel-fill"></i> Lọc
                </button>
            </form>

            <button type="button" class="btn text-white" style="background-color:#4a7fa7;" data-bs-toggle="modal"
                data-bs-target="#modalCreate">
                <i class="bi bi-plus-circle"></i> Tạo thông báo
            </button>
        </div>
    </div>


    <hr class="mt-2 mb-3" style="border-top: 1px solid #c3d0df;">




    <!-- Danh sách thông báo -->
    <div class="card shadow-sm">
        <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
            <i class="bi bi-list-ul me-2"></i> Danh sách phân công giảng viên
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="white-space: nowrap; vertical-align: middle;">STT</th>
                    <th style="white-space: nowrap; vertical-align: middle;">Tiêu đề</th>
                    <th style="white-space: nowrap; vertical-align: middle;">Nội dung</th>
                    <th style="white-space: nowrap; vertical-align: middle;">Đối tượng</th>
                    <th style="white-space: nowrap; vertical-align: middle;">Ngày gửi</th>
                    <th style="white-space: nowrap; vertical-align: middle;">Người gửi</th>
                    <th style="white-space: nowrap; vertical-align: middle;">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach($thongbaos as $key => $tb)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $tb->tieude }}</td>
                    <td>{{ $tb->noidung }}</td>
                    <td>{{ $tb->doi_tuong }}</td>
                    <td>{{ $tb->ngay_gui->format('d/m/Y') }}</td>
                    <td>{{ $tb->nguoiGui ? $tb->nguoiGui->username : 'N/A' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Sửa -->
                            <button class="btn btn-sm text-white" style="background-color:#4a7fa7;"
                                data-bs-toggle="modal" data-bs-target="#modalEdit{{ $tb->tb_id }}" title="Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Xóa -->
                            <form class="delete-form m-0 p-0" action="{{ route('admin.thongbao.destroy', $tb->tb_id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>

                <!-- Modal sửa -->
                <div class="modal fade" id="modalEdit{{ $tb->tb_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.thongbao.update', $tb->tb_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#4a7fa7; color:#fff;">
                                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Sửa thông báo</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Tiêu đề</label>
                                        <input type="text" name="tieude" class="form-control" value="{{ $tb->tieude }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Nội dung</label>
                                        <textarea name="noidung" class="form-control"
                                            required>{{ $tb->noidung }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Đối tượng</label>
                                        <select name="doi_tuong" class="form-select" required>
                                            <option value="tat_ca" {{ $tb->doi_tuong=='tat_ca' ? 'selected' : '' }}>Tất
                                                cả
                                            </option>
                                            <option value="sinhvien" {{ $tb->doi_tuong=='sinhvien' ? 'selected' : '' }}>
                                                Sinh
                                                viên</option>
                                            <option value="giangvien"
                                                {{ $tb->doi_tuong=='giangvien' ? 'selected' : '' }}>
                                                Giảng viên</option>
                                            <option value="doanhnghiep"
                                                {{ $tb->doi_tuong=='doanhnghiep' ? 'selected' : '' }}>Doanh nghiệp
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn" style="background-color:#4a7fa7; color:#fff;">
                                        <i class="bi bi-save"></i> Lưu
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>


        <div class="d-flex justify-content-center mt-3">
            {{  $thongbaos->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <!-- Modal tạo thông báo -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.thongbao.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#4a7fa7; color:#fff;">
                        <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Tạo thông báo</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tiêu đề</label>
                            <input type="text" name="tieude" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nội dung</label>
                            <textarea name="noidung" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Đối tượng</label>
                            <select name="doi_tuong" class="form-select" required>
                                <option value="tat_ca">Tất cả</option>
                                <option value="sinhvien">Sinh viên</option>
                                <option value="giangvien">Giảng viên</option>
                                <option value="doanhnghiep">Doanh nghiệp</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn" style="background-color:#4a7fa7; color:#fff;">
                            <i class="bi bi-check-circle"></i> Tạo
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hiển thị thông báo sweetalert
        const successMessage = "{{ session('success') }}";
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: successMessage,
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Xác nhận xóa
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa?',
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
    </script>
    <style>
    /* Ẩn mọi phần thông tin mô tả trong pagination */
    .pagination-info,
    .text-sm.text-gray-700.leading-5,
    .small.text-muted {
        display: none !important;
    }
    </style>

    @endsection