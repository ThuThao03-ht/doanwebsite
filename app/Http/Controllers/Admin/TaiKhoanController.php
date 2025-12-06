<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class TaiKhoanController extends Controller
{
    // Hiển thị danh sách tài khoản
public function index(Request $request)
{
    $roleFilter = $request->input('role_id'); // role được chọn (nếu có)

    $query = User::with('role')->where('is_delete', 0);

    if (!empty($roleFilter)) {
        $query->where('role_id', $roleFilter);
    }

    // Sắp xếp user_id tăng dần
    $users = $query->orderBy('user_id', 'asc')->paginate(10);

    $roles = Role::where('is_delete', 0)->get();

    return view('admin.taikhoan', compact('users', 'roles', 'roleFilter'));
}


    // Xem chi tiết tài khoản (AJAX)
   public function show($id)
{
    $user = User::with('role', 'sinhvien', 'giangvien', 'doanhnghiep')->find($id);

    if ($user && $user->avatar) {
        // Lấy tên file từ đường dẫn nếu lưu full path
        $user->avatar = basename($user->avatar);
    }

    return response()->json($user);
}

    // Khóa hoặc mở khóa tài khoản
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()->with('success', 'Thay đổi trạng thái thành công!');
    }



}