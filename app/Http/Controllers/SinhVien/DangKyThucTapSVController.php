<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SinhVien;
use App\Models\ViTriThucTap;
use App\Models\DangKyThucTap;

class DangKyThucTapSVController extends Controller
{
    /**
     * Hiển thị danh sách đăng ký thực tập của sinh viên
     */
    public function index()
    {
        $user = Auth::user();
        $sv = SinhVien::where('user_id', $user->user_id)->firstOrFail();

        $dangKyList = DangKyThucTap::with(['viTriThucTap.doanhNghiep'])
            ->where('sv_id', $sv->sv_id)
            ->where('is_delete', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('sinhvien.dangkythuctapsv', compact('dangKyList'));
    }

    /**
     * Xem chi tiết 1 đăng ký (hiển thị qua modal)
     */
    public function xemDangKy($id)
    {
        $dk = DangKyThucTap::with([
            'sinhVien',
            'viTriThucTap.doanhNghiep',
            'phanCongGiangViens.giangVien'
        ])->findOrFail($id);

        return response()->json([
            'dangky' => $dk
        ]);
    }

    /**
     * Hủy đăng ký thực tập (chỉ khi trạng thái là 'chờ duyệt')
     */
    public function huyDangKy($id)
    {
        $dk = DangKyThucTap::findOrFail($id);

        if ($dk->trang_thai !== 'cho_duyet') {
            return redirect()->back()->with('error', 'Chỉ có thể hủy khi trạng thái là "chờ duyệt"!');
        }

        $vitri = ViTriThucTap::find($dk->vitri_id);

        if ($vitri) {
            $vitri->so_luong_da_dangky = max(0, $vitri->so_luong_da_dangky - 1);

            // Mở lại vị trí nếu chưa đủ số lượng
            if ($vitri->trang_thai === 'het_han' && $vitri->so_luong_da_dangky < $vitri->soluong) {
                $vitri->trang_thai = 'con_han';
            }

            $vitri->save();
        }

        $dk->update([
            'trang_thai' => 'tu_choi',
            'is_delete' => 1
        ]);

        return redirect()->back()->with('success', 'Hủy đăng ký thực tập thành công!');
    }
}