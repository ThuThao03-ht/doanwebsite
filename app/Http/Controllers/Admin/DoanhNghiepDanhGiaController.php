<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoanhNghiepDanhGia;
use App\Models\SinhVien;
use App\Models\DoanhNghiep;

class DoanhNghiepDanhGiaController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá (lọc + tìm kiếm)
     */
public function index(Request $request)
{
    $query = DoanhNghiepDanhGia::with([
        'doanhNghiep',
        'dangKyThucTap.sinhVien'
    ])->where('is_delete', 0);

    // Lọc theo tên sinh viên
    if ($request->filled('sinhvien')) {
        $query->whereHas('dangKyThucTap.sinhVien', function ($q) use ($request) {
            $q->where('ho_ten', 'LIKE', '%' . $request->sinhvien . '%');
        });
    }

    // Lọc theo tên doanh nghiệp
    if ($request->filled('doanhnghiep')) {
        $query->whereHas('doanhNghiep', function ($q) use ($request) {
            $q->where('ten_dn', $request->doanhnghiep);
        });
    }

    $danhgias = $query->orderByDesc('ngay_danhgia')->paginate(10);

    // Lấy danh sách doanh nghiệp (distinct để tránh trùng tên)
    $dsDoanhNghiep = DoanhNghiep::select('ten_dn')
        ->distinct()
        ->orderBy('ten_dn')
        ->pluck('ten_dn');

    return view('admin.doanhnghiepdanhgia', compact('danhgias', 'dsDoanhNghiep'));
}

    /**
     * Xem chi tiết đánh giá (AJAX)
     */
    public function show($id)
    {
        $danhgia = DoanhNghiepDanhGia::with([
            'doanhNghiep',
            'dangKyThucTap.sinhVien',
             'dangKyThucTap.viTriThucTap',  
            'nguoiDanhGia'
        ])->findOrFail($id);

        return response()->json($danhgia);
    }
}

   