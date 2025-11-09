<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Hiển thị trang login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập


// public function login(Request $request)
// {
//     $request->validate([
//     'username' => 'required|string',
//     'password' => 'required|string',
//     'g-recaptcha-response' => 'required|captcha',
// ], [
//     'g-recaptcha-response.required' => 'Vui lòng xác nhận bạn không phải robot.',
//     'g-recaptcha-response.captcha' => 'Mã xác thực không hợp lệ!',
// ]);

//     // Tìm user theo username
//     $user = DB::table('users')->where('username', $request->username)->first();

//     if ($user && Hash::check($request->password, $user->password_hash)) {
//         // Đăng nhập user theo ID
//         Auth::loginUsingId($user->user_id);

//         // Kiểm tra quyền
//         if ($user->role_id == 1) {
//     return redirect()->route('admin.dashboard');
// } elseif ($user->role_id == 2) {
//     return redirect()->route('sinhvien.dashboard');
// } elseif ($user->role_id == 3) {
//     return redirect()->route('giangvien.dashboard');
// } elseif ($user->role_id == 4) {
//     return redirect()->route('giangvien.dashboard');
//         } else {
//             Auth::logout();
//             return redirect()->route('no-permission')->with('error', 'Tài khoản của bạn không có quyền truy cập!');
//         }

//     } else {
//         return redirect()->back()->with('error', 'Tên đăng nhập hoặc mật khẩu không chính xác!');
//     }
// }

public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
        'g-recaptcha-response' => 'required|captcha',
    ], [
        'g-recaptcha-response.required' => 'Vui lòng xác nhận bạn không phải robot.',
        'g-recaptcha-response.captcha' => 'Mã xác thực không hợp lệ!',
    ]);

    // Tìm user theo username
    $user = DB::table('users')->where('username', $request->username)->first();

    if (!$user) {
        return back()->with('error', 'Tên đăng nhập hoặc mật khẩu không chính xác!');
    }

    // 🔒 Kiểm tra tài khoản có bị khóa không
    if ($user->status === 'inactive') {
        return back()->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên!');
    }

    // Kiểm tra mật khẩu
    if (!Hash::check($request->password, $user->password_hash)) {
        return back()->with('error', 'Tên đăng nhập hoặc mật khẩu không chính xác!');
    }

    // Đăng nhập user theo ID
    Auth::loginUsingId($user->user_id);

    // Kiểm tra quyền
    if ($user->role_id == 1) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role_id == 2) {
        return redirect()->route('sinhvien.dashboard');
    } elseif ($user->role_id == 3) {
        return redirect()->route('giangvien.dashboard');
    } elseif ($user->role_id == 4) {
        return redirect()->route('giangvien.dashboard');
    } else {
        Auth::logout();
        return redirect()->route('no-permission')->with('error', 'Tài khoản của bạn không có quyền truy cập!');
    }
}

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }

    // =========================
    //  Xử lý Quên mật khẩu
    // =========================
public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    // Tìm theo email trong 3 bảng
    $sv = DB::table('sinhvien')->where('email', $request->email)->first();
    $gv = DB::table('giangvien')->where('email', $request->email)->first();
    $dn = DB::table('doanhnghiep')->where('email', $request->email)->first();

    $user = null;
    $displayName = null; // Tên hiển thị trong email

    if ($sv) {
        $user = DB::table('users')->where('user_id', $sv->user_id)->first();
        $displayName = $sv->ho_ten;
    } elseif ($gv) {
        $user = DB::table('users')->where('user_id', $gv->user_id)->first();
        $displayName = $gv->ho_ten;
    } elseif ($dn) {
        $user = DB::table('users')->where('user_id', $dn->leader_user_id)->first();
        $displayName = $dn->ten_dn; // Doanh nghiệp thì lấy tên DN
    }

    if (!$user) {
        return back()->with('error', 'Không tìm thấy tài khoản với email này!');
    }

    // Tạo mật khẩu mới ngẫu nhiên
    $newPassword = Str::random(8);

    // Cập nhật mật khẩu mới
    DB::table('users')->where('user_id', $user->user_id)
        ->update(['password_hash' => Hash::make($newPassword)]);

    // Chuẩn bị dữ liệu gửi email
    $data = [
        'name' => $displayName ?? $user->username,
        'newPassword' => $newPassword,
        'loginUrl' => url('/login')
    ];

    // Gửi email HTML
    try {
        Mail::send('emails.reset_password', $data, function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Khôi phục mật khẩu - Hệ thống Quản lý Thực tập Doanh nghiệp');
        });
    } catch (\Exception $e) {
        return back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
    }

    return back()->with('success', 'Mật khẩu mới đã được gửi đến email của bạn!');
}

}