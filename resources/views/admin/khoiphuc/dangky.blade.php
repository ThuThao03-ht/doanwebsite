@extends('layouts.admin')

@section('content')
<meta name="success-message" content="{{ session('success') }}">
<meta name="error-message" content="{{ session('error') }}">

<style>
h3 i {
    color: #4a7fa7;
}

.table-primary {
    background-color: #4a7fa7 !important;
    color: white;
}

.btn-success {
    background-color: #4a7fa7;
    border-color: #4a7fa7;
}

.btn-success:hover {
    background-color: #3a6d8a;
}
</style>

<div class="container mt-4">
    <h3 class="mb-3 fw-bold " style="color: #4a7fa7"><i class="bi bi-journal-text me-2"></i>Danh sách đăng ký thực tập
        đã
        xóa
    </h3>

    <table class="table table-bordered table-hover align-middle shadow-sm">
        <thead class="table text-center">
            <tr>
                <th>Sinh viên</th>
                <th>Vị trí thực tập</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dangky as $dk)
            <tr>
                <td>{{ $dk->sinhvien->ho_ten ?? 'N/A' }}</td>
                <td>{{ $dk->vitriThucTap->ten_vitri ?? 'N/A' }}</td>
                <td>{{ $dk->trangthai ?? 'Chưa xác định' }}</td>
                <td class="text-center">
                    <form method="POST" action="{{ route('admin.khoiphuc.dangky.restore', $dk->dk_id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-3">Không có đăng ký thực tập nào bị xóa</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('admin.khoiphuc.sweetalert')
@endsection