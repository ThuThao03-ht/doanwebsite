<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ViTriThucTap;
use App\Models\DangKyThucTap;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Auth;

class ViTriThucTapSVController extends Controller
{
    // 🔹 Danh sách vị trí thực tập
    public function vitrithuctap(Request $request)
    {
        $user = Auth::user();
        $sinhvien = SinhVien::where('user_id', $user->user_id)->firstOrFail();

        $trangThaiFilter = $request->get('trang_thai');

        $viTriThucTapQuery = ViTriThucTap::with('doanhNghiep')
            ->where('is_delete', 0)
            ->orderBy('created_at', 'desc');

        if ($trangThaiFilter === 'con_han') {
            $viTriThucTapQuery->where('trang_thai', 'con_han');
        } elseif ($trangThaiFilter === 'het_han') {
            $viTriThucTapQuery->where('trang_thai', 'het_han');
        }

        $viTriThucTap = $viTriThucTapQuery->get();

        return view('sinhvien.vitrithuctapsv', compact('viTriThucTap'));
    }

    // 🔹 API xem chi tiết vị trí (dùng cho modal AJAX)
   public function xemChiTietViTri($id)
{
    $vitri = ViTriThucTap::with('doanhNghiep')->findOrFail($id);

    return response()->json([
        'vitri' => $vitri
    ]);
}

public function dangKyViTriThucTap(Request $request)
{
    $user = Auth::user();
    $sv = SinhVien::where('user_id', $user->user_id)->firstOrFail();

    // Kiểm tra sinh viên đã đăng ký vị trí nào chưa
    $dangKyTonTai = DangKyThucTap::where('sv_id', $sv->sv_id)
        ->whereIn('trang_thai', ['cho_duyet', 'da_duyet', 'dang_thuctap'])
        ->where('is_delete', 0)
        ->first();

    if ($dangKyTonTai) {
        return redirect()->back()->with('error', 'Bạn đã đăng ký một vị trí thực tập. Vui lòng hủy đăng ký hiện tại trước khi đăng ký vị trí mới.');
    }

    $vitri = ViTriThucTap::where('vitri_id', $request->vitri_id)->firstOrFail();

    if ($vitri->trang_thai !== 'con_han' || $vitri->so_luong_da_dangky >= $vitri->soluong) {
        return redirect()->back()->with('error', 'Vị trí này đã hết hạn hoặc đủ số lượng!');
    }

    // Tạo đăng ký mới
    DangKyThucTap::create([
        'sv_id' => $sv->sv_id,
        'vitri_id' => $vitri->vitri_id,
        'trang_thai' => 'cho_duyet'
    ]);

    // Cập nhật số lượng đã đăng ký
    $vitri->so_luong_da_dangky += 1;
    if ($vitri->so_luong_da_dangky >= $vitri->soluong) {
        $vitri->trang_thai = 'het_han';
    }
    $vitri->save();

    return redirect()->back()->with('success', 'Đăng ký vị trí thực tập thành công!');
}

}