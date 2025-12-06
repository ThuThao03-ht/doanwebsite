@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap: 1rem;">
        <h2 class="fw-bold d-flex align-items-center gap-2 mb-0" style="color:#4A7FA7; white-space: nowrap;">
            <i class="bi bi-person-gear"></i> Quản lý Tài khoản
        </h2>

        <!-- Form lọc theo vai trò -->
        <form action="{{ route('admin.taikhoan.index') }}" method="GET" class="d-flex align-items-center flex-nowrap">
            <label for="roleFilter" class="form-label fw-bold me-2 mb-0" style="color:#4A7FA7; white-space: nowrap;">
                <i class="bi bi-filter-circle me-1"></i> Lọc theo vai trò:
            </label>
            <select name="role_id" id="roleFilter" class="form-select" onchange="this.form.submit()"
                style="min-width: 200px;">
                <option value="">-- Tất cả vai trò --</option>
                @foreach($roles as $role)
                <option value="{{ $role->role_id }}"
                    {{ isset($roleFilter) && $roleFilter == $role->role_id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Đường phân cách màu chủ đạo -->
    <hr class="border-2" style="border-color: #4A7FA7;">

    <!-- Bảng danh sách tài khoản -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <!-- Header bảng -->
        <div class="card-header text-white fw-bold d-flex align-items-center" style="background-color: #4A7FA7;">
            <i class="bi bi-person-lines-fill me-2"></i> Danh sách Tài khoản
        </div>

        <!-- Table responsive -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th width="8%">ID</th>
                        <th width="5%">Username</th>
                        <th width="4%">Role</th>
                        <th width="10%">Trạng thái</th>
                        <th width="10%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="align-middle text-center">
                        <td>{{ $user->user_id }}</td>
                        <td class="text-start">{{ $user->username }}</td>
                        <td class="text-start">{{ $user->role->role_name }}</td>
                        <td>
                            <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status === 'active' ? 'Hoạt động' : 'Tạm khóa' }}
                            </span>
                        </td>

                        <td class="text-center">
                            <!-- Xem chi tiết -->
                            <button class="btn btn-info btn-sm btn-view" data-id="{{ $user->user_id }}"
                                title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </button>

                            <!-- Khóa / Mở khóa (ẩn nếu là admin) -->
                            @if($user->role->role_name !== 'Admin')

                            <form action="{{ route('admin.taikhoan.toggle', $user->user_id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm"
                                    title="{{ $user->status === 'active' ? 'Khóa' : 'Mở khóa' }}">
                                    @if($user->status === 'active')
                                    <i class="bi bi-lock-fill"></i>
                                    @else
                                    <i class="bi bi-unlock-fill"></i>
                                    @endif
                                </button>
                            </form>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Không có tài khoản nào phù hợp</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>

            <style>
            /* Ẩn mọi phần thông tin mô tả trong pagination */
            .pagination-info,
            .text-sm.text-gray-700.leading-5,
            .small.text-muted {
                display: none !important;
            }
            </style>

        </div>
    </div>
</div>

<!-- Modal xem chi tiết -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-4">

            <!-- Header -->
            <div class="modal-header text-white" style="background-color: #4A7FA7;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="detailModalLabel">
                    <i class="bi bi-person-circle fs-4"></i> Chi tiết tài khoản
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Đóng"></button>
            </div>

            <!-- Body -->
            <div class="modal-body bg-light">
                <div class="row g-0">

                    <!-- Cột trái: Thông tin tài khoản -->
                    <div class="col-md-6 border-end d-flex flex-column justify-content-between">
                        <div class="p-3 bg-white rounded-start-4 shadow-sm h-100">
                            <!-- Tiêu đề Thông tin Tài khoản -->
                            <h6 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                <i class="bi bi-card-list me-2"></i>Thông tin Tài khoản
                            </h6>

                            <div class="mb-2"><i class="bi bi-hash text-secondary me-2"></i><strong>ID:</strong> <span
                                    id="user_id"></span></div>
                            <hr class="my-1">
                            <div class="mb-2"><i class="bi bi-person text-secondary me-2"></i><strong>Username:</strong>
                                <span id="username"></span>
                            </div>
                            <hr class="my-1">
                            <div class="mb-2"><i
                                    class="bi bi-person-badge text-secondary me-2"></i><strong>Role:</strong> <span
                                    id="role_name"></span></div>
                            <hr class="my-1">
                            <div class="mb-2"><i
                                    class="bi bi-toggle-on text-secondary me-2"></i><strong>Status:</strong> <span
                                    id="status"></span></div>
                            <hr class="my-1">

                            <!-- Tiêu đề Avatar -->
                            <h6 class="fw-bold text-primary mb-2 d-flex align-items-center">
                                <i class="bi bi-image me-2" style="color:#4A7FA7;"></i>Avatar
                            </h6>
                            <div class="text-left">
                                <img id="avatar" src="{{ asset('storage/upload/avatar/' . $user->avatar) }}"
                                    style="width: 120px; height: 120px; object-fit: cover;"
                                    class="shadow-sm border border-3 border-light">
                            </div>



                        </div>
                    </div>



                    <!-- Cột phải: Thông tin chi tiết người dùng -->
                    <div class="col-md-6 d-flex flex-column justify-content-between">
                        <div class="p-3 bg-white rounded-end-4 shadow-sm h-100" id="user_detail">
                            <h6 class="fw-bold text-primary d-flex align-items-center mb-3">
                                <i class="bi bi-info-circle me-2"></i>Thông tin chi tiết
                            </h6>
                            <!-- Nội dung chi tiết sẽ được JS chèn -->
                            <p class="text-muted fst-italic text-center">Đang tải dữ liệu...</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn text-white px-4" style="background-color:#4A7FA7;"
                    data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Đóng
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // === Xem chi tiết tài khoản ===
    document.querySelectorAll('.btn-view').forEach(function(button) {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');

            fetch(`/admin/taikhoan/${id}`)
                .then(res => res.json())
                .then(user => {
                    // Thông tin tài khoản
                    document.getElementById('user_id').textContent = user.user_id;
                    document.getElementById('username').textContent = user.username;
                    document.getElementById('role_name').textContent = user.role.role_name;
                    document.getElementById('status').textContent = user.status;
                    document.getElementById('avatar').src = user.avatar ?
                        '{{ asset("storage/upload/avatar") }}/' + user.avatar :
                        '{{ asset("images/default.png") }}';


                    // Thông tin người dùng cụ thể
                    let detailHtml = '';
                    if (user.role.role_name === 'SinhVien' && user.sinhvien) {
                        detailHtml = `
                            <h6 class="fw-bold mb-3">Thông tin Sinh viên</h6>
                            <p><strong>Mã SV:</strong> ${user.sinhvien.ma_sv}</p>
                            <p><strong>Họ tên:</strong> ${user.sinhvien.ho_ten}</p>
                            <p><strong>Lớp:</strong> ${user.sinhvien.lop}</p>
                            <p><strong>Ngành:</strong> ${user.sinhvien.nganh}</p>
                            <p><strong>Email:</strong> ${user.sinhvien.email}</p>
                            <p><strong>SĐT:</strong> ${user.sinhvien.sdt}</p>
                        `;
                    } else if (user.role.role_name === 'GiangVien' && user.giangvien) {
                        detailHtml = `
                            <h6 class="fw-bold mb-3">Thông tin Giảng viên</h6>
                            <p><strong>Mã GV:</strong> ${user.giangvien.ma_gv}</p>
                            <p><strong>Họ tên:</strong> ${user.giangvien.ho_ten}</p>
                            <p><strong>Bộ môn:</strong> ${user.giangvien.bo_mon}</p>
                            <p><strong>Email:</strong> ${user.giangvien.email}</p>
                            <p><strong>SĐT:</strong> ${user.giangvien.sdt}</p>
                        `;
                    } else if (user.role.role_name === 'DoanhNghiep' && user.doanhnghiep) {
                        detailHtml = `
                            <h6 class="fw-bold mb-3">Thông tin Doanh nghiệp</h6>
                            <p><strong>Tên DN:</strong> ${user.doanhnghiep.ten_dn}</p>
                            <p><strong>Địa chỉ:</strong> ${user.doanhnghiep.dia_chi}</p>
                            <p><strong>Email:</strong> ${user.doanhnghiep.email}</p>
                            <p><strong>Liên hệ:</strong> ${user.doanhnghiep.lien_he}</p>
                            <p><strong>Website:</strong> ${user.doanhnghiep.website}</p>
                            ${user.doanhnghiep.logo ? `<p><strong>Logo:</strong><br><img src="/storage/${user.doanhnghiep.logo}" width="100" class="rounded shadow-sm mt-2"></p>` : ''}
                            ${user.doanhnghiep.mo_ta ? `<p><strong>Mô tả:</strong> ${user.doanhnghiep.mo_ta}</p>` : ''}
                        `;
                    } else {
                        detailHtml =
                            `<p class="text-muted fst-italic">Không có thông tin chi tiết.</p>`;
                    }

                    document.getElementById('user_detail').innerHTML = detailHtml;

                    // Hiển thị modal
                    var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                })
                .catch(err => console.error(err));
        });
    });

    // === Xác nhận khóa / mở khóa tài khoản ===
    document.querySelectorAll('form.toggle-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let btn = form.querySelector('button[type="submit"]');
            let action = btn.title;

            Swal.fire({
                title: 'Xác nhận',
                text: `Bạn có chắc chắn muốn ${action} tài khoản này không?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Có',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>



<!-- CSS tùy chỉnh -->
<style>
.table {
    width: 100%;
    border-collapse: collapse;
}

/* default: cho phép wrap */
.table th,
.table td {
    vertical-align: middle;
    padding: 10px 12px;
    text-align: left;
    white-space: normal;
    word-break: break-word;
}

/* Cột ID */
.table th:nth-child(1),
.table td:nth-child(1) {
    width: 8%;
    text-align: center;
    white-space: nowrap;
}

/* Username */
.table th:nth-child(2),
.table td:nth-child(2) {
    width: 40%;
}

/* Role */
.table th:nth-child(3),
.table td:nth-child(3) {
    width: 25%;
}

/* Trạng thái + Hành động */
.table th:nth-child(4),
.table td:nth-child(4),
.table th:nth-child(5),
.table td:nth-child(5) {
    width: 13%;
    text-align: center;
    white-space: nowrap;
}

/* Hover đẹp */
.table-hover tbody tr:hover {
    background-color: rgba(74, 127, 167, 0.1);
    transition: background-color 0.3s;
}

/* Giữ badge và icon không bị lệch */
.badge {
    font-size: 0.85rem;
    padding: 6px 10px;
    border-radius: 10px;
}

.card-header i {
    font-size: 1.2rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(74, 127, 167, 0.1);
    transition: background-color 0.3s;
}

.badge {
    font-size: 0.9rem;
    padding: 0.4em 0.6em;
}

#detailModal .modal-content {
    animation: fadeInUp 0.3s ease-in-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#detailModal .modal-body p {
    margin-bottom: 0.5rem;
}

#detailModal .text-primary {
    color: #4A7FA7 !important;
}

#detailModal .btn-primary {
    background-color: #4A7FA7;
    border-color: #4A7FA7;
}

#detailModal .btn-primary:hover {
    background-color: #3c6b8b;
}

#detailModal hr {
    border-top: 1px solid #e0e0e0;
    margin: 0.25rem 0;
}
</style>
@endsection