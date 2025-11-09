<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhanCongGiangVien;
use App\Models\GiangVien;
use App\Models\DangKyThucTap;

class PhanCongGiangVienController extends Controller
{
    // Hiển thị danh sách + lọc theo tên giảng viên
    public function index(Request $request)
    {
        $query = PhanCongGiangVien::with(['giangVien', 'dangKyThucTap.sinhVien'])
            ->where('is_delete', 0);

        if ($request->filled('giangvien')) {
            $query->whereHas('giangVien', function ($q) use ($request) {
                $q->where('ho_ten', 'LIKE', '%' . $request->giangvien . '%');
            });
        }

        $phancongs = $query->orderByDesc('pc_id')->paginate(10);
        $giangviens = GiangVien::where('is_delete', 0)->get();
       $dangky = DangKyThucTap::with(['sinhVien', 'viTriThucTap.doanhNghiep'])
    ->where('is_delete', 0)
    ->where('trang_thai', 'da_duyet')
    ->get();


        return view('admin.phanconggiangvien', compact('phancongs', 'giangviens', 'dangky'));
    }

    //  Xem chi tiết (AJAX)
  public function show($id)
{
    $phancong = PhanCongGiangVien::with([
        'giangVien',
        'dangKyThucTap.sinhVien',
        'dangKyThucTap.viTriThucTap.doanhNghiep'
    ])->findOrFail($id);

    return response()->json($phancong);
}


    // Thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'dk_id' => 'required|exists:dangky_thuctap,dk_id',
            'gv_id' => 'required|exists:giangvien,gv_id',
            'ngay_phancong' => 'required|date',
        ]);

        PhanCongGiangVien::create([
            'dk_id' => $request->dk_id,
            'gv_id' => $request->gv_id,
            'ngay_phancong' => $request->ngay_phancong,
            'ghi_chu' => $request->ghi_chu,
        ]);

        return redirect()->back()->with('success', 'Thêm phân công giảng viên thành công!');
    }

    //  Cập nhật
    public function update(Request $request, $id)
    {
        $phancong = PhanCongGiangVien::findOrFail($id);

        $request->validate([
            'gv_id' => 'required|exists:giangvien,gv_id',
            'ngay_phancong' => 'required|date',
        ]);

        $phancong->update([
            'gv_id' => $request->gv_id,
            'ngay_phancong' => $request->ngay_phancong,
            'ghi_chu' => $request->ghi_chu,
        ]);

        return redirect()->back()->with('success', 'Cập nhật phân công thành công!');
    }

    
}