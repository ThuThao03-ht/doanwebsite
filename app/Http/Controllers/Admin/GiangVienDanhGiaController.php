<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiangVienDanhGia;
use App\Models\GiangVien;
use App\Models\SinhVien;

class GiangVienDanhGiaController extends Controller
{
    // Hiển thị danh sách đánh giá
    public function index(Request $request)
    {
        $query = GiangVienDanhGia::with(['giangVien', 'dangKyThucTap.sinhVien'])
                    ->where('is_delete', false);

        // Lọc theo tên giảng viên
        if ($request->filled('ten_gv')) {
            $query->whereHas('giangVien', function($q) use ($request) {
                $q->where('ho_ten', 'like', '%'.$request->ten_gv.'%');
            });
        }

        // Lọc theo tên sinh viên
        if ($request->filled('ten_sv')) {
            $query->whereHas('dangKyThucTap.sinhVien', function($q) use ($request) {
                $q->where('ho_ten', 'like', '%'.$request->ten_sv.'%');
            });
        }

        $danhGias = $query->orderBy('ngay_danhgia', 'desc')->paginate(10);

        return view('admin.giangviendanhgia', compact('danhGias'));
    }

    // Xem chi tiết đánh giá (AJAX)
    public function show($id)
    {
        $danhGia = GiangVienDanhGia::with(['giangVien', 'dangKyThucTap.sinhVien', 'dangKyThucTap.viTriThucTap'])->findOrFail($id);
        return response()->json($danhGia);
    }
}