@extends('layouts.dngv')

@section('title', 'Chi tiết thông báo')

@section('content')
<div class="container mx-auto mt-10 px-4">
    <div class="bg-white shadow-mb rounded-2xl p-6 border border-gray-100">

        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center rounded-full bg-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 16h-1v-4h-1m1-4h.01M12 20c4.418 0 8-3.582 8-8S16.418 4 12 4 4 7.582 4 12s3.582 8 8 8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $thongBao->tieude }}</h2>
                    <div class="flex items-center text-sm text-gray-500 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($thongBao->ngay_gui)->format('d-m-Y') }}
                    </div>
                </div>
            </div>
            <span class="px-4 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                Đã đọc
            </span>
        </div>

        <hr class="my-4 border-gray-200">

        <!-- Người gửi + Thời gian đọc -->
        <div class="space-y-2 mb-6">
            <div class="flex items-center text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-blue-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M5.121 17.804A3 3 0 017.757 17h8.486a3 3 0 012.636 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-semibold mr-1">Người gửi:</span>
                <span>{{ $thongBao->ten_nguoi_gui }}</span>
            </div>

            <div class="flex items-center text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold mr-1">Đã đọc lúc:</span>
                <span>{{ \Carbon\Carbon::parse($thongBao->thoi_gian_doc)->format('d-m-Y') }}</span>
            </div>
        </div>

        <!-- Nội dung thông báo -->
        <div class="bg-gray-50 rounded-xl p-5 text-gray-800 text-base leading-relaxed shadow-inner mb-8">
            {!! nl2br(e($thongBao->noidung)) !!}
        </div>

        <!-- Nút quay lại -->
        <div class="text-center">
            <a href="{{ route('giangvien.dashboard') }}"
                class="inline-flex items-center px-6 py-2 text-white font-medium rounded-lg shadow transition duration-200"
                style="background-color: #4a7fa7;" onmouseover="this.style.backgroundColor='#3b6d91';"
                onmouseout="this.style.backgroundColor='#4a7fa7';">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                </svg>
                Quay lại Dashboard
            </a>

        </div>

    </div>
</div>
@endsection