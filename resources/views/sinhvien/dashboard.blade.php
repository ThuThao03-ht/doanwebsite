@extends('layouts.app')

@section('content')
<main class="p-6 flex flex-col gap-6 bg-gray-50 min-h-screen">

    <!-- === Thống kê nhanh === -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Vị trí đang mở -->
        <div class="p-6 rounded-xl shadow-lg bg-[#4A7FA7] text-white">
            <div class="flex justify-between items-center mb-3">
                <div class="text-sm font-medium opacity-90">Vị trí đang mở</div>
                <i class="fas fa-briefcase text-3xl opacity-80"></i>
            </div>
            <div class="text-4xl font-bold mb-1">{{ $countViTriMo }}</div>
            <div class="text-xs opacity-80">Đang tuyển dụng</div>
        </div>

        <!-- Đăng ký của bạn -->
        <div class="p-6 rounded-xl shadow-lg bg-[#4A7FA7] text-white">
            <div class="flex justify-between items-center mb-3">
                <div class="text-sm font-medium opacity-90">Đăng ký của bạn</div>
                <i class="fas fa-file-signature text-3xl opacity-80"></i>
            </div>
            <div class="text-4xl font-bold mb-1">{{ $countDangKy }}</div>
            @php
            $latestDangKy = $dangKyList->first();

            if($latestDangKy) {
            $statusText = match($latestDangKy->trang_thai) {
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet' => 'Đã duyệt',
            'tu_choi' => 'Bị từ chối',
            'dang_thuctap' => 'Đang thực tập',
            'hoan_thanh' => 'Hoàn thành',
            default => 'Chưa xác định',
            };

            $statusColor = match($latestDangKy->trang_thai) {
            'cho_duyet' => 'text-yellow-300',
            'da_duyet' => 'text-green-300',
            'tu_choi' => 'text-red-300',
            'dang_thuctap' => 'text-blue-300',
            'hoan_thanh' => 'text-gray-200',
            default => 'text-gray-200',

            };
            } else {
            $statusText = 'Chưa đăng ký';
            $statusColor = 'text-gray-400';
            }
            @endphp

            <div class="text-xs opacity-80 {{ $statusColor }}">{{ $statusText }}</div>


        </div>

        <!-- Thông báo mới -->
        <div class="p-6 rounded-xl shadow-lg bg-[#4A7FA7] text-white">



            <div class="flex justify-between items-center mb-3">
                <div class="text-sm font-medium opacity-90">Thông báo mới</div>
                <i class="fas fa-bell text-3xl opacity-80"></i>
            </div>




            <div class="text-4xl font-bold mb-1">{{ $countThongBaoMoi }}</div>
            <div class="text-xs opacity-80">Chưa đọc</div>
        </div>
    </section>

    <!-- === Nội dung chính === -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Hàng 1 -->
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
                            <td class="px-4 py-2">{{ $dk->created_at->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ ucfirst($dk->trang_thai ?? 'chờ xử lý') }}
                                </span>
                            </td>
                            <td class="flex items-center gap-2">
                                <button
                                    class="btn-xem-dangky bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-md text-gray-700"
                                    data-id="{{ $dk->dk_id }}">
                                    <i class="fas fa-eye"></i>
                                </button>

                                @if($dk->trang_thai === 'cho_duyet')
                                <form method="POST" action="{{ route('sinhvien.dangky.huy', $dk->dk_id) }}"
                                    class="form-huy">
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
                            <td colspan="5" class="text-center py-4 text-gray-500">Chưa có đăng ký nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Thông báo mới -->
        <section class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-[#4A7FA7]">
                <i class="fas fa-bell"></i> Thông báo mới
            </h3>

            @forelse($thongBaoList as $tb)
            <a href="{{ route('sinhvien.thongbao.xem', $tb->tb_id) }}"
                class="border rounded-lg p-3 mb-2 flex items-start gap-2 hover:bg-gray-50 transition">
                <i class="fas fa-info-circle text-[#4A7FA7] mt-1"></i>
                <div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $tb->tieude }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($tb->ngay_gui)->format('d-m-Y') }}
                    </div>
                </div>
            </a>
            @empty
            <p class="text-sm text-gray-500 text-center">Không có thông báo mới</p>
            @endforelse

            @if($thongBaoList->count() > 0)
            <div class="text-right mt-3">
                <a href="{{ route('sinhvien.thongbao.danhsach') }}"
                    class="text-sm text-[#4A7FA7] font-medium hover:underline">
                    Xem tất cả
                </a>
            </div>
            @endif

        </section>




        <!-- Hàng 2 -->
        <!-- Vị trí thực tập -->
        <section class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold flex items-center gap-2 text-[#4A7FA7]">
                    <i class="fas fa-briefcase"></i> Vị trí thực tập
                </h3>
                <form method="GET" class="flex items-center gap-2">
                    <select name="trang_thai" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="con_han" {{ request('trang_thai') == 'con_han' ? 'selected' : '' }}>Còn hạn
                        </option>
                        <option value="het_han" {{ request('trang_thai') == 'het_han' ? 'selected' : '' }}>Hết hạn
                        </option>
                    </select>

                    <button type="submit"
                        class="bg-[#4A7FA7] text-white px-3 py-2 rounded-lg text-sm flex items-center gap-1">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border-t">
                    <thead class="border-b text-sm text-gray-600">
                        <tr>
                            <th class="px-4 py-2 text-left">Mã</th>
                            <th class="px-4 py-2 text-left">Vị trí</th>
                            <th class="px-4 py-2 text-left">Doanh nghiệp</th>
                            <th class="px-4 py-2 text-left">Số lượng</th>
                            <th class="px-4 py-2 text-left">Trạng thái</th>
                            <th class="px-4 py-2 text-left">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($viTriThucTap as $vt)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $vt->ma_vitri ?? 'VT-' . $vt->id }}</td>
                            <td class="px-4 py-2">{{ $vt->ten_vitri }}</td>
                            <td class="px-4 py-2">{{ $vt->doanhNghiep->ten_dn ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                    {{ $vt->soluong }} chỗ
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if($vt->trang_thai === 'con_han')
                                <span
                                    class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Còn
                                    hạn</span>
                                @else
                                <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-semibold">Hết
                                    hạn</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('sinhvien.vitri.dangky') }}">
                                    @csrf
                                    <input type="hidden" name="vitri_id" value="{{ $vt->vitri_id }}">
                                    <button type="submit"
                                        class="bg-[#4A7FA7] hover:bg-[#3a6a8d] text-white px-3 py-1 rounded-md text-sm">
                                        <!-- <i class="fas fa-check"></i> -->
                                        Đăng ký
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
        </section>


        <!-- Nhiệm vụ nhanh -->
        <section class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-[#4A7FA7]">
                <i class="fas fa-bolt"></i> Nhiệm vụ nhanh
            </h3>
            <div class="flex flex-col gap-2">
                <a href="{{ route('sinhvien.baocao.index') }}"
                    class="border px-3 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-upload text-[#4A7FA7]"></i> Nộp báo cáo
                </a>

                <a href="{{ route('sinhvien.danhgia.index') }}"
                    class="border px-3 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-star text-[#4A7FA7]"></i> Xem đánh giá
                </a>

                <a href="{{ route('sinhvien.vitri_sinhvien.list') }}"
                    class="border px-3 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-briefcase text-[#4A7FA7]"></i> Danh sách vị trí thực tập
                </a>

                <a href="{{ route('sinhvien.tiendo.index') }}"
                    class="border px-3 py-2 rounded-md hover:bg-gray-50 flex items-center gap-2">
                    <i class="fas fa-tasks text-[#4A7FA7]"></i> Xem tiến độ thực tập
                </a>
            </div>
        </section>

    </div>



    <!-- Hộp trạng thái thực tập -->
    <div class="bg-white p-6 rounded-xl shadow-md mt-6">

        <!-- Tiêu đề vị trí -->
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    {{ $latestDangKy->viTriThucTap->ten_vitri ?? 'Chưa có vị trí thực tập' }}
                </h2>

                <p class="text-gray-600 flex items-center mt-1">
                    <i class="fa-solid fa-building mr-2" style="color: #4a7fa7;"></i>
                    {{ $latestDangKy->viTriThucTap->doanhNghiep->ten_dn ?? 'Chưa có đăng ký ' }}
                </p>

                <p class="text-gray-600 flex items-center mt-1">
                    <i class="fa-solid fa-calendar-days mr-2"></i>
                    Đăng ký:
                    @if($latestDangKy)
                    {{ \Carbon\Carbon::parse($latestDangKy->created_at)->format('d-m-Y') }}
                    @else
                    Chưa có đăng ký
                    @endif
                </p>

            </div>


            <!-- Phần trăm -->
            <div class="bg-red-100 text-red-600 font-bold text-lg px-3 py-1 rounded-xl inline-block">
                {{ $phanTramTienDo ?? 0 }}%
            </div>


        </div>


        <!-- Trạng thái -->
        <div class="flex items-center mb-1">
            @switch($textTienDo)
            @case('Chờ duyệt')
            <i class="fa-solid fa-clock text-yellow-500 mr-2"></i>
            @break
            @case('Đã duyệt')
            <i class="fa-solid fa-circle-check text-green-600 mr-2"></i>
            @break
            @case('Thực tập')
            <i class="fa-solid fa-briefcase text-blue-600 mr-2"></i>
            @break
            @case('Hoàn thành')
            <i class="fa-solid fa-trophy text-emerald-600 mr-2"></i>
            @break
            @case('Bị từ chối')
            <i class="fa-solid fa-xmark text-red-600 mr-2"></i>
            @break
            @default
            <i class="fa-solid fa-circle-info text-gray-500 mr-2"></i>
            @endswitch

            <span class="text-sm font-semibold 
            @if($textTienDo == 'Bị từ chối') text-red-600 @else text-blue-600 @endif">
                {{ $textTienDo ?? '...' }}
            </span>
        </div>

        @php
        $phanTram = $phanTramTienDo ?? 0;
        $mau = $mauTienDo ?? '#9ca3af';
        $style = "width: {$phanTram}%; background-color: {$mau};";
        @endphp

        <!-- Progress bar -->
        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden mb-3">
            <div class="h-3 rounded-full transition-all" style="<?php echo $style; ?>">
            </div>
        </div>


        <!-- Thông báo trạng thái -->
        <div class="flex items-start text-sm mb-6">
            <i class="fa-solid fa-circle-info mr-2 mt-0.5"></i>

            @switch($textTienDo)
            @case('Chờ duyệt')
            <span class="text-yellow-600">Đơn đăng ký đang trong quá trình chờ phê duyệt</span>
            @break

            @case('Đã duyệt')
            <span class="text-green-600">Đơn đã được duyệt, chuẩn bị bắt đầu thực tập</span>
            @break

            @case('Thực tập')
            <span class="text-blue-600">Đang trong quá trình thực tập tại doanh nghiệp</span>
            @break

            @case('Hoàn thành')
            <span class="text-emerald-600">Chúc mừng! Bạn đã hoàn thành thực tập</span>
            @break

            @case('Bị từ chối')
            <span class="text-red-600">Đơn đăng ký bị từ chối bởi doanh nghiệp</span>
            @break

            @default
            <span class="text-gray-600">Chưa có thông tin thực tập</span>
            @endswitch
        </div>

        @php
        $trangThaiHienTai = $textTienDo ?? 'Chưa đăng ký';
        @endphp

        <hr class="my-4 border-gray-400">

        <!-- Timeline hiển thị tiến độ -->
        <?php
    // Màu theo trạng thái
    $colors = [
        'Chờ duyệt'   => '#F59E0B', // vàng
        'Đã duyệt'    => '#1D4ED8', // xanh dương
        'Thực tập'    => '#16A34A', // xanh lá
        'Hoàn thành'  => '#4a7fa7', // tím
    ];

    // % tiến độ
    $percentMap = [
        'Chờ duyệt'  => 0,
        'Đã duyệt'   => 33,
        'Thực tập'   => 66,
        'Hoàn thành' => 100,
    ];

    $percent = $percentMap[$trangThaiHienTai] ?? 0;
    $activeColor = $colors[$trangThaiHienTai] ?? '#1D4ED8';

    // Danh sách các bước
    $steps = [
        ['label' => 'Chờ duyệt',  'icon' => 'fa-clock'],
        ['label' => 'Đã duyệt',   'icon' => 'fa-circle-check'],
        ['label' => 'Thực tập',   'icon' => 'fa-briefcase'],
        ['label' => 'Hoàn thành', 'icon' => 'fa-trophy'],
    ];
?>

        <div class="relative w-full mt-10 mb-6">

            <!-- Line background -->
            <div class="absolute top-10 left-0 right-0 mx-auto w-full h-1 bg-gray-300 z-0"></div>

            <!-- Progress Line -->
            <div class="absolute top-10 left-0 h-1 transition-all z-10"
                style="width: <?= $percent ?>%; background-color: <?= $activeColor ?>;">
            </div>

            <!-- Steps -->
            <div class="flex justify-between items-center text-center text-sm relative z-20">

                <?php foreach ($steps as $step): 
            $isActive = array_search($step['label'], array_column($steps, 'label')) 
                        <= array_search($trangThaiHienTai, array_column($steps, 'label'));

            $circleColor = $isActive ? $activeColor : '#9CA3AF';
            $bgCircle = $isActive ? 'bg-gray-100' : 'bg-gray-200';
            $textColor = $isActive ? $activeColor : '#9CA3AF';
        ?>

                <div class="flex flex-col items-center">
                    <div class="rounded-full w-20 h-20 flex items-center justify-center mb-2 <?= $bgCircle ?>">

                        <!-- <div class="rounded-full p-4 mb-2 <?= $bgCircle ?>"> -->
                        <i class="fa-solid <?= $step['icon'] ?> text-xl" style="color: <?= $circleColor ?>;"></i>
                    </div>
                    <span style="color: <?= $textColor ?>;"><?= $step['label'] ?></span>
                </div>

                <?php endforeach; ?>

            </div>
        </div>



    </div>


    </div>






</main>

<!-- Modal xem vị trí -->
<div id="modalViTri" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-96 p-6 relative transform transition-transform duration-300 scale-90">
        <!-- Nút đóng trên góc phải -->
        <button id="closeModalTop" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
            <i class="fas fa-times fa-lg"></i>
        </button>

        <!-- Header modal -->
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-briefcase text-[#4A7FA7] text-2xl"></i>
            <h3 class="text-xl font-bold text-[#4A7FA7]">Chi tiết vị trí</h3>
        </div>

        <!-- Thông tin vị trí -->
        <div class="space-y-2 mb-4">
            <p><i class="fas fa-tag text-gray-400 mr-2"></i><strong>Tên vị trí:</strong> <span
                    id="modalTenViTri"></span></p>
            <p><i class="fas fa-align-left text-gray-400 mr-2"></i><strong>Mô tả:</strong> <span id="modalMoTa"></span>
            </p>
            <p><i class="fas fa-list-ul text-gray-400 mr-2"></i><strong>Yêu cầu:</strong> <span id="modalYeuCau"></span>
            </p>
            <p><i class="fas fa-users text-gray-400 mr-2"></i><strong>Số lượng:</strong> <span id="modalSoLuong"></span>
            </p>
            <p><i class="fas fa-user-check text-gray-400 mr-2"></i><strong>Đã đăng ký:</strong> <span
                    id="modalDaDangKy"></span></p>
            <p><i class="fas fa-circle text-gray-400 mr-2"></i><strong>Trạng thái:</strong> <span
                    id="modalTrangThai"></span></p>
        </div>

        <hr class="my-3">

        <!-- Thông tin doanh nghiệp -->
        <div class="space-y-2">
            <h4 class="font-semibold flex items-center gap-2 text-[#4A7FA7]">
                <i class="fas fa-building"></i> Thông tin doanh nghiệp
            </h4>
            <p><i class="fas fa-building text-gray-400 mr-2"></i><strong>Tên DN:</strong> <span id="modalTenDN"></span>
            </p>
            <p><i class="fas fa-envelope text-gray-400 mr-2"></i><strong>Email:</strong> <span id="modalEmailDN"></span>
            </p>
            <p><i class="fas fa-phone text-gray-400 mr-2"></i><strong>Liên hệ:</strong> <span id="modalLienHe"></span>
            </p>
            <p><i class="fas fa-globe text-gray-400 mr-2"></i><strong>Website:</strong> <span id="modalWebsite"></span>
            </p>
        </div>

        <!-- Nút đóng dưới góc phải -->
        <div class="flex justify-end mt-4">
            <button id="closeModalBottom" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Đóng</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalViTri');
    const closeModalTop = document.getElementById('closeModalTop');
    const closeModalBottom = document.getElementById('closeModalBottom');

    closeModalTop.addEventListener('click', () => modal.classList.add('hidden'));
    closeModalBottom.addEventListener('click', () => modal.classList.add('hidden'));

    document.querySelectorAll('.btn-xem-vitri').forEach(btn => {
        btn.addEventListener('click', function() {
            const vitriId = this.dataset.id;

            fetch(`/sinhvien/vitri/${vitriId}`)
                .then(res => res.json())
                .then(data => {
                    const v = data.vitri;
                    document.getElementById('modalTenViTri').innerText = v.ten_vitri;
                    document.getElementById('modalMoTa').innerText = v.mo_ta;
                    document.getElementById('modalYeuCau').innerText = v.yeu_cau;
                    document.getElementById('modalSoLuong').innerText = v.soluong;
                    document.getElementById('modalDaDangKy').innerText = v
                        .so_luong_da_dangky;
                    document.getElementById('modalTrangThai').innerText = v.trang_thai ===
                        'con_han' ? 'Còn hạn' : 'Hết hạn';
                    document.getElementById('modalTenDN').innerText = v.doanh_nghiep
                        .ten_dn ?? '-';
                    document.getElementById('modalEmailDN').innerText = v.doanh_nghiep
                        .email ?? '-';
                    document.getElementById('modalLienHe').innerText = v.doanh_nghiep
                        .lien_he ?? '-';
                    document.getElementById('modalWebsite').innerText = v.doanh_nghiep
                        .website ?? '-';
                    modal.classList.remove('hidden');
                });
        });
    });
});
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener("DOMContentLoaded", function() {
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
    }); // Lấy dữ liệu session từ Laravel qua JS
    const successMessage = "{{ session('success') ?? '' }}";
    const errorMessage = "{{ session('error') ?? '' }}";

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: successMessage,
        });
    }

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: errorMessage,
        });
    }
});
</script>

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
            <p><i class="fas fa-user text-[#4A7FA7] mr-2"></i><strong>Sinh viên:</strong> <span id="dkTenSV"></span></p>
            <p><i class="fas fa-envelope text-[#4A7FA7] mr-2"></i><strong>Email:</strong> <span id="dkEmailSV"></span>
            </p>
            <p><i class="fas fa-briefcase text-[#4A7FA7] mr-2"></i><strong>Vị trí:</strong> <span id="dkViTri"></span>
            </p>
            <p><i class="fas fa-building text-[#4A7FA7] mr-2"></i><strong>Doanh nghiệp:</strong> <span
                    id="dkDoanhNghiep"></span></p>
            <p><i class="fas fa-chalkboard-teacher text-[#4A7FA7] mr-2"></i><strong>Giảng viên hướng dẫn:</strong> <span
                    id="dkGV"></span></p>
            <p><i class="fas fa-info-circle text-[#4A7FA7] mr-2"></i><strong>Trạng thái:</strong> <span
                    id="dkTrangThai"></span></p>
            <p><i class="fas fa-calendar-alt text-[#4A7FA7] mr-2"></i><strong>Ngày đăng ký:</strong> <span
                    id="dkNgayDangKy"></span></p>
        </div>

        <div class="flex justify-end mt-4">
            <button id="closeModalDangKyBottom"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 flex items-center gap-2">
                <i class="fas fa-times"></i> Đóng
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalDangKy');
    const closeTop = document.getElementById('closeModalDangKyTop');
    const closeBottom = document.getElementById('closeModalDangKyBottom');

    closeTop.addEventListener('click', () => modal.classList.add('hidden'));
    closeBottom.addEventListener('click', () => modal.classList.add('hidden'));

    document.querySelectorAll('.btn-xem-dangky').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/sinhvien/dangky/${id}`)
                .then(res => res.json())
                .then(data => {
                    const dk = data.dangky;
                    document.getElementById('dkTenSV').innerText = dk.sinh_vien.ho_ten;
                    document.getElementById('dkEmailSV').innerText = dk.sinh_vien.email;
                    document.getElementById('dkViTri').innerText = dk.vi_tri_thuc_tap
                        .ten_vitri;
                    document.getElementById('dkDoanhNghiep').innerText = dk.vi_tri_thuc_tap
                        .doanh_nghiep.ten_dn ?? '-';
                    document.getElementById('dkGV').innerText = dk.phan_cong_giang_viens
                        .map(pc => pc.giang_vien?.ho_ten)
                        .filter(name => name) // loại bỏ null
                        .join(', ') || 'Chưa phân công!';

                    document.getElementById('dkTrangThai').innerText = dk.trang_thai;
                    document.getElementById('dkNgayDangKy').innerText = new Date(dk
                        .created_at).toLocaleDateString();
                    modal.classList.remove('hidden');
                });
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    const successMsg = "{{ session('success') ?? '' }}";
    const errorMsg = "{{ session('error') ?? '' }}";

    if (successMsg) Swal.fire('Thành công', successMsg, 'success');
    if (errorMsg) Swal.fire('Lỗi', errorMsg, 'error');
});
</script>

<style>
td {
    white-space: nowrap;
    /* Không cho chữ xuống dòng */
    vertical-align: middle;
    /* Căn giữa theo chiều dọc */
}

.table td,
.table th {
    display: flex;
    align-items: center;
    gap: 6px;
    /* Khoảng cách giữa icon và chữ */
}

.table th {
    white-space: nowrap;
}

.table .btn,
.table .badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    white-space: nowrap;
}
</style>

<!-- Chatbot Icon -->
<div id="chatbotButton"
    class="fixed bottom-6 left-6 rounded-full shadow-xl cursor-pointer z-50 p-2 bg-white hover:scale-110 transition transform">
    <img src="{{ asset('images/chatbot.png') }}" alt="Chatbot" class="w-14 h-14 object-cover rounded-full">
</div>



<!-- Chatbot Window -->
<div id="chatbotWindow"
    class="fixed bottom-20 left-6 w-80 bg-white rounded-2xl shadow-2xl hidden flex flex-col z-50 border border-gray-200">
    <div class="bg-[#4A7FA7] text-white p-3 rounded-t-2xl flex justify-between items-center">
        <h4 class="font-semibold"><i class="fas fa-comments"></i> Trợ lý thực tập</h4>
        <button id="chatbotClose"><i class="fas fa-times"></i></button>
    </div>
    <div id="chatbotMessages" class="p-3 h-96 overflow-y-auto text-sm text-gray-800 space-y-2">
        <div class="bg-gray-100 p-2 rounded-lg">👋 Xin chào! Tôi có thể giúp bạn tìm vị trí thực tập phù hợp.</div>
    </div>
    <div class="p-3 border-t flex items-center gap-2">
        <input id="chatbotInput" type="text" placeholder="Nhập câu hỏi..."
            class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#4A7FA7]">
        <button id="chatbotSend" class="bg-[#4A7FA7] text-white px-3 py-2 rounded-lg hover:bg-[#3a6a8d]">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('chatbotButton');
    const windowChat = document.getElementById('chatbotWindow');
    const closeBtn = document.getElementById('chatbotClose');
    const sendBtn = document.getElementById('chatbotSend');
    const input = document.getElementById('chatbotInput');
    const messages = document.getElementById('chatbotMessages');

    let typingDiv = null; // phần hiển thị "Đang phản hồi..."

    btn.addEventListener('click', () => windowChat.classList.toggle('hidden'));
    closeBtn.addEventListener('click', () => windowChat.classList.add('hidden'));

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', e => {
        if (e.key === 'Enter') sendMessage();
    });

    function appendMessage(text, from = 'user') {
        const div = document.createElement('div');
        div.className = from === 'user' ?
            'bg-[#4A7FA7] text-white p-2 rounded-lg self-end ml-auto max-w-[80%]' :
            'bg-gray-100 p-2 rounded-lg max-w-[80%]';

        if (from === 'bot') {
            div.innerHTML = text;
        } else {
            div.innerText = text;
        }

        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function showTypingIndicator() {
        typingDiv = document.createElement('div');
        typingDiv.className = 'bg-gray-100 text-gray-600 p-2 rounded-lg italic text-sm flex items-center gap-2';
        typingDiv.innerHTML = `
            <span>Đang soạn phản hồi</span>
            <span class="typing-dots flex gap-1">
                <span class="dot animate-bounce">.</span>
                <span class="dot animate-bounce delay-150">.</span>
                <span class="dot animate-bounce delay-300">.</span>
            </span>
        `;
        messages.appendChild(typingDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    function removeTypingIndicator() {
        if (typingDiv) {
            typingDiv.remove();
            typingDiv = null;
        }
    }

    function sendMessage() {
        const msg = input.value.trim();
        if (!msg) return;
        appendMessage(msg, 'user');
        input.value = '';

        showTypingIndicator(); //  Hiển thị “Đang phản hồi...”

        fetch('{{ route("sinhvien.chatbot.ask") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: msg
                })
            })
            .then(res => res.json())
            .then(data => {
                removeTypingIndicator(); //  Xóa khi có phản hồi
                appendMessage(data.reply || 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn.', 'bot');
            })
            .catch(() => {
                removeTypingIndicator();
                appendMessage('Có lỗi xảy ra khi kết nối chatbot.', 'bot');
            });
    }
});
</script>

<style>
@keyframes bounce {

    0%,
    80%,
    100% {
        transform: scale(0);
    }

    40% {
        transform: scale(1);
    }
}

.typing-dots .dot {
    animation: bounce 1.4s infinite;
    font-weight: bold;
    font-size: 18px;
    line-height: 0;
}

.typing-dots .dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots .dot:nth-child(3) {
    animation-delay: 0.4s;
}
</style>

@endsection