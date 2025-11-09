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
        // $totalDangKyThanhCong = DangKyThucTap::where('is_delete', 0)
        //     ->where('trang_thai', 'da_duyet')
        //     ->count();
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

        // --- BIỂU ĐỒ ĐÁNH GIÁ TỔNG QUAN ---
        $dgDn = DoanhNghiepDanhGia::where('is_delete', 0)->avg('diemso') ?? 0;
        $dgGv = GiangVienDanhGia::where('is_delete', 0)->avg('diemso') ?? 0;

        $chart_danhgia = new Chart;
        $chart_danhgia->labels(['Doanh nghiệp', 'Giảng viên']);
        $chart_danhgia->dataset('Điểm đánh giá', 'doughnut', [$dgDn, $dgGv])
            ->backgroundColor(['#4A7FA7', '#6AA3CA']);

        // --- BIỂU ĐỒ ĐÁNH GIÁ THEO DOANH NGHIỆP ---
        $dgDnData = DoanhNghiepDanhGia::selectRaw('sinhvien.ho_ten as ten_sv, doanhnghiep.ten_dn, doanhnghiep_danhgia.diemso')
            ->join('dangky_thuctap', 'doanhnghiep_danhgia.dk_id', '=', 'dangky_thuctap.dk_id')
            ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
            ->join('doanhnghiep', 'doanhnghiep_danhgia.dn_id', '=', 'doanhnghiep.dn_id')
            ->where('doanhnghiep_danhgia.is_delete', 0)
            ->get();

        $dnGroups = $dgDnData->groupBy('ten_dn');
        $svLabels_dn = $dgDnData->pluck('ten_sv')->unique()->values()->toArray();

        $chart_dg_dn = new Chart;
        $chart_dg_dn->labels($svLabels_dn);

        foreach ($dnGroups as $ten_dn => $records) {
            $dataset = [];
            foreach ($svLabels_dn as $sv) {
                $record = $records->firstWhere('ten_sv', $sv);
                $dataset[] = $record ? floatval($record->diemso) : null;
            }
            $chart_dg_dn->dataset($ten_dn, 'line', $dataset)->options([
                'fill' => false,
                'tension' => 0.3,
            ]);
        }

        // Thêm đường trung bình tất cả sinh viên (Doanh nghiệp)
        $avgDnBySV = $dgDnData->groupBy('ten_sv')->map(fn($r) => $r->avg('diemso'))->values()->toArray();
        $chart_dg_dn->dataset('Trung bình', 'line', $avgDnBySV)->options([
            'borderColor' => '#FF0000',
            'borderWidth' => 2,
            'tension' => 0.3,
            'fill' => false,
            'pointRadius' => 3,
        ]);

        // --- BIỂU ĐỒ ĐÁNH GIÁ THEO GIẢNG VIÊN ---
        $dgGvData = GiangVienDanhGia::selectRaw('sinhvien.ho_ten as ten_sv, giangvien.ho_ten as ten_gv, giangvien_danhgia.diemso')
            ->join('dangky_thuctap', 'giangvien_danhgia.dk_id', '=', 'dangky_thuctap.dk_id')
            ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
            ->join('giangvien', 'giangvien_danhgia.gv_id', '=', 'giangvien.gv_id')
            ->where('giangvien_danhgia.is_delete', 0)
            ->get();

        $gvGroups = $dgGvData->groupBy('ten_gv');
        $svLabels_gv = $dgGvData->pluck('ten_sv')->unique()->values()->toArray();

        $chart_dg_gv = new Chart;
        $chart_dg_gv->labels($svLabels_gv);

        foreach ($gvGroups as $ten_gv => $records) {
            $dataset = [];
            foreach ($svLabels_gv as $sv) {
                $record = $records->firstWhere('ten_sv', $sv);
                $dataset[] = $record ? floatval($record->diemso) : null;
            }
            $chart_dg_gv->dataset($ten_gv, 'line', $dataset)->options([
                'fill' => false,
                'tension' => 0.3,
            ]);
        }

        // Thêm đường trung bình tất cả sinh viên (Giảng viên)
        $avgGvBySV = $dgGvData->groupBy('ten_sv')->map(fn($r) => $r->avg('diemso'))->values()->toArray();
        $chart_dg_gv->dataset('Trung bình', 'line', $avgGvBySV)->options([
            'borderColor' => '#FF0000',
            'borderWidth' => 2,
            'tension' => 0.3,
            'fill' => false,
            'pointRadius' => 3,
        ]);

        // --- DANH SÁCH ĐĂNG KÝ GẦN ĐÂY ---
   $recentDangKy = DangKyThucTap::with([
    'sinhvien',
    'vitriThucTap.doanhnghiep'
])
->where('is_delete', 0)
->latest('dk_id') // sắp xếp theo dk_id giảm dần
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
            'chart_danhgia',
            'chart_dg_dn',
            'chart_dg_gv',
            'recentDangKy',
            'user'
        ));
    }
}