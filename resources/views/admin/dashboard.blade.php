@extends('layouts.admin')

@section('content')
<style>
body {
    background-color: #f5f7fa;
    font-family: "Inter", sans-serif;
}

/* ==== CARD THỐNG KÊ ==== */
.stats-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.stat-card {
    background: #fff;
    border-radius: 18px;
    padding: 24px 28px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

.stat-icon {
    width: 68px;
    height: 68px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: #fff;
    flex-shrink: 0;
    margin-right: 18px;
}

.stat-info h3 {
    font-weight: 700;
    font-size: 28px;
    margin-bottom: 4px;
    color: #1f2937;
}

.stat-info span {
    color: #6b7280;
    font-size: 15px;
}

/* ==== BIỂU ĐỒ ==== */
.chart-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    padding: 22px;
    height: 100%;
    transition: all 0.3s ease;
}

.chart-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
}

/* Tiêu đề biểu đồ */
.chart-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-weight: 600;
    font-size: 18px;
    margin-bottom: 18px;
    color: #374151;
    text-align: center;
}

.chart-title i {
    font-size: 20px;
    color: #3b82f6;
}

/* ==== BỐ TRÍ BIỂU ĐỒ ==== */
.charts-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.chart-pair {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

/* ==== TABLE ==== */
.table {
    border-collapse: separate;
    border-spacing: 0 8px;
}

.table thead tr {
    background-color: #f9fafb;
    border-bottom: 2px solid #e5e7eb;
}

.table tbody tr {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
}

/* ==== NÚT XEM TẤT CẢ ==== */
.btn-gradient {
    background: linear-gradient(135deg, #b7d3e9, #99cff7);
    color: #1f2937 !important;
    border: none;
    border-radius: 10px;
    transition: 0.3s;
    font-weight: 600;
}

.btn-gradient:hover {
    background: linear-gradient(135deg, #99cff7, #b7d3e9);
    transform: translateY(-2px);
}
</style>

<div class="container-fluid py-4">

    {{-- ===== TIÊU ĐỀ ===== --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-1" style="color:#3b5b8a;">
            <i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>
            Bảng điều khiển thống kê
        </h2>

        <p class="text-muted">Theo dõi toàn bộ hoạt động thực tập sinh viên và doanh nghiệp</p>
    </div>

    {{-- ===== THỐNG KÊ TỔNG ===== --}}
    <div class="stats-wrapper">
        <div class="stat-card" style="background-color: #d2b48c20;">
            <div class="stat-icon" style="background: #d2b48c;">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalSinhVien }}</h3>
                <span>Sinh viên</span>
            </div>
        </div>

        <div class="stat-card" style="background-color: #b7d3e920;">
            <div class="stat-icon" style="background: #b7d3e9;">
                <i class="bi bi-building-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalDoanhNghiep }}</h3>
                <span>Doanh nghiệp</span>
            </div>
        </div>

        <div class="stat-card" style="background-color: #c4bcb020;">
            <div class="stat-icon" style="background: #c4bcb0;">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalViTri }}</h3>
                <span>Vị trí thực tập</span>
            </div>
        </div>

        <div class="stat-card" style="background-color: #fff7e620;">
            <div class="stat-icon" style="background: #fff7e6;">
                <i class="bi bi-check-circle-fill text-success"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalDangKyThanhCong }}</h3>
                <span>Đăng ký thành công</span>
            </div>
        </div>
    </div>

    {{-- ===== 3 BIỂU ĐỒ HÀNG ĐẦU ===== --}}
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-people-fill"></i> Sinh viên theo lớp
            </div>
            <div style="height: 340px;">
                {!! $chart_sv->container() !!}
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-briefcase-fill"></i> Vị trí thực tập
            </div>
            <div style="height: 340px;">
                {!! $chart_vitri->container() !!}
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-star-fill text-warning"></i> Đánh giá tổng quan
            </div>
            <div style="height: 340px;">
                {!! $chart_danhgia->container() !!}
            </div>
        </div>
    </div>

    {{-- ===== BIỂU ĐỒ CHI TIẾT ===== --}}
    <div class="chart-pair">
        <div class="chart-card">
            <div class="chart-title text-info">
                <i class="bi bi-building-fill-check"></i> Điểm đánh giá từ doanh nghiệp
            </div>
            <div style="height: 380px;">
                {!! $chart_dg_dn->container() !!}
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-title text-secondary">
                <i class="bi bi-person-badge-fill"></i> Điểm đánh giá từ giảng viên
            </div>
            <div style="height: 380px;">
                {!! $chart_dg_gv->container() !!}
            </div>
        </div>
    </div>

    {{-- ===== DANH SÁCH ĐĂNG KÝ ===== --}}
    <div class="chart-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-graph-up-arrow text-primary fs-4 me-2"></i>
                <h5 class="fw-bold mb-0">Đăng ký thực tập gần đây</h5>
            </div>
            <a href="{{ route('admin.dangkythuctap.index') }}" class="btn btn-gradient btn-sm">
                <i class="bi bi-eye me-1"></i> Xem tất cả
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-secondary">
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
                        <td class="fw-semibold text-dark">{{ $dk->sinhvien->ma_sv }}</td>
                        <td>{{ $dk->sinhvien->ho_ten }}</td>
                        <td>{{ $dk->vitriThucTap->ten_vitri }}</td>
                        <td>{{ $dk->vitriThucTap->doanhnghiep->ten_dn ?? 'Chưa có' }}</td>
                        <td>{{ \Carbon\Carbon::parse($dk->ngay_dangky)->format('d/m/Y') }}</td>
                        <td>
                            @if ($dk->trang_thai == 'cho_duyet')
                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                            @elseif ($dk->trang_thai == 'da_duyet')
                            <span class="badge bg-success">Đã duyệt</span>
                            @elseif ($dk->trang_thai == 'tu_choi')
                            <span class="badge bg-danger">Từ chối</span>
                            @else
                            <span class="badge bg-secondary">{{ ucfirst($dk->trang_thai) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Chưa có dữ liệu đăng ký.
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
{!! $chart_danhgia->script() !!}
{!! $chart_dg_dn->script() !!}
{!! $chart_dg_gv->script() !!}
@endsection