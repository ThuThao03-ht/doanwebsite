<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DangKyThucTap;
use App\Models\SinhVien;
use App\Models\ViTriThucTap;

class DangKyThucTapController extends Controller
{
    // Hiển thị danh sách đăng ký thực tập
 public function index(Request $request)
{
    $query = DangKyThucTap::with(['sinhVien', 'viTriThucTap'])
        ->where('is_delete', 0);

    // Lọc theo trạng thái nếu có
    if ($request->filled('trang_thai')) {
        $query->where('trang_thai', $request->trang_thai);
    }

    $dangky = $query->orderBy('created_at', 'desc')->get();

    return view('admin.dangkythuctap', compact('dangky'));
}


    // Xem chi tiết đăng ký (AJAX hoặc modal)
    public function show($id)
    {
        $dk = DangKyThucTap::with(['sinhVien', 'viTriThucTap'])->findOrFail($id);
        return response()->json($dk);
    }

    // Duyệt đăng ký (Admin chọn "Duyệt" hoặc "Từ chối")
    // public function duyet(Request $request, $id)
    // {
    //     $dk = DangKyThucTap::findOrFail($id);
    //     $dk->trang_thai = $request->trang_thai;
    //     $dk->save();

    //     return redirect()->back()->with('success', 'Cập nhật trạng thái đăng ký thành công!');
    // }


public function duyet(Request $request, $id)
{
    $dk = DangKyThucTap::findOrFail($id);

    // Cập nhật trạng thái duyệt / từ chối
    $dk->trang_thai = $request->trang_thai;
    $dk->save();

    // 🔹 Nếu được duyệt, thì tự động tạo bản ghi trong bảng phancong_giangvien
    if ($request->trang_thai === 'da_duyet') {
        // Kiểm tra xem đã có phân công cho đăng ký này chưa
        $exists = \App\Models\PhanCongGiangVien::where('dk_id', $dk->dk_id)
            ->where('is_delete', 0)
            ->exists();

        if (!$exists) {
            \App\Models\PhanCongGiangVien::create([
                'dk_id' => $dk->dk_id,
                'gv_id' => null, // Chưa có giảng viên hướng dẫn
                'ngay_phancong' => null,
                'ghi_chu' => null,
                'is_delete' => 0,
            ]);
        }
    }

    return redirect()->back()->with('success', 'Cập nhật trạng thái đăng ký thành công!');
}


    
    // Cập nhật trạng thái thực tập (đang thực tập / hoàn thành)
    public function capNhatTrangThai(Request $request, $id)
    {
        $dk = DangKyThucTap::findOrFail($id);
        $dk->trang_thai = $request->trang_thai;
        $dk->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thực tập thành công!');
    }
// Hủy đăng ký thực tập (Admin hoặc sinh viên yêu cầu)
public function huyDangKy($id)
{
    $dk = DangKyThucTap::findOrFail($id);

    // Lấy vị trí thực tập tương ứng
    $vitri = ViTriThucTap::find($dk->vitri_id);

    if ($vitri) {
        // Giảm số lượng đã đăng ký
        $vitri->so_luong_da_dangky = max(0, $vitri->so_luong_da_dangky - 1);

        // Nếu trước đó hết hạn nhưng giờ còn chỗ, đổi lại con_han
        if ($vitri->trang_thai === 'het_han' && $vitri->so_luong_da_dangky < $vitri->soluong) {
            $vitri->trang_thai = 'con_han';
        }

        $vitri->save();
    }

    // Cập nhật trạng thái đăng ký và xóa mềm
    $dk->trang_thai = 'tu_choi';
    $dk->is_delete = 1;
    $dk->save();

    return redirect()->back()->with('success', 'Đã hủy đăng ký thực tập thành công!');
}

public function countDangKy()
    {
        $count = DangKyThucTap::where('is_delete', 0)->count();
        return response()->json(['count' => $count]);
    }
    
}