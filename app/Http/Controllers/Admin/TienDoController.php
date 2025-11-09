<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TienDo;
use App\Models\DangKyThucTap;

class TienDoController extends Controller
{
    // Hiển thị danh sách tiến độ
 public function index(Request $request)
{
    $query = TienDo::with(['dangKyThucTap.sinhVien', 'dangKyThucTap.viTriThucTap'])
        ->chuaXoa()
        ->orderBy('tiendo_id', 'asc'); // sắp xếp theo tiendo_id từ nhỏ tới lớn

    // Lọc theo tên sinh viên
    if ($request->filled('ten_sinh_vien')) {
        $query->whereHas('dangKyThucTap.sinhVien', function($q) use ($request) {
            $q->where('ho_ten', 'like', '%'.$request->ten_sinh_vien.'%');
        });
    }

    $tiendos = $query->get();

    return view('admin.tiendo', compact('tiendos'));
}



    // Xem chi tiết tiến độ (AJAX)
    public function show($id)
    {
        $tiendo = TienDo::with(['dangKyThucTap.sinhVien', 'dangKyThucTap.viTriThucTap'])
            ->findOrFail($id);

        // Tạo link file PDF nếu có
        $fileUrl = null;
        if ($tiendo->file_dinhkem) {
            $fileUrl = asset('storage/' . $tiendo->file_dinhkem);
        }

        return response()->json([
            'tiendo_id' => $tiendo->tiendo_id,
            'noi_dung' => $tiendo->noi_dung,
            'ngay_capnhat' => $tiendo->ngay_capnhat,
            'sinh_vien' => optional($tiendo->dangKyThucTap->sinhVien)->ho_ten,
            'vi_tri' => optional($tiendo->dangKyThucTap->viTriThucTap)->ten_vitri,
            'file_url' => $fileUrl,
        ]);
    }
}