@extends('layouts.app')

@section('title', 'Tiến độ Thực tập của tôi')

@section('content')

<div class="container py-4">
    <section class="bg-white rounded-xl shadow p-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="fw-bold text-[#4A7FA7] mb-0">
                <i class="bi bi-clock-history me-2"></i> Tiến độ Thực tập của tôi
            </h3>

            @if($dangKy)
            <button id="btnThemTienDo" class="btn text-white fw-bold" style="background-color:#4a7fa7;">
                <i class="bi bi-plus-circle me-1"></i> Thêm tiến độ
            </button>
            @endif
        </div>

        @if(!$dangKy)
        <div class="alert alert-info text-center p-4 mt-3">
            <h5 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Bạn chưa đăng ký vị trí thực tập</h5>
            <p class="mb-3">Vui lòng <a href="{{ route('sinhvien.vitri_sinhvien.list') }}"
                    class="fw-bold text-decoration-none text-primary">đăng ký vị trí thực tập</a> để có thể theo dõi và
                cập nhật tiến độ.</p>
            <a href="{{ route('sinhvien.vitri_sinhvien.list') }}" class="btn text-white fw-bold"
                style="background-color:#4a7fa7;">
                <i class="bi bi-pencil-square me-1"></i> Đăng ký ngay
            </a>
        </div>
        @else
        <div class="card shadow-sm border-0">
            <div class="card-header text-[#4A7FA7] fw-bold">
                <i class="bi bi-list-ul me-2"></i> Danh sách tiến độ
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Sinh viên</th>
                                <th>Vị trí thực tập</th>
                                <th>Nội dung</th>
                                <th>Ngày cập nhật</th>
                                <th>Tệp đính kèm</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tienDos as $index => $t)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $t->dangKyThucTap->sinhVien->ho_ten ?? '-' }}</td>
                                <td>{{ $t->dangKyThucTap->viTriThucTap->ten_vitri ?? '-' }}</td>
                                <td>{{ Str::limit($t->noi_dung, 60) }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($t->ngay_capnhat)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    @if($t->file_dinhkem)
                                    <i class="bi bi-file-earmark-pdf text-danger"></i> Có
                                    @else
                                    <span class="text-muted">Không</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <button class="btn btn-sm btn-info text-white btn-view"
                                            data-id="{{ $t->tiendo_id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $t->tiendo_id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $t->tiendo_id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Chưa có tiến độ nào được thêm.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </section>


    <!-- ======================= MODAL: THÊM ======================= -->
    <div class="modal fade" id="modalThemTienDo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form id="formThemTienDo" method="POST" action="{{ route('sinhvien.tiendo.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header  text-[#4A7FA7]">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Thêm Tiến độ</h5>
                        <button type="button" class="btn-close" style="filter: invert(70%) grayscale(100%);"
                            data-bs-dismiss="modal"></button>

                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung tiến độ</label>
                            <textarea name="noi_dung" rows="4" class="form-control" placeholder="Nhập nội dung..."
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File PDF đính kèm (tùy chọn)</label>
                            <input type="file" name="file_dinhkem" class="form-control" accept=".pdf">
                            <small class="text-muted">Chỉ chấp nhận PDF, tối đa 20MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn text-white px-4 fw-bold" style="background-color: #4a7fa7; border-color:
                        #4a7fa7;"><i class="bi bi-check-circle me-1"></i> Lưu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Đóng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ======================= MODAL: SỬA ======================= -->
    <div class="modal fade" id="modalSuaTienDo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form id="formSuaTienDo" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header text-[#4A7FA7]">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Sửa Tiến độ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_tiendo_id" name="tiendo_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung</label>
                            <textarea id="edit_noi_dung" name="noi_dung" rows="4" class="form-control"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File PDF đính kèm</label>
                            <input type="file" name="file_dinhkem" class="form-control" accept=".pdf">
                            <a href="#" id="link_file_hien_tai" target="_blank"
                                class="text-primary small d-block mt-1">Xem
                                file hiện tại</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn text-white px-4 fw-bold" style="background-color: #4a7fa7; border-color:
                        #4a7fa7;"> <i class="bi bi-save me-1"></i> Cập nhật</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Đóng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ======================= MODAL: XEM CHI TIẾT ======================= -->
    <div class="modal fade" id="modalChiTietTienDo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <div class="modal-header  text-[#4A7FA7]">
                    <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Chi tiết Tiến độ</h5>
                    <button type="button" class="btn-close" style="filter: invert(70%) grayscale(100%);"
                        data-bs-dismiss="modal"></button>

                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6 border-end">
                            <div class="mb-3 p-2 bg-light rounded shadow-sm">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-person-circle me-2"></i><span id="sv_name">---</span>
                                </h5>
                                <p><i class="bi bi-building text-success me-2"></i><strong>Vị trí:</strong> <span
                                        id="vitri_name">---</span></p>
                                <p><i class="bi bi-calendar-check text-info me-2"></i><strong>Ngày cập nhật:</strong>
                                    <span id="ngay_capnhat">---</span>
                                </p>
                            </div>
                            <div class="p-3 border rounded bg-white shadow-sm">
                                <h6 class="fw-bold text-dark"><i class="bi bi-journal-text me-2"></i>Nội dung</h6>
                                <p id="noi_dung" style="white-space: pre-line;"></p>
                            </div>
                        </div>

                        <div class="col-md-6" id="file_section" style="display:none;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-bold text-danger"><i class="bi bi-file-earmark-pdf-fill me-2"></i>Tệp đính
                                    kèm</h6>
                                <a id="file_download" href="#" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>Tải PDF
                                </a>
                            </div>
                            <iframe id="file_viewer" width="100%" height="600px" frameborder="0"
                                class="border rounded shadow-sm"></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(function() {
        $('#btnThemTienDo').click(() => $('#modalThemTienDo').modal('show'));

        $(document).on('click', '.btn-view', function() {
            let id = $(this).data('id');
            $.get(`/sinhvien/tiendo/${id}`, function(res) {
                const t = res.tienDo;
                $('#sv_name').text(t.dang_ky_thuc_tap?.sinh_vien?.ho_ten ?? '---');
                $('#vitri_name').text(t.dang_ky_thuc_tap?.vi_tri_thuc_tap?.ten_vitri ?? '---');

                if (t.ngay_capnhat) {
                    const parts = t.ngay_capnhat.split(' ')[0].split(
                        '-'); // tách lấy phần ngày (YYYY-MM-DD)
                    const formatted =
                        `${parts[2]}/${parts[1]}/${parts[0]}`; // đổi sang DD/MM/YYYY
                    $('#ngay_capnhat').text(formatted);
                } else {
                    $('#ngay_capnhat').text('---');
                }


                $('#noi_dung').text(t.noi_dung ?? '---');

                if (t.file_dinhkem) {
                    $('#file_viewer').attr('src', `/sinhvien/tiendo/${t.tiendo_id}/file`);
                    $('#file_download').attr('href',
                        `/sinhvien/tiendo/${t.tiendo_id}/download`);
                    $('#file_section').show();
                } else $('#file_section').hide();

                $('#modalChiTietTienDo').modal('show');
            });
        });

        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.get(`/sinhvien/tiendo/${id}`, function(res) {
                const t = res.tienDo;
                $('#edit_tiendo_id').val(t.tiendo_id);
                $('#edit_noi_dung').val(t.noi_dung);
                $('#link_file_hien_tai').attr('href', `/sinhvien/tiendo/${t.tiendo_id}/file`);
                $('#formSuaTienDo').attr('action', `/sinhvien/tiendo/${t.tiendo_id}`);
                $('#modalSuaTienDo').modal('show');
            });
        });

        const success = "{{ session('success') }}";
        const error = "{{ session('error') }}";
        if (success) Swal.fire('Thành công', success, 'success');
        if (error) Swal.fire('Lỗi', error, 'error');
    });
    //  Xử lý xóa tiến độ
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Bạn có chắc muốn xóa?',
            text: 'Tiến độ này sẽ bị xóa khỏi danh sách!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/sinhvien/tiendo/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        Swal.fire('Đã xóa!', res.success, 'success').then(() => location
                            .reload());
                    },
                    error: function(err) {
                        Swal.fire('Lỗi!', err.responseJSON?.error ||
                            'Không thể xóa tiến độ!', 'error');
                    }
                });
            }
        });
    });
    </script>

    <style>
    #modalChiTietTienDo .modal-dialog {
        max-width: 1100px;
    }

    #file_section iframe {
        height: 500px;
    }
    </style>

    </body>
    @endsection