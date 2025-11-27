<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SinhVien;
use App\Models\DoanhNghiep;
use App\Models\ViTriThucTap;
use App\Models\DangKyThucTap;
use App\Models\GiangVienDanhGia;
use App\Models\DoanhNghiepDanhGia;
use Illuminate\Support\Facades\Auth;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // --- THỐNG KÊ TỔNG QUAN ---
        $totalSinhVien = SinhVien::where('is_delete', 0)->count();
        $totalDoanhNghiep = DoanhNghiep::where('is_delete', 0)->count();
        $totalViTri = ViTriThucTap::where('is_delete', 0)->count();
        $totalDangKyThanhCong = DangKyThucTap::where('is_delete', 0)
            ->whereIn('trang_thai', ['da_duyet', 'dang_thuctap', 'hoan_thanh'])
            ->count();

        // --- BIỂU ĐỒ SINH VIÊN ĐĂNG KÝ THEO LỚP ---
        $chart_sv_data = SinhVien::selectRaw('lop, COUNT(*) as tong_sv')
            ->where('is_delete', 0)
            ->groupBy('lop')
            ->get();

        $chart_sv_dk_data = SinhVien::selectRaw('lop, COUNT(dk.dk_id) as so_sv_dk')
            ->join('dangky_thuctap as dk', 'sinhvien.sv_id', '=', 'dk.sv_id')
            ->where('sinhvien.is_delete', 0)
            ->where('dk.is_delete', 0)
            ->groupBy('lop')
            ->get();

        $svLabels = $chart_sv_data->pluck('lop')->toArray();
        $svTotal = $chart_sv_data->pluck('tong_sv')->toArray();
        $svDK = $chart_sv_dk_data->pluck('so_sv_dk')->toArray();

        $chart_sv = new Chart;
        $chart_sv->labels($svLabels);
        $chart_sv->dataset('Tổng SV', 'bar', $svTotal)->backgroundColor('#4A7FA7');
        $chart_sv->dataset('Đã đăng ký', 'bar', $svDK)->backgroundColor('#6AA3CA');

        // --- BIỂU ĐỒ VỊ TRÍ THỰC TẬP ---
        $vitri_data = ViTriThucTap::where('is_delete', 0)->get();
        $vitriLabels = $vitri_data->pluck('ten_vitri')->toArray();
        $vitriSoluong = $vitri_data->pluck('soluong')->toArray();
        $vitriDangKy = $vitri_data->pluck('so_luong_da_dangky')->toArray();

        $chart_vitri = new Chart;
        $chart_vitri->labels($vitriLabels);
        $chart_vitri->dataset('Số lượng tổng', 'bar', $vitriSoluong)->backgroundColor('#9d9d8c');
        $chart_vitri->dataset('Đã đăng ký', 'bar', $vitriDangKy)->backgroundColor('#f0f0e7');

        // --- BIỂU ĐỒ ĐÁNH GIÁ KẾT HỢP (Doanh nghiệp + Giảng viên) ---
        // Lấy dữ liệu đánh giá từ doanh nghiệp
        $dgDnData = DoanhNghiepDanhGia::selectRaw('sinhvien.ho_ten as ten_sv, doanhnghiep_danhgia.diemso, doanhnghiep_danhgia.created_at')
            ->join('dangky_thuctap', 'doanhnghiep_danhgia.dk_id', '=', 'dangky_thuctap.dk_id')
            ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
            ->where('doanhnghiep_danhgia.is_delete', 0)
            ->get();

        // Lấy dữ liệu đánh giá từ giảng viên
        $dgGvData = GiangVienDanhGia::selectRaw('sinhvien.ho_ten as ten_sv, giangvien_danhgia.diemso, giangvien_danhgia.created_at')
            ->join('dangky_thuctap', 'giangvien_danhgia.dk_id', '=', 'dangky_thuctap.dk_id')
            ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
            ->where('giangvien_danhgia.is_delete', 0)
            ->get();

        // Lấy danh sách sinh viên duy nhất
        $allSinhVien = $dgDnData->pluck('ten_sv')
            ->merge($dgGvData->pluck('ten_sv'))
            ->unique()
            ->values()
            ->toArray();

        $chart_danhgia_combined = new Chart;
        $chart_danhgia_combined->labels($allSinhVien);

        // Dataset cho đánh giá từ doanh nghiệp (lấy điểm mới nhất)
        $dnScores = [];
        foreach ($allSinhVien as $sv) {
            $record = $dgDnData->where('ten_sv', $sv)
                ->sortByDesc('created_at')
                ->first();
            $dnScores[] = $record ? floatval($record->diemso) : null;
        }
        $chart_danhgia_combined->dataset('Doanh nghiệp', 'line', $dnScores)->options([
            'borderColor' => '#3b82f6',
            'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
            'tension' => 0.4,
            'fill' => true,
            'pointRadius' => 4,
            'pointBackgroundColor' => '#3b82f6',
        ]);

        // Dataset cho đánh giá từ giảng viên (lấy điểm mới nhất)
        $gvScores = [];
        foreach ($allSinhVien as $sv) {
            $record = $dgGvData->where('ten_sv', $sv)
                ->sortByDesc('created_at')
                ->first();
            $gvScores[] = $record ? floatval($record->diemso) : null;
        }
        $chart_danhgia_combined->dataset('Giảng viên', 'line', $gvScores)->options([
            'borderColor' => '#10b981',
            'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
            'tension' => 0.4,
            'fill' => true,
            'pointRadius' => 4,
            'pointBackgroundColor' => '#10b981',
        ]);

        // --- DANH SÁCH ĐĂNG KÝ GẦN ĐÂY ---
        $recentDangKy = DangKyThucTap::with([
            'sinhvien',
            'vitriThucTap.doanhnghiep'
        ])
        ->where('is_delete', 0)
        ->latest('dk_id')
        ->take(3)
        ->get();

        // --- Lấy thông tin user hiện tại ---
        $user = Auth::user();

        return view('admin.dashboard', compact(
            'totalSinhVien',
            'totalDoanhNghiep',
            'totalViTri',
            'totalDangKyThanhCong',
            'chart_sv',
            'chart_vitri',
            'chart_danhgia_combined',
            'recentDangKy',
            'user'
        ));
    }
}