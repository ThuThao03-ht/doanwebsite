@extends('layouts.dngv')

@section('title', 'Quản lý tiến độ thực tập')

@section('content')
<div class="container mx-auto mt-6">
    <section class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#4A7FA7] flex items-center gap-2">
                <i class="fas fa-tasks"></i> Danh sách tiến độ sinh viên
            </h2>

            <!--  Form tìm kiếm -->
            <form action="{{ route('giangvien.qltiendothuctap') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nhập tên sinh viên..."
                    class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4A7FA7]" />
                <button type="submit"
                    class="px-4 py-2 bg-[#4A7FA7] text-white rounded-lg hover:bg-[#3B6D8F] transition flex items-center gap-1">
                    <i class="fas fa-search"></i> <span>Tìm</span>
                </button>
            </form>
        </div>

        @if($tienDoList->count() > 0)
        <table class="min-w-full bg-white border shadow-md rounded-lg overflow-hidden">
            <thead class="bg-[#4A7FA7] text-white">
                <tr>
                    <th class="py-2 px-4 border">Mã SV</th>
                    <th class="py-2 px-4 border">Họ tên</th>
                    <th class="py-2 px-4 border">Vị trí thực tập</th>
                    <th class="py-2 px-4 border">Doanh nghiệp</th>
                    <th class="py-2 px-4 border">Giảng viên hướng dẫn</th>
                    <th class="py-2 px-4 border">Ngày cập nhật</th>
                    <th class="py-2 px-4 border">Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tienDoList as $td)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-2 px-4 border">{{ $td->ma_sv }}</td>
                    <td class="py-2 px-4 border">{{ $td->ho_ten }}</td>
                    <td class="py-2 px-4 border">{{ $td->ten_vitri }}</td>
                    <td class="py-2 px-4 border">{{ $td->ten_dn }}</td>
                    <td class="py-2 px-4 border">{{ $td->gv_huongdan ?? '-' }}</td>
                    <td class="py-2 px-4 border">{{ \Carbon\Carbon::parse($td->ngay_capnhat)->format('d-m-Y') }}</td>
                    <td class="py-2 px-4 border text-center">
                        <label for="modal-{{ $td->tiendo_id }}"
                            class="cursor-pointer inline-flex items-center gap-1 text-[#4A7FA7] hover:underline">
                            <i class="fas fa-eye"></i> Xem
                        </label>

                        <!-- Modal -->
                        <input type="checkbox" id="modal-{{ $td->tiendo_id }}" class="modal-toggle hidden" />
                        <div
                            class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300">
                            <div
                                class="modal-box bg-white p-6 rounded-xl shadow-lg relative w-11/12 md:w-3/4 border-t-4 border-[#4A7FA7] text-left">
                                <!-- Close button -->
                                <label for="modal-{{ $td->tiendo_id }}"
                                    class="absolute top-3 right-3 cursor-pointer text-gray-500 hover:text-[#4A7FA7] text-2xl">&times;</label>

                                <h3 class="text-xl font-bold mb-4 text-[#4A7FA7] flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i> Chi tiết tiến độ thực tập
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Bên trái: thông tin -->
                                    <div class="space-y-2 text-gray-700">
                                        <p><strong><i class="fas fa-id-card mr-1"></i>Mã sinh viên:</strong>
                                            {{ $td->ma_sv }}</p>
                                        <p><strong><i class="fas fa-user mr-1"></i>Họ tên:</strong> {{ $td->ho_ten }}
                                        </p>
                                        <p><strong><i class="fas fa-building mr-1"></i>Doanh nghiệp:</strong>
                                            {{ $td->ten_dn }}</p>
                                        <p><strong><i class="fas fa-briefcase mr-1"></i>Vị trí thực tập:</strong>
                                            {{ $td->ten_vitri }}</p>
                                        <p><strong><i class="fas fa-chalkboard-teacher mr-1"></i>Giảng viên hướng
                                                dẫn:</strong> {{ $td->gv_huongdan ?? '-' }}</p>
                                        <p><strong><i class="fas fa-calendar-day mr-1"></i>Ngày cập nhật:</strong>
                                            {{ \Carbon\Carbon::parse($td->ngay_capnhat)->format('d-m-Y') }}</p>
                                        <p><strong><i class="fas fa-align-left mr-1"></i>Nội dung:</strong></p>
                                        <div class="p-3 bg-gray-50 rounded border">{{ $td->noi_dung }}</div>
                                    </div>

                                    <!-- Bên phải: file đính kèm -->
                                    <div>
                                        <p class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                            <i class="fas fa-paperclip text-[#4A7FA7]"></i> File đính kèm
                                        </p>
                                        @if($td->file_dinhkem)
                                        <iframe src="{{ asset('storage/'.$td->file_dinhkem) }}"
                                            class="w-full h-80 border rounded" frameborder="0"></iframe>
                                        @else
                                        <p class="text-gray-500 italic">Không có file đính kèm</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Nút đóng -->
                                <div class="mt-6 text-right">
                                    <label for="modal-{{ $td->tiendo_id }}"
                                        class="inline-flex items-center px-3 py-2 bg-[#4A7FA7] text-white font-medium rounded-lg shadow hover:bg-[#3B6D8F] transition duration-200 cursor-pointer">
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
        <p class="text-gray-500">Chưa có tiến độ nào.</p>
        @endif
    </section>
</div>

<!-- Tailwind Modal Script -->
<script>
document.querySelectorAll('.modal-toggle').forEach(function(el) {
    el.addEventListener('change', function() {
        const modal = el.nextElementSibling;
        if (el.checked) {
            modal.classList.add('opacity-100', 'pointer-events-auto');
            modal.classList.remove('opacity-0', 'pointer-events-none');
        } else {
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }
    });
});
</script>
@endsection