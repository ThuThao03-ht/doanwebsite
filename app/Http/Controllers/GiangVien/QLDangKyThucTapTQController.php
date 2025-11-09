<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QLDangKyThucTapTQController extends Controller
{
   public function index(Request $request)
{
    $user = Auth::user();
    $role = $user->role->role_name ?? null;

    $search = $request->input('search'); //  Từ khóa tìm kiếm

    if ($role == 'GiangVien') {
        $gv = DB::table('giangvien')->where('user_id', $user->user_id)->first();

        $query = DB::table('dangky_thuctap')
            ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
            ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
            ->join('doanhnghiep', 'vitri_thuctap.dn_id', '=', 'doanhnghiep.dn_id')
            ->leftJoin('phancong_giangvien', 'dangky_thuctap.dk_id', '=', 'phancong_giangvien.dk_id')
            ->leftJoin('giangvien as gv', 'phancong_giangvien.gv_id', '=', 'gv.gv_id')
            ->where('phancong_giangvien.gv_id', $gv->gv_id)
            ->where('dangky_thuctap.is_delete', 0)
            ->select(
                'dangky_thuctap.*',
                'sinhvien.ho_ten',
                'sinhvien.ma_sv',
                'sinhvien.email as email_sv',
                'vitri_thuctap.ten_vitri',
                'vitri_thuctap.yeu_cau',
                'vitri_thuctap.mo_ta as mo_ta_vitri',
                'doanhnghiep.ten_dn',
                'gv.ho_ten as gv_huongdan'
            );

        if (!empty($search)) {
            $query->where('sinhvien.ho_ten', 'like', '%' . $search . '%');
        }

        $dangKyList = $query->get();
    } elseif ($role == 'DoanhNghiep') {
        $dn = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();

        $query = DB::table('dangky_thuctap')
            ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
            ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
            ->join('doanhnghiep', 'vitri_thuctap.dn_id', '=', 'doanhnghiep.dn_id')
            ->leftJoin('phancong_giangvien', 'dangky_thuctap.dk_id', '=', 'phancong_giangvien.dk_id')
            ->leftJoin('giangvien as gv', 'phancong_giangvien.gv_id', '=', 'gv.gv_id')
            ->where('vitri_thuctap.dn_id', $dn->dn_id)
            ->where('dangky_thuctap.is_delete', 0)
            ->select(
                'dangky_thuctap.*',
                'sinhvien.ho_ten',
                'sinhvien.ma_sv',
                'sinhvien.email as email_sv',
                'vitri_thuctap.ten_vitri',
                'vitri_thuctap.yeu_cau',
                'vitri_thuctap.mo_ta as mo_ta_vitri',
                'doanhnghiep.ten_dn',
                'gv.ho_ten as gv_huongdan'
            );

        if (!empty($search)) {
            $query->where('sinhvien.ho_ten', 'like', '%' . $search . '%');
        }

        $dangKyList = $query->get();
    } else {
        $dangKyList = collect();
    }

    return view('giangvien.qldangkythuctaptq', compact('dangKyList', 'role', 'search'));
}

}