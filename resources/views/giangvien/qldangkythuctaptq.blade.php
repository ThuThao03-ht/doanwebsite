@extends('layouts.dngv')

@section('title', 'Quản lý đăng ký thực tập')

@section('content')
<div class="container mx-auto mt-6">

    <section class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#4A7FA7] flex items-center gap-2">
                <i class="fas fa-briefcase"></i> Danh sách sinh viên đăng ký thực tập
            </h2>

            <!-- Form tìm kiếm -->
            <form action="{{ route('giangvien.qldangkythuctap') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nhập tên sinh viên..."
                    class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4A7FA7]" />
                <button type="submit"
                    class="px-4 py-2 bg-[#4A7FA7] text-white rounded-lg hover:bg-[#3B6D8F] transition flex items-center gap-1">
                    <i class="fas fa-search"></i> <span>Tìm</span>
                </button>
            </form>
        </div>

        @if($dangKyList->count() > 0)
        <table class="min-w-full bg-white border shadow-md rounded-lg overflow-hidden">
            <thead class="bg-[#4A7FA7] text-white">
                <tr>
                    <th class="py-2 px-4 border">Mã SV</th>
                    <th class="py-2 px-4 border">Họ tên</th>
                    <th class="py-2 px-4 border">Vị trí thực tập</th>
                    <th class="py-2 px-4 border">Doanh nghiệp</th>
                    <th class="py-2 px-4 border">Giảng viên hướng dẫn</th>
                    <th class="py-2 px-4 border text-center">Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dangKyList as $dk)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-2 px-4 border">{{ $dk->ma_sv }}</td>
                    <td class="py-2 px-4 border">{{ $dk->ho_ten }}</td>
                    <td class="py-2 px-4 border">{{ $dk->ten_vitri }}</td>
                    <td class="py-2 px-4 border">{{ $dk->ten_dn }}</td>
                    <td class="py-2 px-4 border">{{ $dk->gv_huongdan ?? '-' }}</td>
                    <td class="py-2 px-4 border text-center">
                        <!-- Trigger modal -->
                        <label for="modal-{{ $dk->dk_id }}"
                            class="cursor-pointer inline-flex items-center gap-1 text-[#4A7FA7] hover:underline">
                            <i class="fas fa-eye"></i> Xem
                        </label>

                        <!-- Modal -->
                        <input type="checkbox" id="modal-{{ $dk->dk_id }}" class="modal-toggle hidden" />
                        <div
                            class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 opacity-0 pointer-events-none transition-all duration-300">
                            <div
                                class="modal-box bg-white p-6 rounded-2xl shadow-xl relative w-11/12 md:w-2/5 border-t-4 border-[#4A7FA7] transform scale-95 transition-all duration-300">

                                <!-- Nút đóng góc phải -->
                                <label for="modal-{{ $dk->dk_id }}"
                                    class="absolute top-2 right-3 cursor-pointer text-gray-400 hover:text-[#4A7FA7] text-2xl">&times;</label>

                                <!-- Tiêu đề modal -->
                                <h3
                                    class="text-xl font-bold mb-4 text-[#4A7FA7] flex items-center gap-2 justify-center">
                                    <i class="fas fa-info-circle"></i> Thông tin chi tiết đăng ký
                                </h3>

                                <div class="space-y-3 text-gray-700 text-sm">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-id-card text-[#4A7FA7] w-5 text-center"></i>
                                        <span><strong>Mã sinh viên:</strong> {{ $dk->ma_sv }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user-graduate text-[#4A7FA7] w-5 text-center"></i>
                                        <span><strong>Họ tên:</strong> {{ $dk->ho_ten }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-envelope text-[#4A7FA7] w-5 text-center"></i>
                                        <span><strong>Email sinh viên:</strong> {{ $dk->email_sv }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-building text-[#4A7FA7] w-5 text-center"></i>
                                        <span><strong>Doanh nghiệp:</strong> {{ $dk->ten_dn }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-briefcase text-[#4A7FA7] w-5 text-center"></i>
                                        <span><strong>Vị trí thực tập:</strong> {{ $dk->ten_vitri }}</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-file-alt text-[#4A7FA7] w-5 text-center mt-1"></i>
                                        <span><strong>Mô tả vị trí:</strong> {{ $dk->mo_ta_vitri }}</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-tasks text-[#4A7FA7] w-5 text-center mt-1"></i>
                                        <span><strong>Yêu cầu:</strong> {{ $dk->yeu_cau }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-chalkboard-teacher text-[#4A7FA7] w-5 text-center"></i>
                                        <span><strong>Giảng viên hướng dẫn:</strong>
                                            {{ $dk->gv_huongdan ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-day text-[#4A7FA7] w-5 text-center"></i>
                                        <span><strong>Ngày đăng ký:</strong>
                                            {{ \Carbon\Carbon::parse($dk->ngay_dangky)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-hourglass-half text-[#4A7FA7] w-5 text-center"></i>
                                        <span><strong>Trạng thái:</strong>
                                            <span class="@switch($dk->trang_thai)
                                            @case('cho_duyet') bg-yellow-100 text-yellow-700 @break
                                            @case('da_duyet') bg-green-100 text-green-700 @break
                                            @case('tu_choi') bg-red-100 text-red-700 @break
                                            @case('dang_thuctap') bg-blue-100 text-blue-700 @break
                                            @case('hoan_thanh') bg-teal-100 text-teal-700 @break
                                        @endswitch rounded px-2 py-0.5 text-sm">
                                                {{ ucfirst(str_replace('_', ' ', $dk->trang_thai)) }}
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Nút đóng -->
                                <div class="mt-6 text-right">
                                    <label for="modal-{{ $dk->dk_id }}"
                                        class="inline-flex items-center px-6 py-2 bg-[#4A7FA7] text-white font-medium rounded-lg shadow hover:bg-[#3B6D8F] transition duration-200 cursor-pointer">
                                        <i class="fas fa-times mr-2"></i> Đóng
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-500">Chưa có sinh viên đăng ký thực tập nào.</p>
        @endif
    </section>
</div>

<!-- Tailwind Modal Script -->
<script>
document.querySelectorAll('.modal-toggle').forEach(function(el) {
    el.addEventListener('change', function() {
        const modal = el.nextElementSibling;
        const box = modal.querySelector('.modal-box');
        if (el.checked) {
            modal.classList.add('opacity-100', 'pointer-events-auto');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            box.classList.add('scale-100');
            box.classList.remove('scale-95');
        } else {
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            modal.classList.add('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
        }
    });
});
</script>
@endsection