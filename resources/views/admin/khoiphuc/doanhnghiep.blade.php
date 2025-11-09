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
    <h3 class="mb-3 fw-bold " style="color: #4a7fa7"><i class=" bi bi-building me-2"></i>Danh sách doanh nghiệp đã xóa
    </h3>

    <table class="table table-bordered table-hover align-middle shadow-sm">
        <thead class="table text-center">
            <tr>
                <th>Tên doanh nghiệp</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($doanhnghiep as $dn)
            <tr>
                <td>{{ $dn->ten_dn }}</td>
                <td>{{ $dn->email }}</td>
                <td>{{ $dn->diachi }}</td>
                <td class="text-center">
                    <form method="POST" action="{{ route('admin.khoiphuc.doanhnghiep.restore', $dn->dn_id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-3">Không có doanh nghiệp nào bị xóa</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('admin.khoiphuc.sweetalert')
@endsection