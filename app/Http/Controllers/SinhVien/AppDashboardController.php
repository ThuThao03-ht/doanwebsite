<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ViTriThucTap;
use App\Models\DangKyThucTap;
use App\Models\ThongBao;
use App\Models\ThongBaoUser;
use App\Models\SinhVien;


class AppDashboardController extends Controller
{
    /**
     * Trang dashboard sinh viên
     */
    public function index()
    {
        $user = Auth::user();

        // ===============================
        //  Lấy thông tin sinh viên đang đăng nhập
        // ===============================
        $sinhvien = SinhVien::where('user_id', $user->user_id)->first();

        if (!$sinhvien) {
            abort(403, 'Không tìm thấy thông tin sinh viên');
        }

        // ===============================
        //  Thống kê nhanh
        // ===============================

        // Vị trí đang mở (trạng thái "con_han")
        $countViTriMo = ViTriThucTap::where('trang_thai', 'con_han')
            ->where('is_delete', 0)
            ->count();

        // Số đăng ký của sinh viên hiện tại
        $countDangKy = DangKyThucTap::where('sv_id', $sinhvien->sv_id)
            ->where('is_delete', 0)
            ->count();

        // Số thông báo mới (đối tượng "tat_ca" hoặc "sinhvien" và chưa đọc)
        $countThongBaoMoi = ThongBaoUser::where('user_id', $user->user_id)
            ->where('da_doc', 0)
            ->count();

        // ===============================
        //  Danh sách vị trí thực tập
        // ===============================
       // Lấy trạng thái lọc từ request
$trangThaiFilter = request('trang_thai'); // có thể là 'con_han' hoặc 'het_han'

$viTriThucTapQuery = ViTriThucTap::with('doanhNghiep')
    ->where('is_delete', 0)
    ->orderBy('created_at', 'desc');

if ($trangThaiFilter === 'con_han') {
    $viTriThucTapQuery->where('trang_thai', 'con_han');
} elseif ($trangThaiFilter === 'het_han') {
    $viTriThucTapQuery->where('trang_thai', 'het_han');
}

$viTriThucTap = $viTriThucTapQuery ->take(3)->get();


        // ===============================
        // Đăng ký của sinh viên
        // ===============================
        $dangKyList = DangKyThucTap::with(['viTriThucTap.doanhNghiep'])
            ->where('sv_id', $sinhvien->sv_id)
            ->where('is_delete', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // ===============================
        //  Thông báo mới (gửi cho tat_ca hoặc sinhvien)
        // ===============================
$thongBaoList = ThongBaoUser::join('thongbao', 'thongbao.tb_id', '=', 'thongbao_user.thongbao_id')
    ->where('thongbao_user.user_id', $user->user_id)
    ->where('thongbao_user.da_doc', 0)
    ->where('thongbao.is_delete', 0)
    ->whereIn('thongbao.doi_tuong', ['tat_ca', 'sinhvien'])
    ->orderBy('thongbao.ngay_gui', 'desc')
    ->take(5)
    ->get([
        'thongbao.tb_id',          
        'thongbao.tieude',
        'thongbao.noidung',
        'thongbao.ngay_gui',
        'thongbao_user.da_doc'
    ]);



        // ===============================
        //  Trả dữ liệu về view
        // ===============================
        // return view('sinhvien.dashboard', [
        //     'countViTriMo'      => $countViTriMo,
        //     'countDangKy'       => $countDangKy,
        //     'countThongBaoMoi'  => $countThongBaoMoi,
        //     'viTriThucTap'      => $viTriThucTap,
        //     'dangKyList'        => $dangKyList,
        //     'thongBaoList'      => $thongBaoList,
        // ]);
         $latestDangKy = $dangKyList->first();
        $phanTramTienDo = $this->tinhPhanTramTienDo($latestDangKy->trang_thai ?? null);
        $mauTienDo = $this->getColorByProgress($phanTramTienDo);
        $textTienDo = $this->getProgressText($phanTramTienDo);

        return view('sinhvien.dashboard', [
            'countViTriMo'      => $countViTriMo,
            'countDangKy'       => $countDangKy,
            'countThongBaoMoi'  => $countThongBaoMoi,
            'viTriThucTap'      => $viTriThucTap,
            'dangKyList'        => $dangKyList,
            'thongBaoList'      => $thongBaoList,
            'phanTramTienDo'    => $phanTramTienDo,
            'mauTienDo'         => $mauTienDo,
            'textTienDo'        => $textTienDo,
        ]);
    }

    public function countThongBaoMoi()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $count = ThongBaoUser::join('thongbao', 'thongbao.tb_id', '=', 'thongbao_user.thongbao_id')
        ->where('thongbao_user.user_id', $user->user_id)
        ->where('thongbao_user.da_doc', 0)
        ->where('thongbao.is_delete', 0)
        ->whereIn('thongbao.doi_tuong', ['tat_ca', 'sinhvien'])
        ->count();

    return response()->json([
        'unread_count' => $count
    ]);
}

//  * Xem chi tiết 1 thông báo
//      */
   public function xemThongBao($id)
{
    $user = Auth::user();

    //  Cập nhật trạng thái đã đọc trước
    ThongBaoUser::where('user_id', $user->user_id)
        ->where('thongbao_id', $id)
        ->update([
            'da_doc' => 1,
            'thoi_gian_doc' => now(),
        ]);

    //  Lấy lại thông tin chi tiết sau khi cập nhật
    $thongBao = ThongBao::join('users', 'users.user_id', '=', 'thongbao.nguoi_gui_id')
        ->leftJoin('thongbao_user', function ($join) use ($user) {
            $join->on('thongbao_user.thongbao_id', '=', 'thongbao.tb_id')
                ->where('thongbao_user.user_id', '=', $user->user_id);
        })
        ->where('thongbao.tb_id', $id)
        ->whereIn('thongbao.doi_tuong', ['tat_ca', 'sinhvien'])
        ->where('thongbao.is_delete', 0)
        ->select(
            'thongbao.*',
            'users.username as ten_nguoi_gui',
            'thongbao_user.da_doc',
            'thongbao_user.thoi_gian_doc'
        )
        ->firstOrFail();

    return view('sinhvien.thongbao_chitiet', compact('thongBao'));
}
public function xemViTri($id)
{
    $vitri = ViTriThucTap::with('doanhNghiep')->where('vitri_id', $id)->firstOrFail();

    return response()->json([
        'vitri' => $vitri
    ]);
}

public function dangKyViTri(Request $request)
{
    $user = Auth::user();
    $sv = SinhVien::where('user_id', $user->user_id)->firstOrFail();

    $vitri = ViTriThucTap::where('vitri_id', $request->vitri_id)->firstOrFail();

    if ($vitri->trang_thai !== 'con_han' || $vitri->so_luong_da_dangky >= $vitri->soluong) {
        return redirect()->back()->with('error', 'Vị trí này đã hết hạn hoặc đã đủ số lượng đăng ký!');
    }

    // CHẶN đăng ký mới nếu sinh viên đang có đăng ký hoạt động
    $daDangKy = DangKyThucTap::where('sv_id', $sv->sv_id)
        ->where('is_delete', 0)
        ->whereIn('trang_thai', ['cho_duyet', 'da_duyet', 'dang_thuctap'])
        ->exists();

    if ($daDangKy) {
        return redirect()->back()->with('error', 'Bạn chỉ được phép đăng ký 1 vị trí thực tập. Vui lòng hủy đăng ký hiện tại trước khi đăng ký vị trí mới!');
    }

    DangKyThucTap::create([
        'sv_id' => $sv->sv_id,
        'vitri_id' => $vitri->vitri_id,
        'trang_thai' => 'cho_duyet'
    ]);

    $vitri->so_luong_da_dangky += 1;
    if ($vitri->so_luong_da_dangky >= $vitri->soluong) {
        $vitri->trang_thai = 'het_han';
    }
    $vitri->save();

    return redirect()->back()->with('success', 'Đăng ký vị trí thực tập thành công!');
}

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

public function huyDangKy($id)
{
    $dk = DangKyThucTap::findOrFail($id);

    // Chỉ cho phép hủy khi trạng thái là "chờ duyệt"
    if ($dk->trang_thai !== 'cho_duyet') {
        return redirect()->back()->with('error', 'Chỉ có thể hủy khi trạng thái là "chờ duyệt"!');
    }

    $vitri = ViTriThucTap::find($dk->vitri_id);

    // Cập nhật lại số lượng vị trí nếu được phép hủy
    if ($vitri) {
        $vitri->so_luong_da_dangky = max(0, $vitri->so_luong_da_dangky - 1);

        // Nếu vị trí trước đó "hết hạn" mà nay chưa đủ người thì mở lại
        if ($vitri->trang_thai === 'het_han' && $vitri->so_luong_da_dangky < $vitri->soluong) {
            $vitri->trang_thai = 'con_han';
        }

        $vitri->save();
    }

    // Cập nhật trạng thái đăng ký thành "từ chối" hoặc "hủy"
    $dk->trang_thai = 'tu_choi'; // hoặc 'huy' tùy cách bạn định nghĩa
    $dk->is_delete = 1;
    $dk->save();

    return redirect()->back()->with('success', 'Hủy đăng ký thực tập thành công!');
}

public function tatCaThongBao()
{
    $user = Auth::user();

    if (!$user) {
        abort(403, 'Không có quyền truy cập');
    }

    // Lấy tất cả thông báo của user (đã đọc + chưa đọc)
    $thongBaoList = ThongBaoUser::join('thongbao', 'thongbao.tb_id', '=', 'thongbao_user.thongbao_id')
        ->where('thongbao_user.user_id', $user->user_id)
        ->where('thongbao.is_delete', 0)
        ->whereIn('thongbao.doi_tuong', ['tat_ca', 'sinhvien'])
        ->orderBy('thongbao.ngay_gui', 'desc')
        ->get([
            'thongbao.tb_id',
            'thongbao.tieude',
            'thongbao.noidung',
            'thongbao.ngay_gui',
            'thongbao_user.da_doc',
            'thongbao_user.thoi_gian_doc'
        ]);

    return view('sinhvien.thongbao_danhsach', compact('thongBaoList'));
}

private function tinhPhanTramTienDo($trangThai)
{
    return match ($trangThai) {
        'cho_duyet'     => 20,
        'da_duyet'      => 40,
        'dang_thuctap'  => 70,
        'hoan_thanh'    => 100,
        default         => 0,
    };
}

/**
 * Chọn màu hiển thị tương ứng với phần trăm tiến độ
 */
private function getColorByProgress($phanTram)
{
    return match (true) {
        $phanTram <= 20   => '#FACC15', // vàng
        $phanTram <= 40   => '#60A5FA', // xanh dương nhạt
        $phanTram <= 70   => '#34D399', // xanh lá
        $phanTram == 100  => '#4a7fa7', // xám
        default           => '#D1D5DB', // mặc định xám nhạt
    };
}

/**
 * Mô tả văn bản tương ứng với phần trăm tiến độ
 */
private function getProgressText($phanTram)
{
    return match ($phanTram) {
        0    => 'Chưa đăng ký',
        20   => 'Chờ duyệt',
        40   => 'Đã duyệt',
        70   => 'Thực tập',
        100  => 'Hoàn thành',
        default => 'Không xác định',
    };
}
}