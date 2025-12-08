@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center justify-content-between p-3 rounded">
    <h3 class="mb-0 fw-bold" style="color: #4a7fa7">
        <i class="bi bi-card-checklist me-2"></i> Quản lý Tiến độ Thực tập
    </h3>


    {{-- Form lọc và nút làm mới --}}
    <form method="GET" class="d-flex align-items-center gap-2">
        <input type="text" name="ten_sinh_vien" class="form-control" placeholder="Tìm theo tên sinh viên......"
            value="{{ request('ten_sinh_vien') }}" style="min-width: 200px;">

        <button class="btn text-nowrap text-white" type="submit" style="background-color: #4a7fa7; border: none;">
            <i class="bi bi-search me-1"></i> Tìm kiếm
        </button>

        <a href="{{ route('admin.tiendo.index') }}" class="btn btn-outline-secondary text-nowrap">
            <i class="bi bi-arrow-repeat me-1"></i> Làm mới
        </a>
    </form>

</div>


<hr class="mt-2 mb-3" style="border-top: 1px solid #c3d0df;">



<div class="card shadow-sm">
    <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
        <i class="bi bi-list-ul me-2"></i> Danh sách tiến độ
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr class="text-center">
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
                @foreach($tiendos as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $t->dangKyThucTap->sinhVien->ho_ten ?? '-' }}</td>
                    <td>{{ $t->dangKyThucTap->viTriThucTap->ten_vitri ?? '-' }}</td>
                    <td>{{ Str::limit($t->noi_dung, 50) }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($t->ngay_capnhat)->format('d-m-Y') }}
                    </td>

                    <td class="text-center">
                        @if($t->file_dinhkem)
                        <i class="bi bi-file-earmark-pdf text-danger"></i> Có
                        @else
                        <span class="text-muted">Không</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm text-white btn-view" data-id="{{ $t->tiendo_id }}"
                            style="background-color: #4a7fa7; border: none;">
                            <i class="bi bi-eye"></i> Xem
                        </button>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

{{-- Modal xem chi tiết --}}
<div class="modal fade" id="modalChiTiet" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header" style="background-color: #4A7FA7; color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Chi tiết Tiến độ Thực tập
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    {{-- Cột trái: thông tin chi tiết --}}
                    <div class="col-md-6 border-end">
                        <div class="mb-3 p-2 bg-light rounded shadow-sm">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-person-circle me-2"></i>
                                <span id="sv_name"></span>
                            </h5>

                            <p class="mb-2">
                                <i class="bi bi-building me-2 text-success"></i>
                                <strong>Vị trí thực tập:</strong> <span id="vitri_name"></span>
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-calendar-check me-2 text-info"></i>
                                <strong>Ngày cập nhật:</strong> <span id="ngay_capnhat"></span>
                            </p>
                        </div>

                        <hr class="my-3">

                        <div class="mb-3 p-2 border rounded bg-white shadow-sm">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-journal-text me-2 text-warning"></i>Nội dung tiến độ
                            </h6>
                            <p id="noi_dung" style="white-space: pre-line;"></p>
                        </div>
                    </div>

                    {{-- Cột phải: hiển thị file PDF --}}
                    <div class="col-md-6" id="file_section" style="display:none;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0">
                                <i class="bi bi-file-earmark-pdf-fill me-2 text-danger"></i>
                                Tệp đính kèm
                            </h6>
                            <a id="file_download" href="#" class="btn btn-sm btn-secondary" target="_blank">
                                <i class="bi bi-download me-1"></i> Tải xuống PDF
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

{{-- Script xử lý AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $(document).on('click', '.btn-view', function() {
        let id = $(this).data('id');
        $.ajax({
            url: `/admin/tiendo/${id}`,
            type: 'GET',
            success: function(data) {
                // Cập nhật thông tin tiến độ
                $('#sv_name').text(data.sinh_vien ?? '-');
                $('#vitri_name').text(data.vi_tri ?? '-');
                $('#ngay_capnhat').text(data.ngay_capnhat ?? '-');
                $('#noi_dung').text(data.noi_dung ?? '-');

                // Xử lý file PDF
                if (data.file_url) {
                    $('#file_viewer').attr('src', data.file_url); // hiển thị PDF
                    $('#file_download').attr('href', data.file_url); // gán link download
                    $('#file_section').show(); // hiện cột PDF
                } else {
                    $('#file_section').hide(); // ẩn cột PDF nếu không có file
                }

                // Hiển thị modal
                $('#modalChiTiet').modal('show');
            },
            error: function() {
                alert('Không thể tải dữ liệu tiến độ!');
            }
        });
    });
});
</script>
<style>
/* Giảm chiều rộng modal-xl đi chút */
#modalChiTiet .modal-dialog {
    max-width: 1100px;
    /* mặc định modal-xl ~1140px */
    margin: 1.75rem auto;
    /* khoảng cách top/bottom */
}



/* Cột PDF chiếm vừa đủ */
#modalChiTiet #file_section iframe {
    height: 500px;
    /* giảm từ 600px */
}
</style>

@endsection