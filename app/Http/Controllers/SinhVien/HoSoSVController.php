<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HoSoSVController extends Controller
{
    // 🧭 Trang xem hồ sơ sinh viên
    public function index()
    {
        $user = Auth::user();

        // Lấy role của người dùng
        $roleName = DB::table('roles')->where('role_id', $user->role_id)->value('role_name') ?? 'SinhVien';

        // Lấy thông tin sinh viên
        $thongtin = DB::table('sinhvien')->where('user_id', $user->user_id)->first();

        return view('sinhvien.hososv', compact('user', 'roleName', 'thongtin'));
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

        // Cập nhật bảng sinhvien
        DB::table('sinhvien')->where('user_id', $user->user_id)->update([
            'email' => $request->email,
            'sdt' => $request->sdt,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Cập nhật thông tin hồ sơ thành công!');
    }

    // 🖼️ Cập nhật ảnh đại diện
//   public function updateAvatar(Request $request)
// {
//     $user = Auth::user();

//     // 🧩 1. Kiểm tra file upload hợp lệ
//     $request->validate([
//         'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//     ], [
//         'avatar.required' => 'Vui lòng chọn ảnh để tải lên.',
//         'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
//         'avatar.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
//         'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
//     ]);

//     // 🧩 2. Đảm bảo thư mục tồn tại
//     $avatarPath = public_path('storage/upload/avatar/');
//     if (!File::exists($avatarPath)) {
//         File::makeDirectory($avatarPath, 0755, true);
//     }

//     // 🧩 3. Xử lý upload file
//     $file = $request->file('avatar');
//     $fileName = 'avatar_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
//     $file->move($avatarPath, $fileName);

//     // 🧩 4. Nếu người dùng có avatar cũ → xóa file cũ
//     if ($user->avatar) {
//         $oldPath = public_path('storage/upload/avatar/' . $user->avatar);
//         if (File::exists($oldPath)) {
//             File::delete($oldPath);
//         }
//     }

//     // 🧩 5. Cập nhật DB – chỉ lưu tên file
//     DB::table('users')->where('user_id', $user->user_id)->update([
//         'avatar' => $fileName,
//         'updated_at' => now(),
//     ]);

//     // 🧩 6. Cập nhật lại thông tin trong session Auth (để hiển thị ngay ảnh mới)
//     $user->avatar = $fileName;
//     Auth::setUser($user);

//     // 🧩 7. Trả về kết quả
//     return redirect()->back()->with('success', 'Cập nhật ảnh đại diện thành công!');
// }

// 🖼️ Cập nhật ảnh đại diện
public function updateAvatar(Request $request)
{
    $user = Auth::user();

    try {
        // 1️⃣ Kiểm tra file upload hợp lệ
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Vui lòng chọn ảnh để tải lên.',
            'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        // 2️⃣ Đảm bảo thư mục lưu ảnh tồn tại
        $avatarPath = public_path('storage/upload/avatar/');
        if (!File::exists($avatarPath)) {
            File::makeDirectory($avatarPath, 0755, true);
        }

        // 3️⃣ Xử lý upload file mới
        $file = $request->file('avatar');
        $fileName = 'avatar_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($avatarPath, $fileName);

        // 4️⃣ Nếu có ảnh cũ → xóa đi
        if (!empty($user->avatar)) {
            $oldPath = public_path('storage/upload/avatar/' . $user->avatar);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        // 5️⃣ Cập nhật cơ sở dữ liệu
        DB::table('users')->where('user_id', $user->user_id)->update([
            'avatar' => $fileName,
            'updated_at' => now(),
        ]);

        // 6️⃣ Cập nhật lại session Auth để ảnh mới hiển thị ngay
        $user->avatar = $fileName;
        Auth::setUser($user);

        // 7️⃣ Phản hồi thành công
        return redirect()->back()->with('success', 'Cập nhật ảnh đại diện thành công!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->with('error', 'Ảnh tải lên không hợp lệ!')->withErrors($e->errors());
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật ảnh đại diện: ' . $e->getMessage());
    }
}


    // Hiển thị form đổi mật khẩu
public function showDoiMatKhauForm()
{
    return view('sinhvien.doimatkhausv');
}

    //  Xử lý đổi mật khẩu
   public function doiMatKhau(Request $request)
{
    $user = Auth::user();

    // Kiểm tra dữ liệu nhập
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

    //  Kiểm tra mật khẩu cũ
    if (!Hash::check($request->mat_khau_cu, $user->password_hash)) {
        return redirect()->back()->with('error', 'Mật khẩu cũ không đúng!');
    }

    // Cập nhật mật khẩu mới vào DB
    DB::table('users')->where('user_id', $user->user_id)->update([
        'password_hash' => Hash::make($request->mat_khau_moi),
        'updated_at' => now(),
    ]);

    //  Đăng xuất người dùng hiện tại
    Auth::logout();

    // Xóa session hiện tại để bảo mật
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Chuyển hướng về trang đăng nhập
    return redirect('/login')->with('success', 'Đổi mật khẩu thành công! Vui lòng đăng nhập lại.');
}

}