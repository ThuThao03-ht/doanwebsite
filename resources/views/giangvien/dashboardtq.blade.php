@extends('layouts.dngv')

@section('title', 'Dashboard Giảng viên / Doanh nghiệp')

@section('content')
<div class="container mx-auto mt-6">

    <!-- ====== Card thống kê nhanh ====== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Tổng đăng ký -->
        <div class="p-6 rounded-xl text-center shadow-md" style="background-color: #d2b48c20;">
            <div class="flex justify-between items-center mb-3">
                <div class="text-sm font-medium opacity-90">Tổng đăng ký</div>
                <i class="bi bi-person-check text-3xl opacity-80" style="color:#d2b48c;"></i>
            </div>
            <div class="text-4xl font-bold mb-1">{{ $totalDangKy }}</div>
            <div class="text-xs opacity-80">Số lượng đăng ký hiện có</div>
        </div>

        <!-- Tiến độ -->
        <div class="p-6 rounded-xl text-center shadow-md" style="background-color: #b7d3e920;">
            <div class="flex justify-between items-center mb-3">
                <div class="text-sm font-medium opacity-90">Tiến độ</div>
                <i class="bi bi-bar-chart-line-fill text-3xl opacity-80" style="color:#b7d3e9;"></i>
            </div>
            <div class="text-4xl font-bold mb-1">{{ $totalTiendo }}</div>
            <div class="text-xs opacity-80">Hoạt động đã hoàn thành</div>
        </div>

        <!-- Báo cáo -->
        <div class="p-6 rounded-xl text-center shadow-md" style="background-color: #c4bcb020;">
            <div class="flex justify-between items-center mb-3">
                <div class="text-sm font-medium opacity-90">Báo cáo</div>
                <i class="bi bi-file-earmark-text-fill text-3xl opacity-80" style="color:#c4bcb0;"></i>
            </div>
            <div class="text-4xl font-bold mb-1">{{ $totalBaocao }}</div>
            <div class="text-xs opacity-80">Tổng số báo cáo đã nộp</div>
        </div>


    </div>





    <!-- ====== Thông báo & Sinh viên ====== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Thông báo -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center space-x-2">
                <!-- Icon thông báo -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span>Thông báo mới</span>
            </h3>

            <ul>
                @if($thongbaos->isEmpty())
                <li class="text-gray-500 text-sm py-2 text-center">
                    Chưa có thông báo nào.
                </li>
                @else
                @foreach($thongbaos as $tb)
                <li class="flex justify-between items-center border-b py-2 hover:bg-gray-50 transition duration-200">
                    <span
                        class="flex items-center space-x-2 {{ $tb->da_doc ? 'text-gray-500' : 'font-semibold text-gray-800' }}">
                        @if(!$tb->da_doc)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-red-500" fill="currentColor"
                            viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="4" />
                        </svg>
                        @endif
                        <a href="{{ route('giangvien.thongbao.xem', ['id' => $tb->tb_id]) }}">
                            {{ $tb->tieude }}
                        </a>
                    </span>

                    @if($tb->da_doc)
                    <span class="px-2 py-0.5 bg-gray-300 text-gray-700 text-xs rounded-full">Đã đọc</span>
                    @else
                    <span class="px-2 py-0.5 bg-red-500 text-white text-xs rounded-full">Mới</span>
                    @endif
                </li>
                @endforeach
                @endif
            </ul>




        </div>

        <!-- Sinh viên mới với thông tin vị trí -->
        <div class="bg-white rounded-xl shadow-md p-6" x-data="{ showAll: false }">
            <h3 class="text-xl font-semibold mb-4">Sinh viên mới</h3>

            <ul>
                @if($sinhviens->isEmpty())
                <li class="text-gray-500 text-sm py-2 text-center">
                    Chưa có sinh viên đăng ký thực tập.
                </li>
                @else
                @foreach($sinhviens as $index => $sv)
                <li class="flex flex-col border-b py-2 hover:bg-gray-50 transition duration-200 mb-2"
                    x-show="showAll || {{ $index }} < 1" x-cloak>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-semibold mr-3">
                                {{ strtoupper(substr($sv->ho_ten,0,1)) }}
                            </div>
                            <span>{{ $sv->ma_sv }} - {{ $sv->ho_ten }}</span>
                        </div>

                        <span class="px-2 py-0.5 rounded-full text-xs 
                        {{ $sv->trang_thai == 'cho_duyet' ? 'bg-yellow-200 text-yellow-800' : '' }}
                        {{ $sv->trang_thai == 'da_duyet' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $sv->trang_thai == 'tu_choi' ? 'bg-red-200 text-red-800' : '' }}
                        {{ $sv->trang_thai == 'dang_thuctap' ? 'bg-blue-200 text-blue-800' : '' }}
                        {{ $sv->trang_thai == 'hoan_thanh' ? 'bg-gray-200 text-gray-800' : '' }}">
                            {{ $sv->trang_thai }}
                        </span>
                    </div>

                    <div class="ml-13 mt-1 text-sm text-gray-600">
                        <p><strong>Vị trí:</strong> {{ $sv->ten_vitri }}</p>
                        <p><strong>Mô tả:</strong> {{ $sv->mo_ta }}</p>
                        <p><strong>Yêu cầu:</strong> {{ $sv->yeu_cau }}</p>
                        <p><strong>Số lượng:</strong> {{ $sv->soluong }}</p>
                    </div>
                </li>
                @endforeach
                @endif
            </ul>


            <!-- Nút xem thêm / thu gọn -->
            @if($sinhviens->count() > 1)

            <button @click="showAll = !showAll" class="mt-2 text-blue-500 hover:text-blue-700 font-semibold text-sm">
                <span x-text="showAll ? 'Thu gọn' : 'Xem thêm'"></span>
            </button>
            @endif
        </div>
    </div>
</div>
@endsection