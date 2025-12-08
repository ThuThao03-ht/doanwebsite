@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <div class="bg-white shadow-lg rounded-2xl p-6 max-w-2xl mx-auto border border-gray-100">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="bg-[#4A7FA7] text-white p-3 rounded-full shadow-md">
                    <i class="fas fa-bell text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $thongBao->tieude }}</h2>
                    <p class="text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::parse($thongBao->ngay_gui)->format('d-m-Y') }}
                    </p>
                </div>
            </div>

            {{-- Trạng thái đọc --}}
            <span class="flex items-center gap-1 text-xs px-3 py-1 rounded-full font-medium
                {{ $thongBao->da_doc ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                @if($thongBao->da_doc)
                <i class="fas fa-check-circle text-green-600"></i> Đã đọc
                @else
                <i class="fas fa-bell text-red-500"></i> Chưa đọc
                @endif
            </span>
        </div>

        <hr class="my-4">

        {{-- Người gửi + thời gian đọc --}}
        <div class="mb-4 text-sm text-gray-600 space-y-1">
            <p>
                <i class="fas fa-user mr-1 text-[#4A7FA7]"></i>
                <strong>Người gửi:</strong> {{ $thongBao->ten_nguoi_gui ?? 'Không rõ' }}
            </p>
            @if($thongBao->da_doc && $thongBao->thoi_gian_doc)
            <p>
                <i class="fas fa-clock mr-1 text-green-600"></i>
                <strong>Đã đọc lúc:</strong>
                {{ \Carbon\Carbon::parse($thongBao->thoi_gian_doc)->format('d-m-Y') }}
            </p>
            @endif
        </div>

        {{-- Nội dung --}}
        <div class="bg-gray-50 rounded-xl p-4 text-gray-800 leading-relaxed shadow-inner">
            {!! nl2br(e($thongBao->noidung)) !!}
        </div>

        {{-- Footer --}}
        <div class="mt-6 text-center">
            <a href="{{ route('sinhvien.dashboard') }}"
                class="inline-flex items-center bg-[#4A7FA7] hover:bg-[#3a6a8d] text-white px-5 py-2 rounded-lg shadow transition">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại Dashboard
            </a>
        </div>
    </div>
</div>
@endsection