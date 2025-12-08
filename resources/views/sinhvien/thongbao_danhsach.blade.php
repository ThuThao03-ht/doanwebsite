@extends('layouts.app')

@section('title', 'Danh sách thông báo')

@section('content')
<div class="container mx-auto mt-8">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">

        <div class="flex items-center justify-between mb-5">
            <h2 class="text-2xl font-bold text-[#4A7FA7]">
                <i class="fas fa-bell mr-2"></i> Danh sách thông báo
            </h2>
            <a href="{{ route('sinhvien.dashboard') }}" class="text-sm text-gray-600 hover:text-[#4A7FA7] transition">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại Dashboard
            </a>
        </div>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Tiêu đề</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                    <th class="text-center" style="width: 120px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($thongBaoList as $index => $tb)
                <tr class="{{ $tb->da_doc ? '' : 'fw-bold' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tb->tieude }}</td>
                    <td>{{ \Carbon\Carbon::parse($tb->ngay_gui)->format('d-m-Y') }}</td>
                    <td>
                        @if($tb->da_doc)
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã đọc</span>
                        @else
                        <span class="badge bg-danger"><i class="fas fa-bell me-1"></i>Chưa đọc</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('sinhvien.thongbao.xem', $tb->tb_id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Xem
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">
                        <i class="fas fa-inbox me-2 text-gray-400"></i> Không có thông báo nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection