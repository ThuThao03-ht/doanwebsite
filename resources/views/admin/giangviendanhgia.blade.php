@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold" style="color: #4a7fa7">
        <i class="bi bi-list-check me-2"></i> Quản lý Giảng viên đánh giá
    </h3>
</div>
<hr class="my-3 border-2 opacity-50">

{{-- Form lọc trên 1 dòng --}}
<form method="GET" class="d-flex align-items-center mb-3 gap-2">
    <input type="text" name="ten_gv" value="{{ request('ten_gv') }}" class="form-control" placeholder="Tên giảng viên"
        style="max-width: 250px;">
    <input type="text" name="ten_sv" value="{{ request('ten_sv') }}" class="form-control" placeholder="Tên sinh viên"
        style="max-width: 250px;">
    <button type="submit" class="btn btn-primary" style="background-color: #4a7fa7;">
        <i class="bi bi-search"></i> Tìm</button>
    <a href="{{ route('admin.giangviendanhgia.index') }}" class="btn " style=" background-color: #ffffff; color: black;
        border: 1px solid black; white-space: nowrap;"> <i class="bi bi-arrow-clockwise me-1"></i> Làm mới</a>
</form>
{{-- Bảng đánh giá --}}
<div class="card shadow-sm">
    <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
        <i class="bi bi-list-ul me-2"></i> Danh sách đánh giá của giảng viên
    </div>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Giảng viên</th>
                <th>Sinh viên</th>
                <th>Điểm số</th>
                <th>Ngày đánh giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($danhGias as $dg)
            <tr>
                <td>{{ $dg->dg_id }}</td>
                <td>{{ $dg->giangVien->ho_ten ?? '' }}</td>
                <td>{{ $dg->dangKyThucTap->sinhVien->ho_ten ?? '' }}</td>
                <td>{{ $dg->diemso }}</td>
                <td>{{ $dg->ngay_danhgia->format('d-m-Y') }}</td>
                <td>
                    <button class="btn btn-info btn-sm btn-view" data-id="{{ $dg->dg_id }}" data-bs-toggle="modal"
                        data-bs-target="#modalChiTiet">
                        Xem chi tiết
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $danhGias->links() }}
</div>

{{-- Modal chi tiết --}}
<div class="modal fade" id="modalChiTiet" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header" style="background-color: #4a7fa7;">
                <h5 class="modal-title fw-bold" style="color: #ffffff;">
                    <i class="bi bi-eye-fill me-2"></i> Chi tiết đánh giá
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th><i class="bi bi-person-badge me-1"></i> Giảng viên:</th>
                        <td class="border-bottom py-2" id="modal_gv"></td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-person me-1"></i> Sinh viên:</th>
                        <td class="border-bottom py-2" id="modal_sv"></td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-briefcase me-1"></i> Vị trí thực tập:</th>
                        <td class="border-bottom py-2" id="modal_vitri"></td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-star me-1"></i> Điểm số:</th>
                        <td class="border-bottom py-2" id="modal_diem"></td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-chat-left-text me-1"></i> Nhận xét:</th>
                        <td class="border-bottom py-2" id="modal_nhanxet"></td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-calendar-event me-1"></i> Ngày đánh giá:</th>
                        <td class="py-2" id="modal_ngay"></td>
                    </tr>
                </table>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary ms-auto" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Đóng
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-view');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`{{ url('admin/giangviendanhgia') }}/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modal_gv').textContent = data.giang_vien
                        .ho_ten;
                    document.getElementById('modal_sv').textContent = data
                        .dang_ky_thuc_tap
                        .sinh_vien.ho_ten;
                    document.getElementById('modal_vitri').textContent = data
                        .dang_ky_thuc_tap.vi_tri_thuc_tap.ten_vitri;
                    document.getElementById('modal_diem').textContent = data.diemso;
                    document.getElementById('modal_nhanxet').textContent = data.nhanxet;
                    // Lấy chỉ phần ngày, bỏ T00:00:00.000000Z
                    document.getElementById('modal_ngay').textContent = data.ngay_danhgia
                        .split('T')[0];
                });
        });
    });
});
</script>
@endsection