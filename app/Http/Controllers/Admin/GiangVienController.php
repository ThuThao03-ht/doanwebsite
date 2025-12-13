<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiangVien;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GiangVienController extends Controller
{
    /**
     * Hiển thị danh sách giảng viên
     */
 public function index(Request $request)
{
    // Khởi tạo query cơ bản (chỉ lấy giảng viên chưa bị xóa)
    $query = GiangVien::where('is_delete', 0);

    // 🔍 Lọc theo bộ môn (nếu có chọn)
    if ($request->filled('bo_mon')) {
        $query->where('bo_mon', $request->bo_mon);
    }

    // 🔎 Tìm kiếm theo họ tên (nếu có nhập từ khóa)
    if ($request->filled('keyword')) {
        $query->where('ho_ten', 'LIKE', '%' . $request->keyword . '%');
    }

    // Sắp xếp mới nhất
    $giangviens = $query->orderByDesc('created_at')->get();

    // Lấy danh sách bộ môn duy nhất (để hiển thị trong dropdown lọc)
    $boMons = GiangVien::where('is_delete', 0)
        ->whereNotNull('bo_mon')
        ->distinct()
        ->pluck('bo_mon');

    // Tạo mã GV mới (tự động tăng)
    $lastGV = GiangVien::orderByDesc('gv_id')->first();
    if ($lastGV && preg_match('/GV(\d+)/', $lastGV->ma_gv, $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }
    $newMaGV = 'GV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    return view('admin.giangvien', compact('giangviens', 'boMons', 'newMaGV'));
}


    /**
     * Thêm giảng viên mới
     */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'ma_gv' => 'required|unique:giangvien,ma_gv',
//             'ho_ten' => 'required',
//             'email' => 'required|email|unique:giangvien,email',
//             'sdt' => ['nullable','regex:/^(\+84|0)\d{9}$/'],

//         ]);

//         DB::beginTransaction();
//         try {
//             // Tạo tài khoản user cho giảng viên
//             $role = Role::where('role_name', 'GiangVien')->first();
//             $user = User::create([
//                 'username' => $request->ma_gv,
//                 'password_hash' => Hash::make('123456'),
//                 'role_id' => $role->role_id ?? 3,
//                  'nguoi_tao_id' => 1,
//                 'mat_khau_moi' => 1,
//                 'status' => 'active',
//             ]);

//             // Thêm thông tin giảng viên
//             GiangVien::create([
//                 'ma_gv' => $request->ma_gv,
//                 'ho_ten' => $request->ho_ten,
//                 'bo_mon' => $request->bo_mon,
//                 'email' => $request->email,
//                 'sdt' => $request->sdt,
//                 'user_id' => $user->user_id,
//             ]);

//             DB::commit();
//             return redirect()->back()->with('success', 'Thêm giảng viên thành công!');
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return redirect()->back()->with('error', 'Lỗi khi thêm giảng viên: ' . $e->getMessage());
//         }
//     }

//     /**
//      * Cập nhật giảng viên
//      */
//     public function update(Request $request, $id)
//     {
//         $gv = GiangVien::findOrFail($id);

//         $request->validate([
//             'ho_ten' => 'required',
//             'email' => 'required|email|unique:giangvien,email,' . $id . ',gv_id',
//             'sdt' => ['nullable','regex:/^(\+84|0)\d{9}$/'],

//         ], [
//     'sdt.regex' => 'Số điện thoại không đúng định dạng.',
//     'email.email' => 'Email không hợp lệ.',
//     'email.unique' => 'Email đã được sử dụng.',
// ]);

//         $gv->update([
//             'ho_ten' => $request->ho_ten,
//             'bo_mon' => $request->bo_mon,
//             'email' => $request->email,
//             'sdt' => $request->sdt,
//         ]);

//         return redirect()->back()->with('success', 'Cập nhật giảng viên thành công!');
//     }
public function store(Request $request)
{
    // =======================
    // VALIDATE
    // =======================
    $request->validate([
        'ma_gv' => 'required|unique:giangvien,ma_gv',
        'ho_ten' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique('giangvien', 'email'),
            Rule::unique('users', 'email'),
        ],
        'sdt' => ['nullable', 'regex:/^(\+84|0)\d{9}$/'],
    ], [
        'ma_gv.required' => 'Mã giảng viên không được để trống.',
        'ma_gv.unique'   => 'Mã giảng viên đã tồn tại.',
        'ho_ten.required'=> 'Họ tên không được để trống.',
        'email.required' => 'Email không được để trống.',
        'email.email'    => 'Email không hợp lệ.',
        'email.unique'   => 'Email đã được sử dụng trong hệ thống.',
        'sdt.regex'      => 'Số điện thoại không đúng định dạng.',
    ]);

    DB::beginTransaction();

    try {
        // =======================
        // TẠO USER
        // =======================
        $role = Role::where('role_name', 'GiangVien')->first();

        $user = User::create([
            'username'       => $request->ma_gv,
            'email'          => $request->email,
            'password_hash'  => Hash::make('123456'),
            'role_id'        => $role->role_id ?? 3,
           'nguoi_tao_id' => 1,
            'mat_khau_moi'   => 1,
            'status'         => 'active',
        ]);

        // =======================
        // TẠO GIẢNG VIÊN
        // =======================
        GiangVien::create([
            'ma_gv'   => $request->ma_gv,
            'ho_ten'  => $request->ho_ten,
            'bo_mon'  => $request->bo_mon,
            'email'   => $request->email,
            'sdt'     => $request->sdt,
            'user_id' => $user->user_id,
        ]);

        DB::commit();

        return redirect()
            ->back()
            ->with('success', 'Thêm giảng viên thành công!');
    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Lỗi khi thêm giảng viên.');
    }
}

public function update(Request $request, $id)
{
    $gv = GiangVien::findOrFail($id);

    // =======================
    // VALIDATE EMAIL CHUẨN
    // =======================
    $emailRules = [
        'required',
        'email',
        Rule::unique('giangvien', 'email')->ignore($gv->gv_id, 'gv_id'),
    ];

    if ($gv->user_id) {
        $emailRules[] = Rule::unique('users', 'email')->ignore($gv->user_id, 'user_id');
    } else {
        $emailRules[] = Rule::unique('users', 'email');
    }

    $request->validate([
        'ho_ten' => 'required|string|max:255',
        'email'  => $emailRules,
        'sdt'    => ['nullable', 'regex:/^(\+84|0)\d{9}$/'],
    ], [
        'ho_ten.required' => 'Họ tên không được để trống.',
        'email.required' => 'Email không được để trống.',
        'email.email'    => 'Email không hợp lệ.',
        'email.unique'   => 'Email đã được sử dụng trong hệ thống.',
        'sdt.regex'      => 'Số điện thoại không đúng định dạng.',
    ]);

    DB::beginTransaction();

    try {
        // =======================
        // UPDATE GIẢNG VIÊN
        // =======================
        $gv->update([
            'ho_ten' => $request->ho_ten,
            'bo_mon' => $request->bo_mon,
            'email'  => $request->email,
            'sdt'    => $request->sdt,
        ]);

        // =======================
        // UPDATE USER (NẾU CÓ)
        // =======================
        if ($gv->user_id) {
            User::where('user_id', $gv->user_id)->update([
                'email' => $request->email,
            ]);
        }

        DB::commit();

        return redirect()
            ->back()
            ->with('success', 'Cập nhật giảng viên thành công!');
    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Lỗi khi cập nhật giảng viên.');
    }
}
    /**
     * Xóa mềm giảng viên
     */
    public function destroy($id)
    {
        $gv = GiangVien::findOrFail($id);
        $gv->update(['is_delete' => 1]);
        if ($gv->user_id) {
            User::where('user_id', $gv->user_id)->update(['is_delete' => 1, 'status' => 'inactive']);
        }
        return redirect()->back()->with('success', 'Xóa giảng viên thành công!');
    }

    /**
     * Xem chi tiết giảng viên
     */
    public function show($id)
    {
        $gv = GiangVien::findOrFail($id);
        return response()->json($gv);
    }
}