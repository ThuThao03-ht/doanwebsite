<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    // Hiển thị danh sách vai trò
    public function index(Request $request)
{
    $roles = Role::where('is_delete', false)
        ->withCount(['users' => function ($query) {
            $query->where('is_delete', false);
        }])
        ->get();

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    return view('admin.roles', compact('roles'));
}


    // Lưu vai trò mới
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:50|unique:roles,role_name',
        ]);

        Role::create(['role_name' => $request->role_name]);

        return redirect()->route('admin.roles.index')->with('success', 'Thêm vai trò thành công!');
    }

    // Cập nhật vai trò
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role || $role->is_delete) {
            return redirect()->route('admin.roles.index')->with('error', 'Không tìm thấy vai trò cần cập nhật.');
        }

        $request->validate([
            'role_name' => 'required|string|max:50|unique:roles,role_name,' . $id . ',role_id',
        ]);

        $role->update(['role_name' => $request->role_name]);

        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công!');
    }

    // Xóa logic vai trò
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role || $role->is_delete) {
            return redirect()->route('admin.roles.index')->with('error', 'Không tìm thấy vai trò cần xóa.');
        }

        $role->update(['is_delete' => true]);

        return redirect()->route('admin.roles.index')->with('success', 'Xóa vai trò thành công!');
    }
}