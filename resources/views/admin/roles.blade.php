@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Tiêu đề & nút thêm --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h3 class="fw-bold mb-0 text-theme">
            <i class="bi bi-shield-lock-fill me-2"></i> Danh sách vai trò
        </h3>
        <button class="btn btn-theme shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="bi bi-plus-circle me-1"></i> Thêm vai trò
        </button>
    </div>

    <!-- {{-- Thông báo success / error --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif -->

    {{-- Bảng danh sách --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-theme text-white fw-semibold">
            <i class="bi bi-list-ul me-2"></i> Bảng vai trò hệ thống
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-theme text-white">
                    <tr class="fw-semibold">
                        <th>#</th>
                        <th>Tên vai trò</th>
                        <th>Số người dùng</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->role_id }}</td>
                        <td>{{ $role->role_name }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td>{{ \Carbon\Carbon::parse($role->created_at)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($role->updated_at)->format('d-m-Y') }}</td>

                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                    data-bs-target="#showRoleModal" data-id="{{ $role->role_id }}"
                                    data-name="{{ $role->role_name }}" data-created="{{ $role->created_at }}"
                                    data-updated="{{ $role->updated_at }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                    data-bs-target="#editRoleModal" data-id="{{ $role->role_id }}"
                                    data-name="{{ $role->role_name }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <form action="{{ route('admin.roles.destroy', $role->role_id) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-muted py-4">Không có vai trò nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>

{{-- Modal Thêm mới --}}
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-theme text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Thêm vai trò mới</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRoleForm" method="POST" action="{{ route('admin.roles.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên vai trò</label>
                        <input type="text" name="role_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Thêm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Xem chi tiết --}}
<div class="modal fade" id="showRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-theme text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-eye-fill me-2"></i>Chi tiết vai trò</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        <tr class="border-bottom">
                            <th class="text-secondary" style="width: 35%;">ID</th>
                            <td id="showRoleId"></td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="text-secondary">Tên vai trò</th>
                            <td id="showRoleName"></td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="text-secondary">Ngày tạo</th>
                            <td id="showRoleCreated"></td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Ngày cập nhật</th>
                            <td id="showRoleUpdated"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-theme text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Chỉnh sửa vai trò</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên vai trò</label>
                        <input type="text" id="editRoleName" name="role_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-theme">Cập nhật</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JS modal Edit --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editRoleModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        document.getElementById('editRoleName').value = name;
        document.getElementById('editRoleForm').action = '/admin/roles/' + id;
    });

    // Modal Xem chi tiết
    const showModal = document.getElementById('showRoleModal');
    showModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget; // nút bấm để mở modal
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const created = button.getAttribute('data-created');
        const updated = button.getAttribute('data-updated');

        document.getElementById('showRoleId').textContent = id;
        document.getElementById('showRoleName').textContent = name;
        document.getElementById('showRoleCreated').textContent = new Date(created).toLocaleString(
            'vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        document.getElementById('showRoleUpdated').textContent = new Date(updated).toLocaleString(
            'vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
    });

    // SweetAlert2 Xóa xác nhận
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Hành động này sẽ xóa vai trò khỏi hệ thống!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: getComputedStyle(document.documentElement)
                    .getPropertyValue('--theme-color').trim(),
                cancelButtonColor: '#6c757d',
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

{{-- CSS --}}
<style>
:root {
    --theme-color: #4A7FA7;
    --theme-hover: #3b6e93;
}

.text-theme {
    color: var(--theme-color) !important;
}

.bg-theme {
    background-color: var(--theme-color) !important;
}

.btn-theme {
    background-color: var(--theme-color);
    color: white;
    border: none;
    transition: 0.3s ease;
}

.btn-theme:hover {
    background-color: var(--theme-hover);
    box-shadow: 0 4px 12px rgba(74, 127, 167, 0.3);
}

.table-theme {
    background-color: var(--theme-color);
}

#showRoleModal table th {
    font-weight: 600;
}

#showRoleModal table td {
    color: #333;
}

#showRoleModal table tr:not(:last-child) {
    border-bottom: 1px solid #dee2e6;
}
</style>
@endsection