@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <h3 class="fw-bold d-flex align-items-center gap-2" style="color:#4a7fa7;">
            <i class="bi bi-building-check fs-2"></i> Quản lý đánh giá doanh nghiệp
        </h3>



    </div>

    <hr class="my-3 border-2 opacity-50">
    <form method="GET" action="{{ route('admin.doanhnghiepdanhgia.index') }}" class="d-flex align-items-center gap-2">

        <input type="text" name="sinhvien" class="form-control rounded-pill" placeholder="Tên sinh viên"
            value="{{ request('sinhvien') }}" style="width: 180px;">

        <select name="doanhnghiep" class="form-select rounded-pill" style="width: 220px;">
            <option value="">-- Chọn doanh nghiệp --</option>
            @foreach($dsDoanhNghiep as $ten)
            <option value="{{ $ten }}" {{ request('doanhnghiep') == $ten ? 'selected' : '' }}>
                {{ $ten }}
            </option>
            @endforeach
        </select>

        <button class="btn text-white rounded-pill px-3" style="background-color:#4a7fa7;">
            <i class="bi bi-search"></i> Tìm
        </button>

        <a href="{{ route('admin.doanhnghiepdanhgia.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </a>
    </form>
    <br>
    <div class="card shadow-sm">
        <div class="card-header text-white fw-bold" style="background-color:#4a7fa7;">
            <i class="bi bi-list-ul me-2"></i> Danh sách đánh giá doanh nghiệp
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Tên doanh nghiệp</th>
                        <th>Tên sinh viên</th>

                        <th>Điểm số</th>
                        <th>Ngày đánh giá</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($danhgias as $index => $dg)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $dg->doanhNghiep->ten_dn ?? 'N/A' }}</td>
                        <td>{{ $dg->dangKyThucTap->sinhVien->ho_ten ?? 'N/A' }}</td>

                        <td class="text-center">{{ $dg->diemso }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($dg->ngay_danhgia)->format('d-m-Y') }}</td>
                        <td class="text-center">
                            <button class="btn btn-info btn-sm btn-view" data-id="{{ $dg->dg_dn_id }}">
                                Xem chi tiết
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Không có dữ liệu đánh giá.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $danhgias->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!--  Modal xem chi tiết -->
<div class="modal fade" id="modalChiTiet" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header" style="background-color:#4a7fa7; color:white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-eye"></i> Chi tiết đánh giá doanh nghiệp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="bi bi-building me-2 text-info"></i>
                        <strong>Doanh nghiệp:</strong> <span id="ct_doanhnghiep"></span>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-person me-2 text-success"></i>
                        <strong>Sinh viên:</strong> <span id="ct_sinhvien"></span>
                    </li>

                    <li class="list-group-item">
                        <i class="bi bi-briefcase me-2 text-secondary"></i>
                        <strong>Vị trí thực tập:</strong> <span id="ct_vitri"></span>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-star me-2 text-warning"></i>
                        <strong>Điểm số:</strong> <span id="ct_diemso"></span>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-chat-dots me-2 text-secondary"></i>
                        <strong>Nhận xét:</strong> <span id="ct_nhanxet"></span>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-calendar-date me-2 text-danger"></i>
                        <strong>Ngày đánh giá:</strong> <span id="ct_ngay"></span>
                    </li>
                </ul>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Đóng
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('modalChiTiet'));

    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;

            fetch(`{{ url('admin/doanhnghiepdanhgia') }}/${id}`)
                .then(response => response.json())
                .then(data => {
                    //  Hiển thị dữ liệu chi tiết
                    document.getElementById('ct_doanhnghiep').textContent = data
                        .doanh_nghiep?.ten_dn ?? 'Chưa có';
                    document.getElementById('ct_sinhvien').textContent = data
                        .dang_ky_thuc_tap?.sinh_vien?.ho_ten ?? 'Chưa có';

                    document.getElementById('ct_vitri').textContent = data.dang_ky_thuc_tap
                        ?.vi_tri_thuc_tap?.ten_vitri ?? 'Chưa có';
                    document.getElementById('ct_diemso').textContent = data.diemso ?? '—';
                    document.getElementById('ct_nhanxet').textContent = data.nhanxet ?? '—';
                    document.getElementById('ct_ngay').textContent = data.ngay_danhgia ?
                        new Date(data.ngay_danhgia).toLocaleDateString('vi-VN') :
                        '—';

                    modal.show();
                })
                .catch(err => console.error('Lỗi khi tải dữ liệu:', err));
        });
    });
});
</script>
@endsection