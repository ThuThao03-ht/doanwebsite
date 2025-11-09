@extends('layouts.app')

@section('title', 'Đăng ký thực tập của tôi')

@section('content')
<div class="p-6 space-y-8">
    <!-- Đăng ký của tôi -->
    <section class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-[#4A7FA7]">
            <i class="fas fa-clipboard-list"></i> Đăng ký của tôi
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border-t">
                <thead class="border-b text-sm text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">Mã ĐK</th>
                        <th class="px-4 py-2 text-left">Vị trí</th>
                        <th class="px-4 py-2 text-left">Ngày đăng ký</th>
                        <th class="px-4 py-2 text-left">Trạng thái</th>
                        <th class="px-4 py-2 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($dangKyList as $dk)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $dk->dk_id ?? 'DK-' . $dk->id }}</td>
                        <td class="px-4 py-2">{{ $dk->viTriThucTap->ten_vitri ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $dk->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            @php
                            $statusColors = [
                            'cho_duyet' => 'bg-yellow-100 text-yellow-700',
                            'duyet' => 'bg-green-100 text-green-700',
                            'tu_choi' => 'bg-red-100 text-red-700',
                            'huy' => 'bg-gray-100 text-gray-700',
                            'ket_thuc' => 'bg-blue-100 text-blue-700',
                            ];
                            @endphp
                            <span
                                class="{{ $statusColors[$dk->trang_thai] ?? 'bg-yellow-100 text-yellow-700' }} px-3 py-1 rounded-full text-xs font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $dk->trang_thai ?? 'chờ xử lý')) }}
                            </span>
                        </td>
                        <td class="flex items-center justify-center gap-2 whitespace-nowrap">
                            <button
                                class="btn-xem-dangky bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-md text-gray-700"
                                data-id="{{ $dk->dk_id }}">
                                <i class="fas fa-eye"></i>
                            </button>

                            @if($dk->trang_thai === 'cho_duyet')
                            <form class="form-huy" method="POST"
                                action="{{ route('sinhvien.dangky.huy', $dk->dk_id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            Chưa có đăng ký nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Modal xem đăng ký -->
<div id="modalDangKy" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-96 p-6 relative transform transition-transform duration-300 scale-90">
        <button id="closeModalDangKyTop" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
            <i class="fas fa-times fa-lg"></i>
        </button>

        <h3 class="text-xl font-bold text-[#4A7FA7] mb-4 flex items-center gap-2">
            <i class="fas fa-clipboard-list"></i> Chi tiết đăng ký
        </h3>

        <div class="space-y-2">
            <p><i class="fas fa-user text-[#4A7FA7] mr-2"></i><strong>Sinh viên:</strong>
                <span id="dkTenSV"></span>
            </p>
            <p><i class="fas fa-envelope text-[#4A7FA7] mr-2"></i><strong>Email:</strong>
                <span id="dkEmailSV"></span>
            </p>
            <p><i class="fas fa-briefcase text-[#4A7FA7] mr-2"></i><strong>Vị trí:</strong>
                <span id="dkViTri"></span>
            </p>
            <p><i class="fas fa-building text-[#4A7FA7] mr-2"></i><strong>Doanh nghiệp:</strong>
                <span id="dkDoanhNghiep"></span>
            </p>
            <p><i class="fas fa-chalkboard-teacher text-[#4A7FA7] mr-2"></i><strong>Giảng viên
                    hướng dẫn:</strong> <span id="dkGV"></span></p>
            <p><i class="fas fa-info-circle text-[#4A7FA7] mr-2"></i><strong>Trạng thái:</strong>
                <span id="dkTrangThai"></span>
            </p>
            <p><i class="fas fa-calendar-alt text-[#4A7FA7] mr-2"></i><strong>Ngày đăng ký:</strong>
                <span id="dkNgayDangKy"></span>
            </p>
        </div>

        <div class="flex justify-end mt-4">
            <button id="closeModalDangKyBottom"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 flex items-center gap-2">
                <i class="fas fa-times"></i> Đóng
            </button>
        </div>
    </div>
</div>

{{-- JS xử lý modal và hủy đăng ký --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalDangKy');
    const closeTop = document.getElementById('closeModalDangKyTop');
    const closeBottom = document.getElementById('closeModalDangKyBottom');

    closeTop.addEventListener('click', () => modal.classList.add('hidden'));
    closeBottom.addEventListener('click', () => modal.classList.add('hidden'));

    // Xem chi tiết đăng ký
    document.querySelectorAll('.btn-xem-dangky').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/sinhvien/dangky/${id}`)
                .then(res => res.json())
                .then(data => {
                    const dk = data.dangky;
                    document.getElementById('dkTenSV').innerText = dk.sinh_vien?.ho_ten ||
                        '-';
                    document.getElementById('dkEmailSV').innerText = dk.sinh_vien?.email ||
                        '-';
                    document.getElementById('dkViTri').innerText = dk.vi_tri_thuc_tap
                        ?.ten_vitri || '-';
                    document.getElementById('dkDoanhNghiep').innerText = dk.vi_tri_thuc_tap
                        ?.doanh_nghiep?.ten_dn || '-';
                    document.getElementById('dkGV').innerText = dk.phan_cong_giang_viens
                        ?.map(pc => pc.giang_vien?.ho_ten)
                        ?.filter(name => name)
                        ?.join(', ') || 'Chưa phân công!';
                    document.getElementById('dkTrangThai').innerText = dk.trang_thai;
                    document.getElementById('dkNgayDangKy').innerText = new Date(dk
                        .created_at).toLocaleDateString();
                    modal.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Lỗi khi tải chi tiết:', err);
                    Swal.fire('Lỗi', 'Không thể tải thông tin chi tiết!', 'error');
                });
        });
    });

    // Hủy đăng ký xác nhận
    document.querySelectorAll('.form-huy').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Xác nhận hủy?',
                text: "Bạn có chắc muốn hủy đăng ký này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Có, hủy!',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Thông báo thành công / lỗi
    const successMsg = "{{ session('success') ?? '' }}";
    const errorMsg = "{{ session('error') ?? '' }}";

    if (successMsg) Swal.fire('Thành công', successMsg, 'success');
    if (errorMsg) Swal.fire('Lỗi', errorMsg, 'error');
});
</script>
@endsection