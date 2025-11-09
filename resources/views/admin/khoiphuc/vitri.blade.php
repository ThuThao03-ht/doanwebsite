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
    <h3 class="mb-3  fw-bold " style=" color: #4a7fa7"><i class="bi bi-briefcase me-2"></i>Danh sách vị trí thực tập đã
        xóa</h3>

    <table class="table table-bordered table-hover align-middle shadow-sm">
        <thead class="table text-center">
            <tr>
                <th>Mã vị trí</th>
                <th>Tên vị trí</th>
                <th>Doanh nghiệp</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vitri as $vt)
            <tr>
                <td>{{ $vt->ma_vitri }}</td>
                <td>{{ $vt->ten_vitri }}</td>
                <td>{{ $vt->doanhnghiep->ten_dn ?? 'N/A' }}</td>
                <td class="text-center">
                    <form method="POST" action="{{ route('admin.khoiphuc.vitri.restore', $vt->vitri_id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-3">Không có vị trí thực tập nào bị xóa</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('admin.khoiphuc.sweetalert')
@endsection