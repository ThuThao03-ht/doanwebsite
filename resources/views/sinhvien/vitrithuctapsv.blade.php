@extends('layouts.app')

@section('title', 'Vị trí thực tập')

@section('content')
<!-- Vị trí thực tập -->
<section class="bg-white rounded-xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold flex items-center gap-2 text-[#4A7FA7]">
            <i class="fas fa-briefcase"></i> Vị trí thực tập
        </h3>

        <form method="GET" class="flex items-center gap-2">
            <select name="trang_thai" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">-- Tất cả --</option>
                <option value="con_han" {{ request('trang_thai') == 'con_han' ? 'selected' : '' }}>Còn hạn</option>
                <option value="het_han" {{ request('trang_thai') == 'het_han' ? 'selected' : '' }}>Hết hạn</option>
            </select>

            <button type="submit"
                class="bg-[#4A7FA7] text-white px-3 py-2 rounded-lg text-sm flex items-center gap-1 hover:bg-[#3a6a8d] transition">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border-t">
            <thead class="border-b text-sm text-gray-600">
                <tr>
                    <th class="px-4 py-2 text-left whitespace-nowrap">Mã</th>
                    <th class="px-4 py-2 text-left whitespace-nowrap">Vị trí</th>
                    <th class="px-4 py-2 text-left whitespace-nowrap">Doanh nghiệp</th>
                    <th class="px-4 py-2 text-left whitespace-nowrap">Số lượng</th>
                    <th class="px-4 py-2 text-left whitespace-nowrap">Trạng thái</th>
                    <th class="px-4 py-2 text-left whitespace-nowrap">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($viTriThucTap as $vt)
                @php
                $isHighlight = isset($highlightId) && $highlightId == $vt->vitri_id;
                @endphp
                <tr id="vitri-row-{{ $vt->vitri_id }}" class="border-b hover:bg-gray-50 transition 
                               {{ $isHighlight ? 'bg-yellow-100 ring-2 ring-yellow-400' : '' }}">
                    <td class="px-4 py-2">{{ $vt->ma_vitri ?? 'VT-' . $vt->vitri_id }}</td>
                    <td class="px-4 py-2">{{ $vt->ten_vitri }}</td>
                    <td class="px-4 py-2">{{ $vt->doanhNghiep->ten_dn ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                            {{ $vt->soluong }} chỗ
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        @if($vt->trang_thai === 'con_han')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Còn hạn
                        </span>
                        @else
                        <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-semibold">
                            Hết hạn
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex items-center gap-2">
                        <button
                            class="btn-xem-vitri bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-md flex items-center gap-1"
                            data-id="{{ $vt->vitri_id }}">
                            <i class="fas fa-eye"></i> Xem
                        </button>

                        <form action="{{ route('sinhvien.vitri_sinhvien.dangky') }}" method="POST">
                            @csrf
                            <input type="hidden" name="vitri_id" value="{{ $vt->vitri_id }}">
                            <button type="submit"
                                class="bg-[#4A7FA7] hover:bg-[#3a6a8d] text-white px-3 py-1 rounded-md text-sm flex items-center gap-1">
                                <i class="fas fa-check"></i> Đăng ký
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">Không có vị trí nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $viTriThucTap->links('pagination::bootstrap-5') }}

    </div>

</section>

<!-- Modal xem vị trí -->
<div id="modalVT_overlay"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-96 p-6 relative transform transition-all duration-300 scale-95 opacity-0"
        id="modalVT_box">
        <!-- Nút đóng -->
        <button id="modalVT_closeTop" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
            <i class="fas fa-times fa-lg"></i>
        </button>

        <!-- Header -->
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-briefcase text-[#4A7FA7] text-2xl"></i>
            <h3 class="text-xl font-bold text-[#4A7FA7]">Chi tiết vị trí</h3>
        </div>

        <!-- Thông tin -->
        <div class="space-y-2 mb-4">
            <p><i class="fas fa-tag text-gray-400 mr-2"></i><strong>Tên vị trí:</strong> <span id="vt_ten"></span></p>
            <p><i class="fas fa-align-left text-gray-400 mr-2"></i><strong>Mô tả:</strong> <span id="vt_mota"></span>
            </p>
            <p><i class="fas fa-list-ul text-gray-400 mr-2"></i><strong>Yêu cầu:</strong> <span id="vt_yeucau"></span>
            </p>
            <p><i class="fas fa-users text-gray-400 mr-2"></i><strong>Số lượng:</strong> <span id="vt_soluong"></span>
            </p>
            <p><i class="fas fa-user-check text-gray-400 mr-2"></i><strong>Đã đăng ký:</strong> <span
                    id="vt_dadangky"></span></p>
            <p><i class="fas fa-circle text-gray-400 mr-2"></i><strong>Trạng thái:</strong> <span
                    id="vt_trangthai"></span></p>
        </div>

        <hr class="my-3">

        <!-- Doanh nghiệp -->
        <div class="space-y-2">
            <h4 class="font-semibold flex items-center gap-2 text-[#4A7FA7]">
                <i class="fas fa-building"></i> Thông tin doanh nghiệp
            </h4>
            <p><i class="fas fa-building text-gray-400 mr-2"></i><strong>Tên DN:</strong> <span id="vt_tenDN"></span>
            </p>
            <p><i class="fas fa-envelope text-gray-400 mr-2"></i><strong>Email:</strong> <span id="vt_emailDN"></span>
            </p>
            <p><i class="fas fa-phone text-gray-400 mr-2"></i><strong>Liên hệ:</strong> <span id="vt_lienhe"></span></p>
            <p><i class="fas fa-globe text-gray-400 mr-2"></i><strong>Website:</strong> <span id="vt_website"></span>
            </p>
        </div>

        <div class="flex justify-end mt-4">
            <button id="modalVT_closeBottom" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Đóng</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('modalVT_overlay');
    const box = document.getElementById('modalVT_box');
    const closeTop = document.getElementById('modalVT_closeTop');
    const closeBottom = document.getElementById('modalVT_closeBottom');

    function showModalVT() {
        overlay.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideModalVT() {
        box.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 200);
    }

    closeTop.addEventListener('click', hideModalVT);
    closeBottom.addEventListener('click', hideModalVT);

    // --- Xem chi tiết vị trí (AJAX) ---
    document.querySelectorAll('.btn-xem-vitri').forEach(btn => {
        btn.addEventListener('click', async function() {
            const vitri_id = this.dataset.id;
            try {
                const res = await fetch(`/sinhvien/vitri-sv/${vitri_id}`);
                if (!res.ok) throw new Error('Lỗi tải dữ liệu');
                const data = await res.json();
                const v = data.vitri;
                const dn = v.doanhNghiep || v.doanh_nghiep || {};

                document.getElementById('vt_ten').innerText = v.ten_vitri ?? '-';
                document.getElementById('vt_mota').innerText = v.mo_ta ?? '-';
                document.getElementById('vt_yeucau').innerText = v.yeu_cau ?? '-';
                document.getElementById('vt_soluong').innerText = v.soluong ?? 0;
                document.getElementById('vt_dadangky').innerText = v.so_luong_da_dangky ??
                    0;
                document.getElementById('vt_trangthai').innerText = v.trang_thai ===
                    'con_han' ? 'Còn hạn' : 'Hết hạn';
                document.getElementById('vt_tenDN').innerText = dn.ten_dn ?? '-';
                document.getElementById('vt_emailDN').innerText = dn.email ?? '-';
                document.getElementById('vt_lienhe').innerText = dn.lien_he ?? '-';
                document.getElementById('vt_website').innerText = dn.website ?? '-';

                showModalVT();
            } catch (err) {
                console.error('❌ Lỗi khi tải chi tiết vị trí:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể xem chi tiết',
                    text: 'Đã có lỗi xảy ra khi tải thông tin vị trí.',
                });
            }
        });
    });

    // --- Hiệu ứng cuộn tới dòng highlight ---
    const highlightRow = document.querySelector('tr.ring-yellow-400');
    if (highlightRow) {
        highlightRow.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        // Sau 3 giây làm mờ hiệu ứng highlight dần
        setTimeout(() => {
            highlightRow.classList.remove('ring-yellow-400', 'bg-yellow-100');
            highlightRow.classList.add('bg-white');
        }, 3000);
    }

    // --- Hiển thị thông báo SweetAlert ---
    const successMessage = "{{ session('success') ?? '' }}";
    const errorMessage = "{{ session('error') ?? '' }}";
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: successMessage
        });
    }
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: errorMessage
        });
    }
});

// --- Highlight theo query id trên URL (ví dụ ?id=6) ---
const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');
if (id) {
    const row = document.querySelector(`#vitri-row-${id}`);
    if (row) {
        row.classList.add('bg-yellow-100', 'ring-2', 'ring-yellow-400');
        row.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        setTimeout(() => {
            row.classList.remove('ring-yellow-400', 'bg-yellow-100');
            row.classList.add('bg-white');
        }, 5000);
    }
}
</script>


@endsection