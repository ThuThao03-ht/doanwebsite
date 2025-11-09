<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HoSoTQController extends Controller
{
    // 🧭 Trang xem hồ sơ giảng viên
   public function index()
{
    $user = Auth::user();

    // Lấy tên quyền của người dùng
    $roleName = DB::table('roles')->where('role_id', $user->role_id)->value('role_name') ?? '';

    $thongtin = null;

    // Nếu là giảng viên
    if ($roleName === 'GiangVien') {
        $thongtin = DB::table('giangvien')->where('user_id', $user->user_id)->first();
    }
    // Nếu là doanh nghiệp
    elseif ($roleName === 'DoanhNghiep') {
        $thongtin = DB::table('doanhnghiep')->where('leader_user_id', $user->user_id)->first();
    }

    return view('giangvien.hosotq', compact('user', 'roleName', 'thongtin'));
}


    // ✏️ Cập nhật thông tin hồ sơ
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'sdt' => 'nullable|string|max:20',
        ]);

        // Cập nhật bảng users
        DB::table('users')->where('user_id', $user->user_id)->update([
            'username' => $request->username,
            'updated_at' => now(),
        ]);

        // Cập nhật bảng giảng viên
        DB::table('giangvien')->where('user_id', $user->user_id)->update([
            'email' => $request->email,
            'sdt' => $request->sdt,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Cập nhật thông tin hồ sơ thành công!');
    }

    // 🖼️ Cập nhật ảnh đại diện
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'avatar.required' => 'Vui lòng chọn ảnh để tải lên.',
                'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
                'avatar.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
                'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            ]);

            $avatarPath = public_path('storage/upload/avatar/');
            if (!File::exists($avatarPath)) {
                File::makeDirectory($avatarPath, 0755, true);
            }

            $file = $request->file('avatar');
            $fileName = 'avatar_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($avatarPath, $fileName);

            // Xóa ảnh cũ nếu có
            if (!empty($user->avatar)) {
                $oldPath = public_path('storage/upload/avatar/' . $user->avatar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Cập nhật DB
            DB::table('users')->where('user_id', $user->user_id)->update([
                'avatar' => $fileName,
                'updated_at' => now(),
            ]);

            $user->avatar = $fileName;
            Auth::setUser($user);

            return redirect()->back()->with('success', 'Cập nhật ảnh đại diện thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('error', 'Ảnh tải lên không hợp lệ!')->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật ảnh đại diện: ' . $e->getMessage());
        }
    }

    // 🔒 Hiển thị form đổi mật khẩu
    public function showDoiMatKhauForm()
    {
        return view('giangvien.doimatkhautq');
    }

    // 🔐 Xử lý đổi mật khẩu
    public function doiMatKhau(Request $request)
    {
        $user = Auth::user();

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

        if (!Hash::check($request->mat_khau_cu, $user->password_hash)) {
            return redirect()->back()->with('error', 'Mật khẩu cũ không đúng!');
        }

        DB::table('users')->where('user_id', $user->user_id)->update([
            'password_hash' => Hash::make($request->mat_khau_moi),
            'updated_at' => now(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Đổi mật khẩu thành công! Vui lòng đăng nhập lại.');
    }
}