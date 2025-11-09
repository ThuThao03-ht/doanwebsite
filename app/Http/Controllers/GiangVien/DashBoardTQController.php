<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
class DashBoardTQController extends Controller
{
    public function index()
    {        
        $user = Auth::user();
        $role = $user->role->role_name ?? null;

        $totalDangKy = $totalTiendo = $totalBaocao = 0;
        $thongbaos = $sinhviens = [];

        // ====== Giảng viên ======
        if ($role == 'GiangVien') {
            $gv = DB::table('giangvien')->where('user_id', $user->user_id)->first();
            if ($gv) {
                $totalDangKy = DB::table('phancong_giangvien')
                    ->join('dangky_thuctap', 'phancong_giangvien.dk_id', '=', 'dangky_thuctap.dk_id')
                    ->where('phancong_giangvien.gv_id', $gv->gv_id)
                    ->where('dangky_thuctap.is_delete', 0)
                    ->count();

                $totalTiendo = DB::table('tiendo')
                    ->join('dangky_thuctap', 'tiendo.dk_id', '=', 'dangky_thuctap.dk_id')
                    ->join('phancong_giangvien', 'dangky_thuctap.dk_id', '=', 'phancong_giangvien.dk_id')
                    ->where('phancong_giangvien.gv_id', $gv->gv_id)
                    ->where('tiendo.is_delete', 0)
                    ->count();

                $totalBaocao = DB::table('baocao_thuctap')
                    ->join('dangky_thuctap', 'baocao_thuctap.dk_id', '=', 'dangky_thuctap.dk_id')
                    ->join('phancong_giangvien', 'dangky_thuctap.dk_id', '=', 'phancong_giangvien.dk_id')
                    ->where('phancong_giangvien.gv_id', $gv->gv_id)
                    ->where('baocao_thuctap.is_delete', 0)
                    ->count();

                $thongbaos = DB::table('thongbao_user')
                    ->join('thongbao', 'thongbao_user.thongbao_id', '=', 'thongbao.tb_id')
                    ->where('thongbao_user.user_id', $user->user_id)
                    ->where('thongbao.is_delete', 0)
                    ->orderBy('thongbao.ngay_gui', 'desc')
                    ->limit(5)
                    ->select('thongbao.tb_id', 'thongbao.tieude', 'thongbao_user.da_doc')
                    ->get();

                $sinhviens = DB::table('dangky_thuctap')
                    ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
                    ->join('phancong_giangvien', 'dangky_thuctap.dk_id', '=', 'phancong_giangvien.dk_id')
                    ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                    ->where('phancong_giangvien.gv_id', $gv->gv_id)
                    ->where('dangky_thuctap.is_delete', 0)
                    ->orderBy('dangky_thuctap.ngay_dangky', 'desc')
                    ->limit(5)
                    ->select(
                        'sinhvien.ma_sv',
                        'sinhvien.ho_ten',
                        'dangky_thuctap.trang_thai as trang_thai',
                        'vitri_thuctap.ten_vitri',
                        'vitri_thuctap.mo_ta',
                        'vitri_thuctap.yeu_cau',
                        'vitri_thuctap.soluong'
                    )
                    ->get();
            }
        } 
        // ====== Doanh nghiệp ======
        elseif ($role == 'DoanhNghiep') {
            $dn = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();
            if ($dn) {
                $totalDangKy = DB::table('dangky_thuctap')
                    ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                    ->where('vitri_thuctap.dn_id', $dn->dn_id)
                    ->where('dangky_thuctap.is_delete', 0)
                    ->count();

                $totalTiendo = DB::table('tiendo')
                    ->join('dangky_thuctap', 'tiendo.dk_id', '=', 'dangky_thuctap.dk_id')
                    ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                    ->where('vitri_thuctap.dn_id', $dn->dn_id)
                    ->where('tiendo.is_delete', 0)
                    ->count();

                $totalBaocao = DB::table('baocao_thuctap')
                    ->join('dangky_thuctap', 'baocao_thuctap.dk_id', '=', 'dangky_thuctap.dk_id')
                    ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                    ->where('vitri_thuctap.dn_id', $dn->dn_id)
                    ->where('baocao_thuctap.is_delete', 0)
                    ->count();

                $thongbaos = DB::table('thongbao_user')
                    ->join('thongbao', 'thongbao_user.thongbao_id', '=', 'thongbao.tb_id')
                    ->where('thongbao_user.user_id', $user->user_id)
                    ->where('thongbao.is_delete', 0)
                    ->orderBy('thongbao.ngay_gui', 'desc')
                    ->limit(5)
                    ->select('thongbao.tb_id', 'thongbao.tieude', 'thongbao_user.da_doc')
                    ->get();

                $sinhviens = DB::table('dangky_thuctap')
                    ->join('sinhvien', 'dangky_thuctap.sv_id', '=', 'sinhvien.sv_id')
                    ->join('vitri_thuctap', 'dangky_thuctap.vitri_id', '=', 'vitri_thuctap.vitri_id')
                    ->where('vitri_thuctap.dn_id', $dn->dn_id)
                    ->where('dangky_thuctap.is_delete', 0)
                    ->orderBy('dangky_thuctap.ngay_dangky', 'desc')
                    ->limit(5)
                    ->select(
                        'sinhvien.ma_sv',
                        'sinhvien.ho_ten',
                        'dangky_thuctap.trang_thai as trang_thai',
                        'vitri_thuctap.ten_vitri',
                        'vitri_thuctap.mo_ta',
                        'vitri_thuctap.yeu_cau',
                        'vitri_thuctap.soluong'
                    )
                    ->get();
            }
        }

        return view('giangvien.dashboardtq', compact(
            'totalDangKy', 'totalTiendo', 'totalBaocao', 'role', 'thongbaos', 'sinhviens'
        ));
    }
    // Lấy tất cả thông báo cho user
    public function tatCaThongBao()
    {
        $user = Auth::user();

        $thongBaoList = DB::table('thongbao_user')
    ->join('thongbao', 'thongbao_user.thongbao_id', '=', 'thongbao.tb_id')
    ->leftJoin('users as nguoi_gui', 'thongbao.nguoi_gui_id', '=', 'nguoi_gui.user_id')
    ->where('thongbao_user.user_id', $user->user_id)
    ->where('thongbao.is_delete', 0)
    ->orderBy('thongbao.ngay_gui', 'desc')
    ->select(
        'thongbao.tb_id',
        'thongbao.tieude',
        'thongbao.noidung',
        'thongbao.ngay_gui',
        'thongbao_user.da_doc',
        'thongbao_user.thoi_gian_doc',
        'nguoi_gui.username as ten_nguoi_gui'
    )
    ->paginate(10); // <--- paginate thay vì get


        return view('giangvien.tatcathongbaotq', compact('thongBaoList'));
    }

    // Xem chi tiết 1 thông báo
    public function chiTietThongBao($id)
{
    $user = Auth::user();

    $thongBao = DB::table('thongbao_user')
        ->join('thongbao', 'thongbao_user.thongbao_id', '=', 'thongbao.tb_id')
        ->leftJoin('users as nguoi_gui', 'thongbao.nguoi_gui_id', '=', 'nguoi_gui.user_id')
        ->leftJoin('giangvien', 'nguoi_gui.user_id', '=', 'giangvien.user_id')
        ->leftJoin('doanhnghiep', 'nguoi_gui.user_id', '=', 'doanhnghiep.leader_user_id')
        ->leftJoin('sinhvien', 'nguoi_gui.user_id', '=', 'sinhvien.user_id')
        ->where('thongbao_user.user_id', $user->user_id)
        ->where('thongbao_user.thongbao_id', $id)
        ->where('thongbao.is_delete', 0)
        ->select(
            'thongbao.tb_id',
            'thongbao.tieude',
            'thongbao.noidung',
            'thongbao.ngay_gui',
            'thongbao_user.da_doc',
            'thongbao_user.thoi_gian_doc',
            'nguoi_gui.username',
            DB::raw("COALESCE(giangvien.ho_ten, doanhnghiep.ten_dn, sinhvien.ho_ten, nguoi_gui.username) as ten_nguoi_gui")
        )
        ->first();

    if (!$thongBao) {
        abort(404, 'Thông báo không tồn tại.');
    }

    // Nếu chưa đọc -> cập nhật trạng thái đã đọc
    if (!$thongBao->da_doc) {
        DB::table('thongbao_user')
            ->where('user_id', $user->user_id)
            ->where('thongbao_id', $id)
            ->update([
                'da_doc' => 1,
                'thoi_gian_doc' => Carbon::now()
            ]);
        $thongBao->da_doc = 1;
        $thongBao->thoi_gian_doc = Carbon::now();
    }

    return view('giangvien.thongbaochitiettq', compact('thongBao'));
}

}