@extends('layouts.app')

@section('title', 'Báo cáo thực tập của tôi')

@section('content')
<div class="p-6 space-y-8">
    <!-- Đăng ký của tôi -->
    <section class="bg-white rounded-xl shadow p-6">




        <div class="container py-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-[#4A7FA7]"><i class=" bi bi-file-earmark-text me-2"></i>Báo cáo Thực tập của
                    tôi</h3>
                @if(!$baoCao && $dangKy)
                <button id="btnNopBaoCao" class="btn text-white fw-bold" style="background-color:#4a7fa7;">
                    <i class="bi bi-upload me-1"></i>Nộp Báo cáo
                </button>
                @endif
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header text-[#4A7FA7] fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Thông tin Báo cáo
                </div>
                <div class="card-body">
                    @if(!$dangKy)
                    <p class="text-danger mb-0 fw-bold">Bạn chưa đăng ký thực tập nên không thể nộp báo cáo.</p>
                    @elseif(!$baoCao)
                    <p class="text-muted mb-0">Bạn chưa nộp báo cáo nào.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="width: 200px;">Sinh viên</th>
                                    <th>Tiêu đề</th>
                                    <th style="width: 120px;">Ngày nộp</th>
                                    <th style="width: 120px;">Tệp đính kèm</th>
                                    <th style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>

                                    <td class="fw-semibold">
                                        {{ $baoCao->dangKyThucTap->sinhVien->ho_ten ?? '-' }}
                                    </td>

                                    <td style="max-width: 350px; white-space: normal;">
                                        {{ $baoCao->tieu_de }}
                                    </td>

                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($baoCao->ngay_nop)->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        @if($baoCao->file_baocao)
                                        <i class="bi bi-file-earmark-pdf text-danger"></i> Có
                                        @else
                                        <span class="text-muted">Không</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="btn btn-sm btn-info btn-view"
                                                data-id="{{ $baoCao->baocao_id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning btn-edit"
                                                data-id="{{ $baoCao->baocao_id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-delete"
                                                data-id="{{ $baoCao->baocao_id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= MODAL NỘP BÁO CÁO ================= -->
        <div class="modal fade" id="modalNopBaoCao" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <form id="formNopBaoCao" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header text-[#4A7FA7]">
                            <h5 class="modal-title fw-bold"><i class="bi bi-upload me-2"></i>Nộp Báo cáo</h5>
                            <button type="button" class="btn-close" style="filter: invert(70%) grayscale(100%);"
                                data-bs-dismiss="modal"></button>

                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tiêu đề báo cáo</label>
                                <input type="text" name="tieu_de" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nội dung</label>
                                <textarea name="noi_dung" rows="4" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">File báo cáo (PDF/DOC/DOCX)</label>
                                <input type="file" name="file_baocao" class="form-control" accept=".pdf,.doc,.docx"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn text-white px-4 fw-bold" style="background-color: #4a7fa7; border-color:
                        #4a7fa7;"><i class="bi bi-check-circle me-1"></i>Nộp</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                    class="bi bi-x-circle me-1"></i>Đóng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= MODAL CHI TIẾT ================= -->
        <div class="modal fade" id="modalChiTiet" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="modal-header bg-gradient text-[#4A7FA7]"
                        style="background: linear-gradient(135deg, #007bff, #6610f2);">
                        <h5 class="modal-title fw-bold ">
                            <i class="bi bi-file-earmark-text me-2"></i>Chi tiết Báo cáo Thực tập
                        </h5>
                        <button type="button" class="btn-close" style="filter: invert(70%) grayscale(100%);"
                            data-bs-dismiss="modal"></button>

                    </div>

                    <!-- Body -->
                    <div class="modal-body bg-light">
                        <div class="row g-4">
                            <!-- Thông tin chung -->
                            <div class="col-md-6 border-end border-2">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-person-circle text-primary fs-3 me-2"></i>
                                    <h5 class="mb-0 fw-bold text-primary" id="sv_name">---</h5>
                                </div>

                                <p class="mb-2"><i class="bi bi-briefcase-fill text-success me-2"></i>
                                    <strong>Vị trí thực tập:</strong> <span id="vitri_name">---</span>
                                </p>

                                <p class="mb-2"><i class="bi bi-building text-warning me-2"></i>
                                    <strong>Doanh nghiệp:</strong> <span id="dn_name">---</span>
                                </p>

                                <p class="mb-2"><i class="bi bi-mortarboard-fill text-info me-2"></i>
                                    <strong>Giảng viên hướng dẫn:</strong> <span id="gv_name">---</span>
                                </p>

                                <p class="mb-2"><i class="bi bi-calendar-check text-secondary me-2"></i>
                                    <strong>Ngày nộp:</strong> <span id="ngay_nop">---</span>
                                </p>

                                <p class="mb-2"><i class="bi bi-pencil-square text-danger me-2"></i>
                                    <strong>Tiêu đề:</strong> <span id="tieu_de">---</span>
                                </p>

                                <hr class="my-3">

                                <h6 class="fw-bold text-dark">
                                    <i class="bi bi-journal-text me-2"></i>Nội dung báo cáo
                                </h6>
                                <div class="p-3 bg-white rounded shadow-sm border" id="noi_dung"
                                    style="white-space: pre-line; min-height: 150px;"></div>
                            </div>

                            <!-- File đính kèm -->
                            <div class="col-md-6" id="file_section" style="display:none;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-danger mb-0">
                                        <i class="bi bi-file-earmark-pdf-fill me-2"></i>Tệp đính kèm
                                    </h6>
                                    <a id="file_download" href="#" target="_blank"
                                        class="btn btn-sm btn-outline-dark shadow-sm">
                                        <i class="bi bi-download me-1"></i>Tải File
                                    </a>
                                </div>
                                <iframe id="file_viewer" width="100%" height="600px" frameborder="0"
                                    class="border rounded shadow-sm bg-white"></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer bg-white">
                        <button class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- ================= MODAL SỬA ================= -->
        <div class="modal fade" id="modalSuaBaoCao" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <form id="formSuaBaoCao" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header  text-[#4A7FA7]">
                            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Sửa Báo cáo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_baocao_id" name="baocao_id">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tiêu đề</label>
                                <input type="text" id="edit_tieu_de" name="tieu_de" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nội dung</label>
                                <textarea id="edit_noi_dung" name="noi_dung" rows="4" class="form-control"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">File báo cáo mới (nếu muốn thay)</label>
                                <input type="file" name="file_baocao" class="form-control" accept=".pdf,.doc,.docx">
                                <a href="#" id="file_hien_tai" target="_blank"
                                    class="text-primary small d-block mt-1">Xem
                                    file hiện tại</a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn text-white px-4 fw-bold"
                                style="background-color: #4a7fa7; border-color: #4a7fa7;">
                                <i class="bi bi-save me-1"></i>Cập nhật
                            </button>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                    class="bi bi-x-circle me-1"></i>Đóng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= SCRIPTS ================= -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(function() {


        function showSwal(res, modalSelector = null) {
            // Nếu có modal, đóng modal
            if (modalSelector) {
                $(modalSelector).modal('hide');
            }

            if (res.status === 'success') {
                Swal.fire('Thành công', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Lỗi', res.message, 'error');
            }
        }

        // ================= Nộp báo cáo =================
        $('#btnNopBaoCao').click(() => $('#modalNopBaoCao').modal('show'));

        $('#formNopBaoCao').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('sinhvien.baocao.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: res => showSwal(res, '#modalNopBaoCao'),
                error: err => Swal.fire('Lỗi', err.responseJSON?.message || 'Có lỗi xảy ra',
                    'error')
            });
        });

        // ================= Xem chi tiết báo cáo =================
        $(document).on('click', '.btn-view', function() {
            const id = $(this).data('id');
            $.get(`/sinhvien/baocao/${id}`, function(t) {
                const dk = t.dang_ky_thuc_tap;

                $('#sv_name').text(dk?.sinh_vien?.ho_ten || '---');
                $('#vitri_name').text(dk?.vi_tri_thuc_tap?.ten_vitri || '---');
                $('#dn_name').text(dk?.vi_tri_thuc_tap?.doanh_nghiep?.ten_dn || '---');

                const pc = dk?.phan_cong_giang_viens?. [0];
                $('#gv_name').text(pc?.giang_vien?.ho_ten || '---');

                if (t.ngay_nop) {
                    const parts = t.ngay_nop.split(' ')[0].split('-');
                    $('#ngay_nop').text(`${parts[2]}/${parts[1]}/${parts[0]}`);
                } else {
                    $('#ngay_nop').text('---');
                }

                $('#tieu_de').text(t.tieu_de || '---');
                $('#noi_dung').text(t.noi_dung || '---');

                if (t.file_baocao) {
                    const fileUrl = `{{ asset('storage') }}/${t.file_baocao}`;
                    $('#file_viewer').attr('src', fileUrl);
                    $('#file_download').attr('href', fileUrl);
                    $('#file_section').show();
                } else {
                    $('#file_section').hide();
                }

                $('#modalChiTiet').modal('show');
            });
        });

        // ================= Sửa báo cáo =================
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            $.get(`/sinhvien/baocao/${id}`, function(t) {
                $('#edit_baocao_id').val(t.baocao_id);
                $('#edit_tieu_de').val(t.tieu_de);
                $('#edit_noi_dung').val(t.noi_dung);
                $('#file_hien_tai').attr('href', `{{ asset('storage') }}/${t.file_baocao}`);
                $('#modalSuaBaoCao').modal('show');
            });
        });

        $('#formSuaBaoCao').submit(function(e) {
            e.preventDefault();
            const id = $('#edit_baocao_id').val();
            let formData = new FormData(this);
            $.ajax({
                url: `/sinhvien/baocao/${id}`,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: res => showSwal(res, '#modalSuaBaoCao'),
                error: err => Swal.fire('Lỗi', err.responseJSON?.message || 'Có lỗi xảy ra',
                    'error')
            });
        });

        // ================= Xóa báo cáo =================
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Xóa báo cáo?',
                text: 'Hành động này không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/sinhvien/baocao/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: showSwal,
                        error: err => Swal.fire('Lỗi', err.responseJSON?.message ||
                            'Có lỗi xảy ra', 'error')
                    });
                }
            });
        });

    });
    </script>


    </body>
    @endsection