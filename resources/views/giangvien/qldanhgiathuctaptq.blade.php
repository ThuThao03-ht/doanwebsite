@extends('layouts.dngv')

@section('title', 'Quản lý đánh giá thực tập')

@section('content')
<div class="container mx-auto mt-6" id="flash-data" data-success="{{ session('success') }}"
    data-error="{{ session('error') }}">
    <section class="bg-white rounded-xl shadow p-6">
        <!-- Khối tiêu đề -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-[#4A7FA7] flex items-center gap-2">
                <i class="fas fa-clipboard-list"></i> Danh sách báo cáo sinh viên
            </h2>
        </div>

        <!-- Khối form tìm kiếm - Thiết kế mới -->
        <div class="mb-2">

            <form action="{{ route('giangvien.qldanhgiathuctap') }}" method="GET">
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-end">

                    <!-- Ô tìm kiếm -->
                    <div class="flex-1 w-full md:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search text-[#4A7FA7] mr-1"></i>Tìm kiếm sinh viên
                        </label>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Nhập tên hoặc mã sinh viên..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#4A7FA7] focus:border-transparent transition" />
                    </div>

                    <!-- Checkbox lọc -->
                    <div class="flex items-center h-[42px]">
                        <label
                            class="flex items-center gap-2 px-4 py-2.5 bg-white rounded-lg border border-gray-300 cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="chua_danhgia" value="1"
                                class="h-4 w-4 text-[#4A7FA7] rounded focus:ring-[#4A7FA7]"
                                {{ !empty($chuaDanhGia) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Chưa đánh giá</span>
                        </label>
                    </div>

                    <!-- Nút tìm kiếm -->
                    <button type="submit"
                        class="h-[42px] px-6 bg-[#4A7FA7] text-white font-medium rounded-lg hover:bg-[#3B6D8F] transition flex items-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fas fa-search"></i>
                        <span>Tìm kiếm</span>
                    </button>

                </div>
            </form>

        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-lg overflow-hidden shadow-md">
                <thead class="bg-[#4A7FA7] text-white">
                    <tr>
                        <th class="py-2 px-4 border">Mã SV</th>
                        <th class="py-2 px-4 border">Họ tên</th>
                        <th class="py-2 px-4 border">Vị trí thực tập</th>
                        <!-- <th class="py-2 px-4 border">Doanh nghiệp</th> -->
                        <th class="py-2 px-4 border">Tiêu đề báo cáo</th>
                        <th class="py-2 px-4 border">Ngày nộp</th>
                        <!-- <th class="py-2 px-4 border">Trạng thái DK</th> -->
                        <th class="py-2 px-4 border">Điểm</th>
                        <th class="py-2 px-4 border">Chi tiết / Đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($baoCaoList as $bc)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-2 px-4 border">{{ $bc->ma_sv }}</td>
                        <td class="py-2 px-4 border">{{ $bc->ho_ten }}</td>
                        <td class="py-2 px-4 border">{{ $bc->ten_vitri }}</td>
                        <!-- <td class="py-2 px-4 border">{{ $bc->ten_dn }}</td> -->
                        <td class="py-2 px-4 border">{{ $bc->tieu_de }}</td>
                        <td class="py-2 px-4 border">{{ \Carbon\Carbon::parse($bc->ngay_nop)->format('d-m-Y') }}
                        </td>
                        <!-- <td class="py-2 px-4 border">{{ $bc->trangthai_dk }}</td> -->
                        <td class="py-2 px-4 border text-center font-semibold">{{ $bc->diem_so ?? '-' }}</td>
                        <td class="py-2 px-4 border text-center">
                            <label for="modal-{{ $bc->baocao_id }}"
                                class="cursor-pointer inline-flex items-center gap-1 text-[#4A7FA7] hover:underline">
                                Xem / Đánh giá
                            </label>

                            <!-- Modal -->
                            <input type="checkbox" id="modal-{{ $bc->baocao_id }}" class="modal-toggle hidden" />
                            <div
                                class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300">
                                <div
                                    class="modal-box bg-white p-6 rounded-xl shadow-lg relative w-11/12 md:w-3/4 border-t-4 border-[#4A7FA7] text-left">
                                    <!-- Nút đóng -->
                                    <label for="modal-{{ $bc->baocao_id }}"
                                        class="absolute top-3 right-3 cursor-pointer text-gray-500 hover:text-[#4A7FA7] text-2xl">&times;</label>

                                    <h3 class="text-xl font-bold mb-4 text-[#4A7FA7] flex items-center gap-2">
                                        <i class="fas fa-file-alt"></i> Chi tiết báo cáo - {{ $bc->ho_ten }}
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Cột trái -->
                                        <div class="space-y-2 text-gray-700">
                                            <p><strong><i class="fas fa-id-card mr-1"></i>Mã sinh viên:</strong>
                                                {{ $bc->ma_sv }}</p>
                                            <p><strong><i class="fas fa-user mr-1"></i>Họ tên:</strong>
                                                {{ $bc->ho_ten }}
                                            </p>
                                            <p><strong><i class="fas fa-building mr-1"></i>Doanh nghiệp:</strong>
                                                {{ $bc->ten_dn }}</p>
                                            <p><strong><i class="fas fa-briefcase mr-1"></i>Vị trí thực
                                                    tập:</strong>
                                                {{ $bc->ten_vitri }}</p>
                                            <p><strong><i class="fas fa-book mr-1"></i>Tiêu đề:</strong>
                                                {{ $bc->tieu_de }}
                                            </p>
                                            <p><strong><i class="fas fa-align-left mr-1"></i>Nội dung:</strong></p>
                                            <div class="p-3 bg-gray-50 rounded border">{{ $bc->noi_dung }}</div>
                                            <p><strong><i class="fas fa-calendar-day mr-1"></i>Ngày nộp:</strong>
                                                {{ \Carbon\Carbon::parse($bc->ngay_nop)->format('d-m-Y') }}</p>
                                        </div>

                                        <!-- Cột phải -->
                                        <div class="flex flex-col space-y-4">
                                            <div>
                                                <p class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                    <i class="fas fa-paperclip text-[#4A7FA7]"></i> File đính kèm
                                                </p>
                                                {!! $bc->file_baocao
                                                ? '<iframe src="'.asset('storage/'.$bc->file_baocao).'"
                                                    class="w-full h-72 border rounded-lg shadow-sm"></iframe>'
                                                : '<p class="text-gray-500 italic">Không có file đính kèm</p>' !!}
                                            </div>

                                            <!-- Form đánh giá -->
                                            <form method="POST"
                                                action="{{ route('giangvien.qldanhgiathuctap.danhgia') }}"
                                                class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="dk_id" value="{{ $bc->dk_id }}">

                                                <div>
                                                    <label class="block font-semibold text-[#4A7FA7] mb-1">
                                                        <i class="fas fa-star mr-1"></i>Điểm:
                                                    </label>
                                                    <input type="number" name="diemso" value="{{ $bc->diem_so ?? '' }}"
                                                        min="0" max="10" step="0.1"
                                                        class="border px-2 py-1 w-24 rounded focus:ring-2 focus:ring-[#4A7FA7] outline-none">
                                                </div>

                                                <div>
                                                    <label class="block font-semibold text-[#4A7FA7] mb-1">
                                                        <i class="fas fa-comment-dots mr-1"></i>Nhận xét:
                                                    </label>
                                                    <textarea name="nhanxet" rows="3"
                                                        class="border px-3 py-2 w-full rounded focus:ring-2 focus:ring-[#4A7FA7] outline-none">{{ $bc->nhan_xet ?? '' }}</textarea>
                                                </div>

                                                <div class="flex justify-end gap-3 pt-4 pr-2">
                                                    <label for="modal-{{ $bc->baocao_id }}"
                                                        class="inline-flex items-center px-3 py-2 bg-gray-300 text-gray-700 rounded-lg shadow hover:bg-gray-400 cursor-pointer transition">
                                                        <i class="fas fa-times mr-1"></i> Hủy
                                                    </label>

                                                    <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-[#4A7FA7] text-white font-medium rounded-lg shadow hover:bg-[#3B6D8F] transition">
                                                        <i class="fas fa-save mr-1"></i> Lưu đánh giá
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500 italic">Chưa có báo cáo nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const flash = document.getElementById('flash-data');
    const successMessage = flash.dataset.success;
    const errorMessage = flash.dataset.error;

    successMessage && Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: successMessage,
        confirmButtonColor: '#4A7FA7',
        timer: 2000,
        showConfirmButton: false
    });

    errorMessage && Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: errorMessage,
        confirmButtonColor: '#d33',
    });
});

// Tailwind Modal Script
document.querySelectorAll('.modal-toggle').forEach(el => {
    el.addEventListener('change', () => {
        const modal = el.nextElementSibling;
        modal.classList.toggle('opacity-100', el.checked);
        modal.classList.toggle('pointer-events-auto', el.checked);
        modal.classList.toggle('opacity-0', !el.checked);
        modal.classList.toggle('pointer-events-none', !el.checked);
    });
});
</script>
@endsection