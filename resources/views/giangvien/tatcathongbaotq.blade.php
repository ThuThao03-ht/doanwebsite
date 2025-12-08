@extends('layouts.dngv')

@section('title', 'Tất cả thông báo')

@section('content')
<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold mb-6 flex items-center space-x-2" style="color: #4a7fa7;">
        <!-- Icon thông báo (Bootstrap Icons hoặc SVG) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span>Tất cả thông báo</span>
    </h2>

    @if($thongBaoList->count() > 0)
    <ul class="bg-white rounded-lg divide-y divide-gray-200">
        @foreach($thongBaoList as $tb)
        <li class="p-4 flex justify-between items-center hover:bg-gray-50 transition">
            <div>
                <a href="{{ route('giangvien.thongbao.xem', ['id' => $tb->tb_id]) }}"
                    class="{{ $tb->da_doc ? 'text-gray-500' : 'font-semibold text-gray-800' }}">
                    {{ $tb->tieude }}
                </a>

                <p class="text-sm text-gray-400">
                    {{ \Carbon\Carbon::parse($tb->ngay_gui)->format('d-m-Y') }}
                </p>
            </div>

            @if($tb->da_doc)
            <span class="inline-block bg-gray-300 text-gray-700 text-xs px-2 py-1 rounded-full">Đã đọc</span>
            @else
            <span class="inline-block bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Mới</span>
            @endif
        </li>
        @endforeach
    </ul>

    <div class="mt-4">
        {{ $thongBaoList->links() ?? '' }}
    </div>
    @else
    <p class="text-gray-500">Chưa có thông báo nào.</p>
    @endif

</div>
@endsection