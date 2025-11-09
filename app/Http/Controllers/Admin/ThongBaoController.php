<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThongBao;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ThongBaoUser;
use Illuminate\Support\Facades\DB;

class ThongBaoController extends Controller
{
    // Hiển thị danh sách thông báo
    public function index()
    {
        $thongbaos = ThongBao::where('is_delete', 0)->orderBy('ngay_gui', 'desc')->get();
        return view('admin.thongbao', compact('thongbaos'));
    }

    // Thêm thông báo
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'tieude' => 'required|string|max:150',
    //         'noidung' => 'required|string',
    //         'doi_tuong' => 'required|in:tat_ca,sinhvien,giangvien,doanhnghiep',
    //     ]);

    //     ThongBao::create([
    //         'tieude' => $request->tieude,
    //         'noidung' => $request->noidung,
    //         'doi_tuong' => $request->doi_tuong,
    //         'nguoi_gui_id' => 1,
    //         'ngay_gui' => now(),
    //     ]);

    //     return redirect()->back()->with('success', 'Tạo thông báo thành công!');
    // }


    public function store(Request $request)
{
    $request->validate([
        'tieude' => 'required|string|max:150',
        'noidung' => 'required|string',
        'doi_tuong' => 'required|in:tat_ca,sinhvien,giangvien,doanhnghiep',
    ]);

    // 1️⃣ Tạo thông báo
    $thongbao = ThongBao::create([
        'tieude' => $request->tieude,
        'noidung' => $request->noidung,
        'doi_tuong' => $request->doi_tuong,
        'nguoi_gui_id' => Auth::id() ?? 1,
        'ngay_gui' => now(),
    ]);

    // 2️⃣ Xác định role_id tương ứng với đối tượng
    $roleMapping = [
        'sinhvien' => 'SinhVien',
        'giangvien' => 'GiangVien',
        'doanhnghiep' => 'DoanhNghiep',
    ];

    // 3️⃣ Lấy danh sách user phù hợp
    if ($request->doi_tuong === 'tat_ca') {
        $users = User::where('is_delete', 0)
            ->where('status', 'active')
            ->get();
    } else {
        $roleName = $roleMapping[$request->doi_tuong];
        $roleId = DB::table('roles')->where('role_name', $roleName)->value('role_id');

        $users = User::where('role_id', $roleId)
            ->where('is_delete', 0)
            ->where('status', 'active')
            ->get();
    }

    // 4️⃣ Ghi vào thongbao_user
    foreach ($users as $user) {
        ThongBaoUser::create([
            'thongbao_id' => $thongbao->tb_id,
            'user_id' => $user->user_id,
            'da_doc' => 0,
        ]);
    }

    return redirect()->back()->with('success', 'Tạo thông báo thành công!');
}

    // Sửa thông báo
    public function update(Request $request, $id)
    {
        $request->validate([
            'tieude' => 'required|string|max:150',
            'noidung' => 'required|string',
            'doi_tuong' => 'required|in:tat_ca,sinhvien,giangvien,doanhnghiep',
        ]);

        $tb = ThongBao::findOrFail($id);
        $tb->update([
            'tieude' => $request->tieude,
            'noidung' => $request->noidung,
            'doi_tuong' => $request->doi_tuong,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thông báo thành công!');
    }

    // Xóa thông báo (xóa mềm)
    public function destroy($id)
    {
        $tb = ThongBao::findOrFail($id);
        $tb->is_delete = 1;
        $tb->save();

        return redirect()->back()->with('success', 'Xóa thông báo thành công!');
    }
}