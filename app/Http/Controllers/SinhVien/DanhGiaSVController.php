<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SinhVien;
use App\Models\DangKyThucTap;
use Illuminate\Http\Request;

class DanhGiaSVController extends Controller
{
    /**
     * Hiển thị trang danh sách đánh giá của sinh viên
     */
    public function index()
    {
        $user = Auth::user();

        // Lấy sinh viên hiện tại
        $sinhvien = SinhVien::where('user_id', $user->user_id)->first();

        if (!$sinhvien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        // Lấy danh sách đăng ký thực tập của sinh viên
        //  Loại bỏ các đăng ký có trạng_thai = 'tu_choi'
        $dangkyList = DangKyThucTap::with([
            'viTriThucTap.doanhNghiep',
            'danhGiaGiangVien.giangVien',
            'danhGiaDoanhNghiep.doanhNghiep'
        ])
        ->where('sv_id', $sinhvien->sv_id)
        ->where('is_delete', 0)
       ->whereNotIn('trang_thai', ['tu_choi', 'cho_duyet'])

        ->get();

        return view('sinhvien.danhgiasv', compact('dangkyList', 'sinhvien'));
    }

    /**
     * API xem chi tiết đánh giá
     */
    public function show($dk_id)
    {
        $dangky = DangKyThucTap::with([
            'viTriThucTap.doanhNghiep',
            'danhGiaGiangVien.giangVien',
            'danhGiaDoanhNghiep.doanhNghiep'
        ])->findOrFail($dk_id);

        // Tính điểm trung bình nếu có cả 2
        $gv_diem = $dangky->danhGiaGiangVien->diemso ?? null;
        $dn_diem = $dangky->danhGiaDoanhNghiep->diemso ?? null;
        $diem_tb = null;
        if ($gv_diem !== null && $dn_diem !== null) {
            $diem_tb = round(($gv_diem + $dn_diem) / 2, 2);
        }

        return response()->json([
            'dangky' => $dangky,
            'diem_trung_binh' => $diem_tb,
        ]);
    }
}