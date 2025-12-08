@extends('layouts.admin')

@section('content')
<meta name="success-message" content="{{ session('success') }}">
<meta name="error-message" content="{{ session('error') }}">

<div class="container mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <!-- Tiêu đề -->
        <h3 class="fw-bold d-flex align-items-center gap-2 mb-2 mb-md-0" style="color: #4a7fa7;">
            <i class="bi bi-person-video2 fs-2"></i>
            Quản lý phân công giảng viên
        </h3>

        <!-- Ô lọc -->
        <form method="GET" action="{{ route('admin.phanconggiangvien.index') }}"
            class="d-flex align-items-center gap-2">
            <input type="text" name="giangvien" class="form-control rounded-pill" placeholder="Nhập tên giảng viên"
                value="{{ request('giangvien') }}" style="width: 220px;">
            <button class="btn text-white rounded-pill px-3 d-flex align-items-center gap-2"
                style="background-color: #4a7fa7;">
                <i class="bi bi-search"></i> Tìm
            </button><a href="{{ route('admin.phanconggiangvien.index') }}" class="btn " style=" background-color:
                #ffffff; color: black; border: 1px solid black; white-space: nowrap;"> <i
                    class="bi bi-arrow-clockwise me-1"></i> Làm mới</a>
        </form>
    </div>

    <hr class="mt-2 mb-3" style="border-top: 1px solid #c3d0df;">
    <!-- Bảng -->
    <div class="card">
        <div class="card shadow-sm">
            <div class="card-header text-white fw-bold" style="background-color: #4A7FA7;">
                <i class="bi bi-list-ul me-2"></i> Danh sách phân công giảng viên
            </div>
            <div class="card-body table-responsive">

                <table class="table table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>ID</th>
                            <th>Tên sinh viên</th>
                            <th>Giảng viên hướng dẫn</th>
                            <th>Ngày phân công</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($phancongs as $pc)
                        <tr>
                            <td>{{ $pc->pc_id }}</td>
                            <td>{{ $pc->dangKyThucTap->sinhVien->ho_ten ?? 'N/A' }}</td>
                            <td>
                                @if($pc->giangVien)
                                {{ $pc->giangVien->ho_ten }}
                                @else
                                <em class="text-muted">Chưa phân công</em>
                                @endif
                            </td>
                            <td>
                                @if($pc->ngay_phancong)
                                {{ \Carbon\Carbon::parse($pc->ngay_phancong)->format('d-m-Y') }}
                                @else
                                <em class="text-muted">Chưa có</em>
                                @endif
                            </td>

                            <td>{{ $pc->ghi_chu }}</td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm btn-view" data-id="{{ $pc->pc_id }}">Xem</button>
                                <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $pc->pc_id }}">Phân
                                    công</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $phancongs->links() }}
            </div>
        </div>
    </div>



    <!-- ======================== MODALS ======================== -->

    <!-- Modal Xem chi tiết -->
    <div class="modal fade" id="modalView" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header text-white" style="background-color: #4a7fa7;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-person-workspace fs-4"></i>
                        Chi tiết phân công giảng viên
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light" id="viewDetail">
                    <div class="text-center text-muted py-4">Đang tải dữ liệu...</div>
                </div>

                <!-- Nút Đóng -->
                <div class="modal-footer bg-light d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill d-flex align-items-center gap-2"
                        data-bs-dismiss="modal">
                        <i class="bi bi-x-circle fs-5"></i> Đóng
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal Phân công -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- rộng hơn -->
            <form id="formEdit" method="POST" class="w-100">
                @csrf
                @method('PUT')
                <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">

                    <!-- Header -->
                    <div class="modal-header text-white" style="background-color: #4a7fa7;">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="bi bi-person-video2 fs-4"></i>
                            Phân công giảng viên
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body px-4 py-3 bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="fw-semibold">
                                    <i class="bi bi-person-badge me-1"></i> Giảng viên
                                </label>
                                <select name="gv_id" id="edit_gv_id" class="form-control rounded-pill" required>
                                    <option value="">-- Chọn giảng viên --</option>
                                    @foreach($giangviens as $gv)
                                    <option value="{{ $gv->gv_id }}">{{ $gv->ho_ten }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-semibold">
                                    <i class="bi bi-calendar-date me-1"></i> Ngày phân công
                                </label>
                                <input type="date" name="ngay_phancong" id="edit_ngay"
                                    class="form-control rounded-pill">
                            </div>

                            <div class="col-12">
                                <label class="fw-semibold">
                                    <i class="bi bi-sticky me-1"></i> Ghi chú
                                </label>
                                <textarea name="ghi_chu" id="edit_ghichu" rows="3"
                                    class="form-control rounded-4"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer bg-white d-flex justify-content-end gap-2 px-4 py-3 border-0">
                        <button type="button"
                            class="btn btn-outline-secondary px-4 rounded-pill d-flex align-items-center gap-2"
                            data-bs-dismiss="modal">
                            <i class="bi bi-x-circle fs-5"></i> Hủy
                        </button>
                        <button class="btn text-white px-4 rounded-pill d-flex align-items-center gap-2"
                            style="background-color: #4a7fa7;">
                            <i class="bi bi-save2 fs-5"></i> Lưu phân công
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @endsection


    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("JS phanconggiangvien đang hoạt động");

        const modalView = new bootstrap.Modal(document.getElementById('modalView'));
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));

        //  Xem chi tiết
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                const res = await fetch(`/admin/phanconggiangvien/${id}`);
                const data = await res.json();
                const dk = data.dang_ky_thuc_tap;
                const sv = dk?.sinh_vien;
                const vt = dk?.vi_tri_thuc_tap;
                const dn = vt?.doanh_nghiep;

                const detail = `
<div class="p-3">
    <div class="row">
        <!-- Cột trái: Thông tin sinh viên -->
        <div class="col-md-6 border-end">
            <h6 class="fw-bold text-white px-3 py-2 rounded-3 mb-3" style="background-color:#4a7fa7;">
                <i class="bi bi-person-badge me-2"></i> Thông tin sinh viên
            </h6>
            <div class="ps-2">
                <p><i class="bi bi-person"></i> <b>Họ tên:</b> ${sv?.ho_ten ?? 'N/A'}</p>
                <p><i class="bi bi-mortarboard"></i> <b>Lớp:</b> ${sv?.lop ?? 'N/A'}</p>
                <p><i class="bi bi-journal-bookmark"></i> <b>Ngành:</b> ${sv?.nganh ?? 'N/A'}</p>
                <p><i class="bi bi-envelope"></i> <b>Email:</b> ${sv?.email ?? 'N/A'}</p>
                <p><i class="bi bi-telephone"></i> <b>SĐT:</b> ${sv?.sdt ?? 'N/A'}</p>
            </div>
        </div>

        <!-- Cột phải: Thông tin thực tập -->
        <div class="col-md-6">
            <h6 class="fw-bold text-white px-3 py-2 rounded-3 mb-3" style="background-color:#4a7fa7;">
                <i class="bi bi-building me-2"></i> Thông tin thực tập
            </h6>
            <div class="ps-2">
                <p><i class="bi bi-briefcase"></i> <b>Vị trí:</b> ${vt?.ten_vitri ?? 'N/A'}</p>
                <p><i class="bi bi-card-text"></i> <b>Mô tả:</b> ${vt?.mo_ta ? vt.mo_ta.slice(0, 100) + '...' : 'N/A'}</p>
                <p><i class="bi bi-building-check"></i> <b>Công ty:</b> ${dn?.ten_dn ?? 'N/A'}</p>
                <p><i class="bi bi-geo-alt"></i> <b>Địa chỉ:</b> ${dn?.dia_chi ?? 'N/A'}</p>
                <p><i class="bi bi-telephone"></i> <b>Liên hệ:</b> ${dn?.lien_he ?? 'N/A'}</p>
            </div>
        </div>
    </div>

    <!-- Dòng dưới: Thông tin phân công -->
    <div class="mt-4">
        <h6 class="fw-bold text-white px-3 py-2 rounded-3 mb-3" style="background-color:#4a7fa7;">
            <i class="bi bi-calendar-check me-2"></i> Thông tin phân công
        </h6>
        <div class="ps-2">
            <p><i class="bi bi-person-video2"></i> <b>Giảng viên hướng dẫn:</b> ${data.giang_vien?.ho_ten ?? '<em>Chưa phân công</em>'}</p>
            <p><i class="bi bi-calendar3"></i> <b>Ngày phân công:</b> ${
                data.ngay_phancong ? data.ngay_phancong.split('T')[0] : '<em>Chưa có</em>'
            }</p>
            <p><i class="bi bi-sticky"></i> <b>Ghi chú:</b> ${data.ghi_chu ?? '<em>Không có</em>'}</p>
        </div>
    </div>
</div>
`;
                document.getElementById('viewDetail').innerHTML = detail;
                modalView.show();


            });
        });

        //  Phân công
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                const res = await fetch(`/admin/phanconggiangvien/${id}`);
                const data = await res.json();
                document.getElementById('edit_gv_id').value = data.gv_id ?? '';
                document.getElementById('edit_ngay').value = data.ngay_phancong ?
                    data.ngay_phancong.split('T')[0] :
                    '';
                document.getElementById('edit_ghichu').value = data.ghi_chu ?? '';
                document.getElementById('formEdit').action =
                    `/admin/phanconggiangvien/${id}`;
                modalEdit.show();

            });
        });

        // SweetAlert thông báo
        ['success', 'error'].forEach(type => {
            const message = document.querySelector(`meta[name="${type}-message"]`)?.content;
            if (message) {
                Swal.fire({
                    icon: type,
                    title: message,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });
    </script>
    @endsection