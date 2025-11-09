@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold text-theme mb-0" style="color: #4A7FA7">
            <i class="bi bi-journal-check me-2" style="color:#4A7FA7;"></i>Danh sách đăng ký thực tập
        </h3>

        <form method="GET" class="d-flex align-items-center">
            <label class="me-2 fw-semibold" style="color:#4A7FA7;">
                <i class="bi bi-funnel-fill me-1"></i>Lọc trạng thái:
            </label>
            <select name="trang_thai" class="form-select me-2 shadow-sm rounded-2"
                style="width: 200px; border-color:#4A7FA7;">
                <option value="">Tất cả</option>
                <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt
                </option>
                <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã duyệt
                </option>
                <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
                <option value="dang_thuctap" {{ request('trang_thai') == 'dang_thuctap' ? 'selected' : '' }}>Đang
                    thực
                    tập</option>
                <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành
                </option>
            </select>
            <button type="submit" class="btn btn-sm text-white" style="background-color:#4A7FA7;">
                </i>Lọc
            </button>
        </form>
    </div>

    <hr class="my-3 border-2 opacity-50">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header fw-semibold text-white" style="background-color: #4A7FA7;">
            <i class="bi bi-list-ul me-2"></i>Bảng đăng ký thực tập
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #e9f1f7;" class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Sinh viên</th>
                            <th>Vị trí</th>
                            <th>Ngày đăng ký</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dangky as $dk)
                        <tr>
                            <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                            <td>{{ $dk->sinhVien->ho_ten ?? '—' }}</td>
                            <td>{{ $dk->viTriThucTap->ten_vitri ?? '—' }}</td>
                            <td class="text-center">{{ $dk->ngay_dangky }}</td>
                            <td class="text-center">
                                <span
                                    class="badge bg-{{ $dk->trang_thai == 'cho_duyet' ? 'secondary' : ($dk->trang_thai == 'da_duyet' ? 'success' : ($dk->trang_thai == 'tu_choi' ? 'danger' : 'info')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $dk->trang_thai)) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info me-1" data-id="{{ $dk->dk_id }}"
                                    data-bs-toggle="modal" data-bs-target="#modalChiTiet">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-success me-1" data-id="{{ $dk->dk_id }}"
                                    data-bs-toggle="modal" data-bs-target="#modalDuyet">
                                    <i class="bi bi-check2-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-warning me-1" data-id="{{ $dk->dk_id }}"
                                    data-bs-toggle="modal" data-bs-target="#modalCapNhat">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-id="{{ $dk->dk_id }}" data-bs-toggle="modal"
                                    data-bs-target="#modalHuy">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Modal 1: Xem chi tiết --}}
            <div class="modal fade" id="modalChiTiet" tabindex="-1">
                <div class="modal-dialog modal-md modal-dialog-centered"> {{-- modal-md cho ngắn --}}
                    <div class="modal-content shadow-lg border-0 rounded-3">

                        {{-- Header --}}
                        <div class="modal-header" style="background-color: #4a7fa7; color: white;">
                            <h5 class="modal-title fw-semibold">
                                <i class="fas fa-file-alt me-2"></i>Chi tiết đăng ký
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        {{-- Body --}}
                        <div class="modal-body px-4 py-3" id="chiTietContent">
                            {{-- Nội dung động sẽ được load vào đây --}}
                            <div class="text-center text-muted">
                                <i class="fas fa-spinner fa-spin me-2"></i>Đang tải dữ liệu...
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="modal-footer border-0 d-flex justify-content-end">
                            <button type="button" class="btn text-white px-4" data-bs-dismiss="modal"
                                style="background-color: #4a7fa7;">
                                <i class="fas fa-times-circle me-1"></i>Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal 2: Duyệt đăng ký --}}
            <div class="modal fade" id="modalDuyet" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <form method="POST" id="formDuyet">
                        @csrf
                        <div class="modal-content shadow-lg border-0 rounded-3">
                            <div class="modal-header" style="background-color: #4a7fa7; color: white;">
                                <h5 class="modal-title fw-semibold">
                                    <i class="bi bi-check-circle-fill me-2"></i>Duyệt đăng ký
                                </h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body py-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-toggle2-on me-2 text-success"></i>Chọn trạng thái:
                                </label>
                                <select name="trang_thai" class="form-select shadow-sm rounded-2">
                                    <option value="da_duyet">Duyệt</option>
                                    <option value="tu_choi">Từ chối</option>
                                </select>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-2"></i>Hủy
                                </button>
                                <button class="btn text-white" style="background-color: #4a7fa7;" type="submit">
                                    <i class="bi bi-save2-fill me-2"></i>Cập nhật
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal 3: Cập nhật trạng thái thực tập --}}
            <div class="modal fade" id="modalCapNhat" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <form method="POST" id="formCapNhat">
                        @csrf
                        <div class="modal-content shadow-lg border-0 rounded-3">
                            <div class="modal-header" style="background-color: #4a7fa7; color: white;">
                                <h5 class="modal-title fw-semibold">
                                    <i class="bi bi-arrow-repeat me-2"></i>Cập nhật trạng thái
                                </h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body py-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-flag-fill me-2 text-warning"></i>Trạng thái mới:
                                </label>
                                <select name="trang_thai" class="form-select shadow-sm rounded-2">
                                    <option value="dang_thuctap">Đang thực tập</option>
                                    <option value="hoan_thanh">Hoàn thành</option>
                                </select>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-2"></i>Hủy
                                </button>
                                <button class="btn text-white" style="background-color: #4a7fa7;" type="submit">
                                    <i class="bi bi-pencil-square me-2"></i>Cập nhật
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            {{-- Modal 4: Hủy đăng ký --}}
            <div class="modal fade" id="modalHuy" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" id="formHuy">
                        @csrf
                        <div class="modal-content shadow-lg border-0 rounded-3">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fw-semibold">
                                    <i class="bi bi-exclamation-octagon-fill me-2"></i>Xác nhận hủy đăng ký
                                </h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                    Bạn có chắc chắn muốn <strong>hủy</strong> đăng ký thực tập này không?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-2"></i>Hủy
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-check-circle me-2"></i>Xác nhận
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            {{-- SweetAlert Thông báo --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                var successMessage = "{{ session('success') }}";

                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: successMessage,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
                // Load chi tiết đăng ký
                const chiTietModal = document.getElementById('modalChiTiet');

                chiTietModal.addEventListener('show.bs.modal', function(event) {
                    const id = event.relatedTarget.getAttribute('data-id');
                    const chiTietContent = document.getElementById('chiTietContent');

                    // Hiển thị loading tạm
                    chiTietContent.innerHTML = `
        <div class="text-center text-muted py-3">
            <i class="bi bi-arrow-repeat spin me-2"></i>Đang tải dữ liệu...
        </div>
    `;

                    // Gửi request
                    fetch(`/admin/dangkythuctap/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            chiTietContent.innerHTML = `
                <div class="p-2">
                    <div class="d-flex align-items-center py-2 border-bottom">
                        <i class="bi bi-person-fill me-2 text-theme fs-5"></i>
                        <strong>Sinh viên:</strong> 
                        <span class="ms-2">${data.sinh_vien.ho_ten}</span>
                    </div>
                    <div class="d-flex align-items-center py-2 border-bottom">
                        <i class="bi bi-briefcase-fill me-2 text-theme fs-5"></i>
                        <strong>Vị trí:</strong> 
                        <span class="ms-2">${data.vi_tri_thuc_tap.ten_vitri}</span>
                    </div>
                    <div class="d-flex align-items-center py-2 border-bottom">
                        <i class="bi bi-calendar-event-fill me-2 text-theme fs-5"></i>
                        <strong>Ngày đăng ký:</strong> 
                        <span class="ms-2">${data.ngay_dangky}</span>
                    </div>
                    <div class="d-flex align-items-center py-2">
                        <i class="bi bi-check-circle-fill me-2 text-theme fs-5"></i>
                        <strong>Trạng thái:</strong> 
                        <span class="ms-2">${data.trang_thai}</span>
                    </div>
                </div>
            `;
                        })
                        .catch(() => {
                            chiTietContent.innerHTML = `
                <div class="text-danger text-center py-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Lỗi khi tải dữ liệu.
                </div>
            `;
                        });
                });


                // Cập nhật action form duyệt
                document.getElementById('modalDuyet').addEventListener('show.bs.modal', e => {
                    let id = e.relatedTarget.getAttribute('data-id');
                    document.getElementById('formDuyet').action = `/admin/dangkythuctap/duyet/${id}`;
                });

                // Cập nhật action form cập nhật
                document.getElementById('modalCapNhat').addEventListener('show.bs.modal', e => {
                    let id = e.relatedTarget.getAttribute('data-id');
                    document.getElementById('formCapNhat').action =
                        `/admin/dangkythuctap/capnhat/${id}`;
                });
                // Cập nhật action form hủy
                document.getElementById('modalHuy').addEventListener('show.bs.modal', e => {
                    let id = e.relatedTarget.getAttribute('data-id');
                    document.getElementById('formHuy').action = `/admin/dangkythuctap/huy/${id}`;
                });

            });
            </script>
            @endsection