<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QLDanhGiaThucTapTQController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? null;
        $search = $request->input('search'); // Tìm kiếm theo tên sinh viên
        $chuaDanhGia = $request->input('chua_danhgia'); // Lọc báo cáo chưa đánh giá

        if ($role == 'GiangVien') {
            $gv = DB::table('giangvien')->where('user_id', $user->user_id)->first();

            $query = DB::table('baocao_thuctap')
                ->join('dangky_thuctap', 'baocao_thuctap.dk_id', '=', 'dangky_thuctap.dk_id')
                ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
                ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                ->join('doanhnghiep', 'vitri_thuctap.dn_id', '=', 'doanhnghiep.dn_id')
                ->leftJoin('phancong_giangvien', 'dangky_thuctap.dk_id', '=', 'phancong_giangvien.dk_id')
                ->leftJoin('giangvien_danhgia', function ($join) use ($gv) {
                    $join->on('dangky_thuctap.dk_id', '=', 'giangvien_danhgia.dk_id')
                        ->where('giangvien_danhgia.gv_id', $gv->gv_id)
                        ->where('giangvien_danhgia.is_delete', 0);
                })
                ->where('phancong_giangvien.gv_id', $gv->gv_id)
                ->where('baocao_thuctap.is_delete', 0)
                ->select(
                    'baocao_thuctap.*',
                    'sinhvien.ho_ten',
                    'sinhvien.ma_sv',
                    'vitri_thuctap.ten_vitri',
                    'doanhnghiep.ten_dn',
                    'dangky_thuctap.trang_thai as trangthai_dk',
                    'giangvien_danhgia.diemso as diem_so',
                    'giangvien_danhgia.nhanxet as nhan_xet'
                );

            // Lọc theo tên sinh viên
            if (!empty($search)) {
                $query->where('sinhvien.ho_ten', 'like', '%' . $search . '%');
            }

            // Lọc chỉ báo cáo chưa đánh giá
            if ($chuaDanhGia) {
                $query->whereNull('giangvien_danhgia.diemso');
            }

            $baoCaoList = $query->orderBy('baocao_thuctap.ngay_nop', 'desc')->get();
        } elseif ($role == 'DoanhNghiep') {
            $dn = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();

            $query = DB::table('baocao_thuctap')
                ->join('dangky_thuctap', 'baocao_thuctap.dk_id', '=', 'dangky_thuctap.dk_id')
                ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
                ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                ->join('doanhnghiep', 'vitri_thuctap.dn_id', '=', 'doanhnghiep.dn_id')
                ->leftJoin('doanhnghiep_danhgia', function ($join) use ($dn) {
                    $join->on('dangky_thuctap.dk_id', '=', 'doanhnghiep_danhgia.dk_id')
                        ->where('doanhnghiep_danhgia.dn_id', $dn->dn_id)
                        ->where('doanhnghiep_danhgia.is_delete', 0);
                })
                ->where('vitri_thuctap.dn_id', $dn->dn_id)
                ->where('baocao_thuctap.is_delete', 0)
                ->select(
                    'baocao_thuctap.*',
                    'sinhvien.ho_ten',
                    'sinhvien.ma_sv',
                    'vitri_thuctap.ten_vitri',
                    'doanhnghiep.ten_dn',
                    'dangky_thuctap.trang_thai as trangthai_dk',
                    'doanhnghiep_danhgia.diemso as diem_so',
                    'doanhnghiep_danhgia.nhanxet as nhan_xet'
                );

            //  Lọc theo tên sinh viên
            if (!empty($search)) {
                $query->where('sinhvien.ho_ten', 'like', '%' . $search . '%');
            }

            //  Lọc chỉ báo cáo chưa đánh giá
            if ($chuaDanhGia) {
                $query->whereNull('doanhnghiep_danhgia.diemso');
            }

            $baoCaoList = $query->orderBy('baocao_thuctap.ngay_nop', 'desc')->get();
        } else {
            $baoCaoList = collect();
        }

        return view('giangvien.qldanhgiathuctaptq', compact('baoCaoList', 'role', 'search', 'chuaDanhGia'));
    }

    public function danhgia(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? null;

        $request->validate([
            'dk_id' => 'required|integer',
            'diemso' => 'required|numeric|min:0|max:10',
            'nhanxet' => 'nullable|string|max:1000'
        ]);

        if ($role == 'GiangVien') {
            $gv = DB::table('giangvien')->where('user_id', $user->user_id)->first();

            DB::table('giangvien_danhgia')->updateOrInsert(
                ['dk_id' => $request->dk_id, 'gv_id' => $gv->gv_id],
                ['diemso' => $request->diemso, 'nhanxet' => $request->nhanxet, 'ngay_danhgia' => now()]
            );

        } elseif ($role == 'DoanhNghiep') {
            $dn = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();

            DB::table('doanhnghiep_danhgia')->updateOrInsert(
                ['dk_id' => $request->dk_id, 'dn_id' => $dn->dn_id],
                ['nguoi_danhgia_id' => $user->user_id, 'diemso' => $request->diemso, 'nhanxet' => $request->nhanxet, 'ngay_danhgia' => now()]
            );
        }

        return redirect()->back()->with('success', 'Đã lưu đánh giá thành công.');
    }
}