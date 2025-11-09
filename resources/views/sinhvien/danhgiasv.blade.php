@extends('layouts.app')

@section('title', 'Đánh giá thực tập của tôi')

@section('content')
<div class="p-6 space-y-8">
    <!-- Đăng ký của tôi -->
    <section class="bg-white rounded-xl shadow p-6">
        <div class="container py-4">
            <h3 class="text-center mb-4 fw-bold text-[#4A7FA7]">Kết quả đánh giá thực tập của {{ $sinhvien->ho_ten }}
            </h3>

            @foreach ($dangkyList as $dk)
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><strong>Vị trí:</strong> {{ $dk->viTriThucTap->ten_vitri ?? 'Chưa có' }}</span>
                    <button class="btn btn-sm view-detail"
                        style="border:1px solid #4A7FA7; color:#4A7FA7; background-color:white;"
                        data-dk-id="{{ $dk->dk_id }}">
                        Xem chi tiết
                    </button>

                </div>
                <div class="card-body">
                    <table class="table table-bordered text-center align-middle mb-3">
                        <thead>
                            <tr>
                                <th colspan="4">Đánh giá Doanh nghiệp</th>
                            </tr>
                            <tr>
                                <th>Tên Doanh nghiệp</th>
                                <th>Điểm</th>
                                <th>Nhận xét</th>
                                <th>Ngày đánh giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($dk->danhGiaDoanhNghiep)
                            <tr>
                                <td>{{ $dk->danhGiaDoanhNghiep->doanhNghiep->ten_dn ?? '---' }}</td>
                                <td>{{ number_format($dk->danhGiaDoanhNghiep->diemso, 2) }}</td>
                                <td>{{ $dk->danhGiaDoanhNghiep->nhanxet }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($dk->danhGiaDoanhNghiep->ngay_danhgia)->format('d/m/Y') }}
                                </td>

                            </tr>
                            @else
                            <tr>
                                <td colspan="4">Chưa có đánh giá từ doanh nghiệp</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <table class="table table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th colspan="4">Đánh giá Giảng viên</th>
                            </tr>
                            <tr>
                                <th>Tên Giảng viên</th>
                                <th>Điểm</th>
                                <th>Nhận xét</th>
                                <th>Ngày đánh giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($dk->danhGiaGiangVien)
                            <tr>
                                <td>{{ $dk->danhGiaGiangVien->giangVien->ho_ten ?? '---' }}</td>
                                <td>{{ number_format($dk->danhGiaGiangVien->diemso, 2) }}</td>
                                <td>{{ $dk->danhGiaGiangVien->nhanxet }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($dk->danhGiaGiangVien->ngay_danhgia)->format('d/m/Y') }}
                                </td>

                            </tr>
                            @else
                            <tr>
                                <td colspan="4">Chưa có đánh giá từ giảng viên</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    @php
                    $dn_diem = $dk->danhGiaDoanhNghiep->diemso ?? null;
                    $gv_diem = $dk->danhGiaGiangVien->diemso ?? null;
                    $diem_tb = ($dn_diem !== null && $gv_diem !== null)
                    ? number_format(($dn_diem + $gv_diem) / 2, 2)
                    : '---';
                    @endphp
                    <p class="result-text">
                        <i class="bi bi-bar-chart-fill"></i>
                        Điểm trung bình của quá trình thực tập của bạn:
                        <span class="score-box">{{ $diem_tb }}</span>
                    </p>
                </div>
            </div>
            @endforeach
        </div>


        <!-- Modal xem chi tiết -->
        <div class="modal fade" id="modalChiTiet" tabindex="-1">
            <div class="modal-dialog modal-mb modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Chi tiết đánh giá</h5>
                        <button type="button" class="btn-close custom-grey" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalContent">Đang tải...</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.view-detail').forEach(btn => {
            btn.addEventListener('click', async () => {
                const dkId = btn.dataset.dkId;
                const res = await fetch(`/sinhvien/danhgia/${dkId}`);
                const data = await res.json();

                const dk = data.dangky;
                const diem_tb = data.diem_trung_binh ?
                    parseFloat(data.diem_trung_binh).toFixed(2) :
                    '---';

                let html = `
                        <h6><b>Vị trí:</b> ${dk.vi_tri_thuc_tap?.ten_vitri ?? '---'}</h6>
                        <hr>
                        <p><b>Doanh nghiệp:</b> ${dk.danh_gia_doanh_nghiep?.doanh_nghiep?.ten_dn ?? '---'}</p>
                        <p><b>Điểm DN:</b> ${dk.danh_gia_doanh_nghiep?.diemso ? parseFloat(dk.danh_gia_doanh_nghiep.diemso).toFixed(2) : '---'}</p>
                        <p><b>Giảng viên:</b> ${dk.danh_gia_giang_vien?.giang_vien?.ho_ten ?? '---'}</p>
                        <p><b>Điểm GV:</b> ${dk.danh_gia_giang_vien?.diemso ? parseFloat(dk.danh_gia_giang_vien.diemso).toFixed(2) : '---'}</p>
                        <p><b>Điểm trung bình:</b> <span class="score-box">${diem_tb}</span></p>
                    `;

                document.querySelector('#modalContent').innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalChiTiet')).show();
            });
        });
    });
    </script>

    <style>
    body {
        background: #f7f9fb;
    }

    .table thead {
        background-color: #4A7FA7;
        color: white;
    }

    .table th,
    .table td {
        vertical-align: middle;
        text-align: center;
        width: 25%;
    }

    .modal-header {
        background: #4A7FA7;
        color: #fff;
    }

    .btn-close.custom-grey {
        filter: invert(70%) grayscale(100%);
    }

    .score-box {
        font-weight: bold;
        color: #4A7FA7;
        font-size: 1.1rem;
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        background-color: #f1f5f9;
        border-bottom: 2px solid #e2e8f0;
    }

    .table {
        border-radius: 8px;
        overflow: hidden;
    }

    .result-text {
        text-align: left;
        margin-top: 10px;
        font-size: 1.1rem;
        color: #333;
    }

    .result-text i {
        color: #4A7FA7;
        font-size: 1.3rem;
        margin-right: 6px;
    }
    </style>
    </body>

    @endsection