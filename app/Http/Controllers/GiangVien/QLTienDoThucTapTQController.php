<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QLTienDoThucTapTQController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? null;
        $search = $request->input('search'); //  Lấy từ khóa tìm kiếm

        if ($role == 'GiangVien') {
            $gv = DB::table('giangvien')->where('user_id', $user->user_id)->first();

            $query = DB::table('tiendo')
                ->join('dangky_thuctap', 'tiendo.dk_id', '=', 'dangky_thuctap.dk_id')
                ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
                ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                ->join('doanhnghiep', 'vitri_thuctap.dn_id', '=', 'doanhnghiep.dn_id')
                ->leftJoin('phancong_giangvien', 'dangky_thuctap.dk_id', '=', 'phancong_giangvien.dk_id')
                ->leftJoin('giangvien as gv_hd', 'phancong_giangvien.gv_id', '=', 'gv_hd.gv_id')
                ->where('phancong_giangvien.gv_id', $gv->gv_id)
                ->where('tiendo.is_delete', 0)
                ->select(
                    'tiendo.*',
                    'sinhvien.ho_ten',
                    'sinhvien.ma_sv',
                    'vitri_thuctap.ten_vitri',
                    'doanhnghiep.ten_dn',
                    'dangky_thuctap.trang_thai as trangthai_dk',
                    'gv_hd.ho_ten as gv_huongdan'
                );

            // Lọc theo tên sinh viên
            if (!empty($search)) {
                $query->where('sinhvien.ho_ten', 'like', '%' . $search . '%');
            }

            $tienDoList = $query->orderBy('tiendo.ngay_capnhat', 'desc')->get();

        } elseif ($role == 'DoanhNghiep') {
            $dn = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();

            $query = DB::table('tiendo')
                ->join('dangky_thuctap', 'tiendo.dk_id', '=', 'dangky_thuctap.dk_id')
                ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
                ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                ->join('doanhnghiep', 'vitri_thuctap.dn_id', '=', 'doanhnghiep.dn_id')
                ->leftJoin('phancong_giangvien', 'dangky_thuctap.dk_id', '=', 'phancong_giangvien.dk_id')
                ->leftJoin('giangvien as gv_hd', 'phancong_giangvien.gv_id', '=', 'gv_hd.gv_id')
                ->where('vitri_thuctap.dn_id', $dn->dn_id)
                ->where('tiendo.is_delete', 0)
                ->select(
                    'tiendo.*',
                    'sinhvien.ho_ten',
                    'sinhvien.ma_sv',
                    'vitri_thuctap.ten_vitri',
                    'doanhnghiep.ten_dn',
                    'dangky_thuctap.trang_thai as trangthai_dk',
                    'gv_hd.ho_ten as gv_huongdan'
                );

            //  Lọc theo tên sinh viên
            if (!empty($search)) {
                $query->where('sinhvien.ho_ten', 'like', '%' . $search . '%');
            }

            $tienDoList = $query->orderBy('tiendo.ngay_capnhat', 'desc')->get();
        } else {
            $tienDoList = collect();
        }

        return view('giangvien.qltiendothuctaptq', compact('tienDoList', 'role', 'search'));
    }
}