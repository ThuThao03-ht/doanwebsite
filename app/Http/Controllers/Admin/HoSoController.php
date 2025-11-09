<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HoSoController extends Controller
{
    // Trang xem hồ sơ
    public function index()
    {
        $user = Auth::user();
        $roleName = DB::table('roles')->where('role_id', $user->role_id)->value('role_name');

        $thongtin = null;

        switch (strtolower($roleName)) {
            case 'sinhvien':
                $thongtin = DB::table('sinhvien')->where('user_id', $user->user_id)->first();
                break;
            case 'giangvien':
                $thongtin = DB::table('giangvien')->where('user_id', $user->user_id)->first();
                break;
            case 'doanhnghiep':
                $thongtin = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();
                break;
            default:
                $thongtin = null;
                break;
        }

        return view('hoso.hoso', compact('user', 'roleName', 'thongtin'));
    }

    // Cập nhật thông tin hồ sơ (không cập nhật mật khẩu)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'sdt' => 'nullable|string|max:20',
        ]);

        DB::table('users')->where('user_id', $user->user_id)->update([
            'username' => $request->username,
            'updated_at' => now(),
        ]);

        // Lấy role để xác định bảng cần cập nhật
        $roleName = DB::table('roles')->where('role_id', $user->role_id)->value('role_name');

        if ($roleName == 'SinhVien') {
            DB::table('sinhvien')->where('user_id', $user->user_id)->update([
                'email' => $request->email,
                'sdt' => $request->sdt,
                'updated_at' => now(),
            ]);
        } elseif ($roleName == 'GiangVien') {
            DB::table('giangvien')->where('user_id', $user->user_id)->update([
                'email' => $request->email,
                'sdt' => $request->sdt,
                'updated_at' => now(),
            ]);
        } elseif ($roleName == 'DoanhNghiep') {
            DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->update([
                'email' => $request->email,
                'lien_he' => $request->sdt,
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Cập nhật thông tin hồ sơ thành công!');
    }

    // Cập nhật avatar
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = 'D:\doan\public\storage\upload\avatar\\';

        if (!File::exists($avatarPath)) {
            File::makeDirectory($avatarPath, 0755, true);
        }

        $file = $request->file('avatar');
        $fileName = 'avatar_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($avatarPath, $fileName);

        $relativePath = 'storage/upload/avatar/' . $fileName;

        DB::table('users')->where('user_id', $user->user_id)->update([
            'avatar' => $relativePath,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Cập nhật ảnh đại diện thành công!');
    }
  
    public function showDoiMatKhauForm()
{
    return view('hoso.doimatkhau');
}
public function doiMatKhau(Request $request)
{
    $user = Auth::user();

    // Validate dữ liệu
    $request->validate([
        'mat_khau_cu' => 'required|string|min:6',
        'mat_khau_moi' => 'required|string|min:6|confirmed',
    ], [
        'mat_khau_cu.required' => 'Vui lòng nhập mật khẩu cũ',
        'mat_khau_cu.min' => 'Mật khẩu cũ phải có ít nhất 6 ký tự',
        'mat_khau_moi.required' => 'Vui lòng nhập mật khẩu mới',
        'mat_khau_moi.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
        'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu không khớp',
    ]);

    // Kiểm tra mật khẩu cũ
    if (!Hash::check($request->mat_khau_cu, $user->password_hash)) {
        return redirect()->back()->with('error', 'Mật khẩu cũ không đúng!');
    }

    // Cập nhật mật khẩu mới
    DB::table('users')->where('user_id', $user->user_id)->update([
        'password_hash' => Hash::make($request->mat_khau_moi),
        'mat_khau_moi' => 0, // đã đổi lần đầu
        'updated_at' => now(),
    ]);

    // 🔹 Đăng xuất người dùng
    Auth::logout();

    // 🔹 Hủy session hiện tại để bảo mật
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // 🔹 Chuyển hướng đến trang đăng nhập
    return redirect('/login')->with('success', 'Đổi mật khẩu thành công! Vui lòng đăng nhập lại.');
}


}