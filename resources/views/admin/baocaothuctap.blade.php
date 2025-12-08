@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center justify-content-between p-3 rounded">
    <h3 class="mb-0 fw-bold " style="color: #4a7fa7">
        <i class="bi bi-file-earmark-text me-2"></i> Quản lý Báo cáo Thực tập
    </h3>

    {{-- Form lọc và nút làm mới --}}
    <form method="GET" class="d-flex align-items-center gap-2">
        <input type="text" name="ten_sv" class="form-control" placeholder="Tìm theo tên sinh viên"
            value="{{ request('ten_sv') }}" style="min-width: 200px;">

        <button class="btn text-nowrap text-white" type="submit" style="background-color: #4a7fa7; border: none;">
            <i class="bi bi-search me-1"></i> Tìm kiếm
        </button>

        <a href="{{ route('admin.baocaothuctap.index') }}" class="btn btn-outline-secondary text-nowrap">
            <i class="bi bi-arrow-repeat me-1"></i> Làm mới
        </a>
    </form>
</div>

<hr class="mt-2 mb-3" style="border-top: 1px solid #c3d0df;">


{{-- Bảng danh sách --}}
<div class="card shadow-sm">
    <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
        <i class="bi bi-list-ul me-2"></i> Danh sách tiến độ
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Sinh viên</th>
                    <th>Tiêu đề</th>
                    <th>Ngày nộp</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($baocao as $item)
                <tr>
                    <td>{{ $loop->iteration + ($baocao->currentPage()-1) * $baocao->perPage() }}</td>
                    <td>{{ $item->dangKyThucTap->sinhVien->ho_ten ?? '-' }}</td>
                    <td>{{ $item->tieu_de }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->ngay_nop)->format('d-m-Y') }}</td>

                    <td class="text-center">
                        {{-- Xem chi tiết --}}
                        <button class="btn btn-sm btn-info btn-detail" data-id="{{ $item->baocao_id }}"
                            title="Xem chi tiết">
                            <i class="bi bi-eye"></i>
                        </button>

                        {{-- Xem file --}}
                        <a href="{{ route('admin.baocaothuctap.viewFile', $item->baocao_id) }}" target="_blank"
                            class="btn btn-sm btn-success" title="Xem file">
                            <i class="bi bi-file-earmark-text"></i>
                        </a>

                        {{-- Tải về --}}
                        <a href="{{ route('admin.baocaothuctap.downloadFile', $item->baocao_id) }}"
                            class="btn btn-sm btn-primary" title="Tải về">
                            <i class="bi bi-download"></i>
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $baocao->links() }}
    </div>
</div>

{{-- Modal xem chi tiết --}}
<div class="modal fade" id="modalChiTiet" tabindex="-1">
    <div class="modal-dialog modal-mb modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <!-- Header -->
            <div class="modal-header" style="background-color: #4a7fa7; color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-earmark-text me-2"></i>Chi tiết báo cáo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <p><i class="bi bi-person-circle me-1"></i><strong>Sinh viên:</strong> <span id="sv_name"></span>
                </p>
                <hr>
                <p><i class="bi bi-card-text me-1"></i><strong>Tiêu đề:</strong> <span id="tieu_de"></span></p>
                <hr>
                <p><i class="bi bi-journal-text me-1"></i><strong>Nội dung:</strong></p>
                <div class="border rounded p-2 mb-2" style="background-color: #f8f9fa;">
                    <p id="noi_dung" class="mb-0"></p>
                </div>
                <hr>
                <p><i class="bi bi-calendar-check me-1"></i><strong>Ngày nộp:</strong> <span id="ngay_nop"></span>
                </p>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Đóng
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

    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`{{ url('admin/baocaothuctap') }}/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('sv_name').textContent = data
                        .dang_ky_thuc_tap
                        .sinh_vien.ho_ten ?? '-';
                    document.getElementById('tieu_de').textContent = data.tieu_de ??
                        '-';
                    document.getElementById('noi_dung').textContent = data.noi_dung ??
                        '-';
                    document.getElementById('ngay_nop').textContent = data.ngay_nop ??
                        '-';
                    modal.show();
                });
        });
    });
});
</script>
@endsection