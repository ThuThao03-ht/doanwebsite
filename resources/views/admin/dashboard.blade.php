@extends('layouts.admin')

@section('content')
<style>
body {
    background: #f0f2f5;
    font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

.container-fluid {
    padding: 24px;
    max-width: 1600px;
    margin: 0 auto;
}

/* ==== HEADER ==== */
.page-header {
    text-align: center;
    margin-bottom: 32px;
}

.page-header h2 {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.page-header h2 i {
    color: #5b7ce6;
}

.page-header p {
    color: #6b7280;
    font-size: 15px;
    margin: 0;
}

/* ==== ENHANCED STATS CARDS ==== */

.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    /* 3 ô mỗi hàng */
    gap: 20px;
}


/* Card */
.stat-item {
    /* flex: 1 1 260px; */
    width: 100%;
    padding: 20px 24px;
    border-radius: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border: none;
    position: relative;

    /* Màu nền pastel giống ảnh */
}

.stat-icon-box {
    width: 50px;
    height: 50px;
    background: #4A7FA7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    margin-right: 16px;
}

.stat-details .stat-number {
    font-size: 20px;
    font-weight: bold;
}

.stat-details .stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
}


.stat-item:hover::before {
    left: 100%;
}

.stat-item:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(91, 124, 230, 0.15),
        0 0 0 1px rgba(91, 124, 230, 0.1);
    border-color: rgba(91, 124, 230, 0.2);
}

/* Icon box */
.stat-icon-box {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #1e293b;
    background: rgba(255, 255, 255, 0.45);
    /* nền icon mờ giống ảnh */
    backdrop-filter: blur(4px);
}

.stat-item:hover .stat-icon-box {
    transform: scale(1.08) rotate(3deg);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.stat-item:nth-child(1) {
    background: #dbeafe;
    /* xanh dương nhạt */
}

.stat-item:nth-child(2) {
    background: #e0f2fe;
    /* xanh cyan nhạt */
}

.stat-item:nth-child(3) {
    background: #dcfce7;
    /* xanh lá nhạt */
}

.stat-item:nth-child(4) {
    background: #fce7f3;
    /* hồng pastel */
}

.stat-item:nth-child(5) {
    background: #fef9c3;
    /* vàng nhạt pastel */
}

.stat-item:nth-child(6) {
    background: #ede9fe;
    /* tím nhạt pastel */
}


.stat-details {
    flex: 1;
    position: relative;
    z-index: 2;
}

/* Text */
.stat-number {
    font-size: 26px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}

.stat-item:hover .stat-number {
    font-size: 36px;
}

.stat-label {
    font-size: 13px;
    color: #475569;
    font-weight: 500;
}

.stat-item:hover .stat-label {
    color: #5b7ce6;
}

/* Bottom accent line animation */
.stat-item::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, #fbbf24, #60a5fa, #a78bfa, #34d399);
    transition: width 0.4s ease;
    z-index: 3;
}

.stat-item:hover::after {
    width: 100%;
}

/* ==== SECTION TITLE ==== */
.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e5e7eb;
}

.section-header i {
    font-size: 20px;
    color: #5b7ce6;
}

.section-header h4 {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0;
}

/* ==== CHART CARDS ==== */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.chart-box {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.chart-box:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.chart-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
}

.chart-title i {
    font-size: 18px;
    color: #5b7ce6;
}

.chart-container {
    height: 320px;
    position: relative;
}

/* ==== COMBINED CHART ==== */
.chart-box-full {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
    margin-bottom: 32px;
}

.chart-container-full {
    height: 400px;
    position: relative;
}

/* ==== TABLE ==== */
.table-wrapper {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.table-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-header-left i {
    font-size: 22px;
    color: #5b7ce6;
}

.table-header-left h5 {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0;
}

.btn-view {
    background: #5b7ce6;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-view:hover {
    background: #4c6fd8;
    transform: translateY(-1px);
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background: #f9fafb;
}

.table thead th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e5e7eb;
}

.table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.15s ease;
}

.table tbody tr:hover {
    background: #f9fafb;
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table tbody td {
    padding: 14px 16px;
    font-size: 14px;
    color: #374151;
}

.table tbody td:first-child {
    font-weight: 600;
    color: #111827;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.approved {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #991b1b;
}

.table-empty {
    text-align: center;
    padding: 48px 20px;
    color: #9ca3af;
}

.table-empty i {
    font-size: 48px;
    margin-bottom: 12px;
    display: block;
    opacity: 0.5;
}

/* ==== RESPONSIVE ==== */
@media (max-width: 768px) {
    .stats-row {
        grid-template-columns: 1fr;
    }

    .charts-grid {
        grid-template-columns: 1fr;
    }

    .stat-number {
        font-size: 28px;
    }

    .stat-item:hover .stat-number {
        font-size: 30px;
    }

    .table-responsive {
        overflow-x: auto;
    }
}
</style>

<div class="container-fluid">
    {{-- HEADER --}}
    <div class="page-header">
        <h2>
            <i class="bi bi-speedometer2"></i>
            Dashboard Quản Lý Thực Tập
        </h2>
        <p>Theo dõi và phân tích hoạt động thực tập sinh viên</p>
    </div>

    {{-- ENHANCED STATS --}}
    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-icon-box">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ $totalSinhVien }}</div>
                <div class="stat-label">SINH VIÊN</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-box">
                <i class="bi bi-building-check"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ $totalDoanhNghiep }}</div>
                <div class="stat-label">DOANH NGHIỆP</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-box">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ $totalViTri }}</div>
                <div class="stat-label">VỊ TRÍ THỰC TẬP</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-box">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ $totalDangKyThanhCong }}</div>
                <div class="stat-label">ĐÃ ĐĂNG KÝ</div>
            </div>
        </div>

        <!-- SỐ GIẢNG VIÊN -->
        <div class="stat-item">
            <div class="stat-icon-box">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ $totalGiangVien }}</div>
                <div class="stat-label">GIẢNG VIÊN</div>
            </div>
        </div>

        <!-- THỰC TẬP HOÀN THÀNH -->
        <div class="stat-item">
            <div class="stat-icon-box">
                <i class="bi bi-flag-fill"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ $totalHoanThanh }}</div>
                <div class="stat-label">HOÀN THÀNH THỰC TẬP</div>
            </div>
        </div>


    </div>

    {{-- MAIN CHARTS --}}
    <div class="section-header">
        <i class="bi bi-bar-chart-line-fill"></i>
        <h4>Phân tích tổng quan</h4>
    </div>

    <div class="charts-grid">
        <div class="chart-box">
            <div class="chart-title">
                <i class="bi bi-people-fill"></i>
                <span>Sinh viên theo lớp</span>
            </div>
            <div class="chart-container">
                {!! $chart_sv->container() !!}
            </div>
        </div>

        <div class="chart-box">
            <div class="chart-title">
                <i class="bi bi-briefcase-fill"></i>
                <span>Vị trí thực tập</span>
            </div>
            <div class="chart-container">
                {!! $chart_vitri->container() !!}
            </div>
        </div>
    </div>

    {{-- COMBINED EVALUATION CHART --}}
    <div class="section-header">
        <i class="bi bi-graph-up"></i>
        <h4>Điểm đánh giá sinh viên</h4>
    </div>

    <div class="chart-box-full">
        <div class="chart-title">
            <i class="bi bi-award-fill text-primary"></i>
            <span>So sánh đánh giá từ doanh nghiệp và giảng viên</span>
        </div>
        <div class="chart-container-full">
            {!! $chart_danhgia_combined->container() !!}
        </div>
    </div>

    {{-- TABLE --}}
    <div class="section-header">
        <i class="bi bi-clock-history"></i>
        <h4>Hoạt động gần đây</h4>
    </div>

    <div class="table-wrapper">
        <div class="table-header">
            <div class="table-header-left">
                <i class="bi bi-list-check"></i>
                <h5>Đăng ký thực tập mới nhất</h5>
            </div>
            <a href="{{ route('admin.dangkythuctap.index') }}" class="btn-view">
                <i class="bi bi-eye"></i>
                <span>Xem tất cả</span>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>MÃ SV</th>
                        <th>HỌ TÊN</th>
                        <th>VỊ TRÍ</th>
                        <th>DOANH NGHIỆP</th>
                        <th>NGÀY ĐĂNG KÝ</th>
                        <th>TRẠNG THÁI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentDangKy as $dk)
                    <tr>
                        <td>{{ $dk->sinhvien->ma_sv }}</td>
                        <td>{{ $dk->sinhvien->ho_ten }}</td>
                        <td>{{ $dk->vitriThucTap->ten_vitri }}</td>
                        <td>{{ $dk->vitriThucTap->doanhnghiep->ten_dn ?? 'Chưa có' }}</td>
                        <td>{{ \Carbon\Carbon::parse($dk->ngay_dangky)->format('d/m/Y') }}</td>
                        <td>
                            @if ($dk->trang_thai == 'cho_duyet')
                            <span class="status-badge pending">Chờ duyệt</span>
                            @elseif ($dk->trang_thai == 'da_duyet')
                            <span class="status-badge approved">Đã duyệt</span>
                            @elseif ($dk->trang_thai == 'tu_choi')
                            <span class="status-badge rejected">Từ chối</span>
                            @else
                            <span class="status-badge">{{ ucfirst($dk->trang_thai) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="table-empty">
                            <i class="bi bi-inbox"></i>
                            <div>Chưa có dữ liệu đăng ký</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{!! $chart_sv->script() !!}
{!! $chart_vitri->script() !!}
{!! $chart_danhgia_combined->script() !!}
@endsection