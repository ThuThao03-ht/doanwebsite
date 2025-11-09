<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoanhNghiep;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoanhNghiepController extends Controller
{
    // Hiển thị danh sách doanh nghiệp
public function index(Request $request)
{
    $query = DoanhNghiep::where('is_delete', 0);

    // Nếu có từ khóa tìm kiếm
    if ($request->has('search') && !empty($request->search)) {
        $keyword = $request->search;
        $query->where('ten_dn', 'LIKE', '%' . $keyword . '%');
    }

    $doanhnghieps = $query->get();

    return view('admin.doanhnghiep', compact('doanhnghieps'));
}

    // Xem chi tiết doanh nghiệp (dùng modal trong view, không cần JSON)
    public function show($id)
    {
        $dn = DoanhNghiep::findOrFail($id);
        return view('admin.doanhnghiep_detail', compact('dn'));
    }

    // Thêm doanh nghiệp và tự tạo tài khoản leader
    public function store(Request $request)
{
    $request->validate([
        'ten_dn'   => 'required|string|max:150',
        'email'    => 'required|email|unique:doanhnghiep,email',
        'lien_he'  => 'nullable|string|max:100',
        'dia_chi'  => 'nullable|string|max:255',
        'website'  => 'nullable|string|max:100',
        'logo'     => 'nullable|string|max:255',
        'mo_ta'    => 'nullable|string',
    ]);

    // Giữ nguyên tên doanh nghiệp làm username (có dấu, có khoảng trắng)
    $username = trim($request->ten_dn);
    $password = Str::random(8);

    // Nếu username trùng, thêm số để tránh trùng
    $originalUsername = $username;
    $count = 1;
    while (User::where('username', $username)->exists()) {
        $username = $originalUsername . ' ' . $count;
        $count++;
    }

    // Tạo tài khoản leader doanh nghiệp
    $user = User::create([
        'username'      => $username,
        'password_hash' => Hash::make('123456'),
        'role_id'       => 4,
        'nguoi_tao_id'  => 1,
        'mat_khau_moi'  => 1,
        'status'        => 'active'
    ]);

    // Tạo doanh nghiệp
    $dn = DoanhNghiep::create([
        'ten_dn'        => $request->ten_dn,
        'email'         => $request->email,
        'lien_he'       => $request->lien_he,
        'dia_chi'       => $request->dia_chi,
        'website'       => $request->website,
        'logo'          => $request->logo,
        'mo_ta'         => $request->mo_ta,
        'leader_user_id'=> $user->user_id
    ]);

    return redirect()->back()->with(
        'success',
        "Thêm doanh nghiệp thành công!"
    );
}



    // Cập nhật doanh nghiệp
    public function update(Request $request, $id)
    {
        $dn = DoanhNghiep::findOrFail($id);

        $request->validate([
            'ten_dn' => 'required|string|max:150',
            'email' => 'required|email|unique:doanhnghiep,email,' . $dn->dn_id . ',dn_id',
            'lien_he' => 'nullable|string|max:100',
            'dia_chi' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:100',
            'logo' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string',
        ]);

        $dn->update($request->only([
            'ten_dn', 'email', 'lien_he', 'dia_chi', 'website', 'logo', 'mo_ta'
        ]));

        return redirect()->back()->with('success', 'Cập nhật doanh nghiệp thành công!');
    }

    // Xóa doanh nghiệp (soft delete)
    public function destroy($id)
    {
        $dn = DoanhNghiep::findOrFail($id);
        $dn->is_delete = 1;
        $dn->save();

        return redirect()->back()->with('success', 'Xóa doanh nghiệp thành công!');
    }
}