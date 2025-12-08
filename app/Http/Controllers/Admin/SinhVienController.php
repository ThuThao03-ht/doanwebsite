<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SinhVien;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SinhVienExport;
use App\Imports\SinhVienImport;



class SinhVienController extends Controller
{


private function taoMaSV()
{
    // Lấy số lớn nhất từ tất cả bản ghi, kể cả is_delete = 1
    $maxNumber = SinhVien::selectRaw("MAX(CAST(SUBSTRING(ma_sv, 3) AS UNSIGNED)) AS max_number")
        ->value('max_number');

    // Nếu chưa có sinh viên nào thì bắt đầu từ 1
    $newNumber = $maxNumber ? $maxNumber + 1 : 1;

    // Tạo mã SV dạng SV001
    return 'SV' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}

    /**
     * Hiển thị danh sách sinh viên
     */
//   public function index()
// {
//     $sinhviens = SinhVien::where('is_delete', 0) ->with('user')
//     ->paginate(10); // hiển thị 10 sinh viên mỗi trang

//     // Lấy mã SV tiếp theo
//     $lastSV = SinhVien::where('is_delete', 0)->orderBy('ma_sv', 'desc')->first();
//     $nextMaSV = $lastSV ? 'SV' . str_pad(intval(substr($lastSV->ma_sv, 2)) + 1, 3, '0', STR_PAD_LEFT) : 'SV001';

//     // Lấy danh sách ngành có sinh viên
//     $dsNganh = SinhVien::where('is_delete', 0)
//         ->distinct()
//         ->pluck('nganh')
//         ->filter() // loại bỏ null/empty
//         ->values(); // reset key

//     return view('admin.sinhvien', compact('sinhviens', 'nextMaSV', 'dsNganh'));
// }

public function index()
{
    $sinhviens = SinhVien::where('is_delete', 0)
        ->with('user')
        ->paginate(10);

    // Tạo mã SV tự động
    $nextMaSV = $this->taoMaSV();

    $dsNganh = SinhVien::where('is_delete', 0)
        ->distinct()
        ->pluck('nganh')
        ->filter()
        ->values();

    return view('admin.sinhvien', compact('sinhviens', 'nextMaSV', 'dsNganh'));
}

    /**
     * Lưu sinh viên mới
     */
//     public function store(Request $request)
//     {
//         DB::beginTransaction();
//         try {
//             // Validate trước khi tạo dữ liệu
//             $request->validate([
//     'ma_sv' => 'required|unique:sinhvien,ma_sv',
//     'ho_ten' => 'required|string|max:100',
//     'email' => 'required|email|unique:sinhvien,email',
//     'sdt' => [
//         'nullable',
//         'regex:/^\d{10}$/'
//     ],
//     'lop' => 'nullable|string|max:50',
//     'nganh' => 'nullable|string|max:100'
// ], [
//     'sdt.regex' => 'Số điện thoại phải đúng 10 chữ số và không chứa ký tự đặc biệt.',
// ]);


//             // Tạo user
//             $user = User::create([
//                 'username' => $request->ma_sv,
//                 'password_hash' => Hash::make('123456'),
//                 'role_id' => 2,
//                 'nguoi_tao_id' => 1,
//                 'mat_khau_moi' => 1,
//                 'status' => 'active'
//             ]);

//             // Tạo sinh viên
//             SinhVien::create([
//                 'ma_sv' => $request->ma_sv,
//                 'ho_ten' => $request->ho_ten,
//                 'lop' => $request->lop,
//                 'nganh' => $request->nganh,
//                 'email' => $request->email,
//                 'sdt' => $request->sdt,
//                 'user_id' => $user->user_id
//             ]);

//             DB::commit();

//             return redirect()->route('admin.sinhvien.index')->with('success', 'Thêm sinh viên thành công');

//         } catch (\Exception $e) {
//             DB::rollBack();
//             return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
//         }
//     }


public function store(Request $request)
{
    DB::beginTransaction();
    try {
        // Tạo mã SV mới
        $newMaSV = $this->taoMaSV();

        $request->validate([
            'ho_ten' => 'required|string|max:100',
            'email' => 'required|email|unique:sinhvien,email',
            'sdt' => ['nullable', 'regex:/^\d{10}$/'],
            'lop' => 'nullable|string|max:50',
            'nganh' => 'nullable|string|max:100'
       ], [
    'sdt.regex' => 'Số điện thoại không đúng định dạng.',
    'email.email' => 'Email không hợp lệ.',
]);
        // Tạo tài khoản user
   
        $user = User::create([
            'username' => $newMaSV,
            'password_hash' => Hash::make('123456'),
            'role_id' => 2,
            'nguoi_tao_id' => 1,
            'mat_khau_moi' => 1,
            'status' => 'active'
        ]);
        // Tạo sinh viên
        SinhVien::create([
            'ma_sv'   => $newMaSV,
            'ho_ten'  => $request->ho_ten,
            'lop'     => $request->lop,
            'nganh'   => $request->nganh,
            'email'   => $request->email,
            'sdt'     => $request->sdt,
            'user_id' => $user->user_id
        ]);

        DB::commit();

        return redirect()->route('admin.sinhvien.index')
            ->with('success', 'Thêm sinh viên thành công');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
            ->withInput();
    }
}


    /**
     * Cập nhật sinh viên
     */
public function update(Request $request, $id)
{
    $sv = SinhVien::findOrFail($id);

    try {
        $request->validate([
            'ho_ten' => 'required|string|max:100',
            'email'  => 'required|email|unique:sinhvien,email,' . $id . ',sv_id',
            'sdt'    => ['nullable', 'regex:/^(\+84|0)\d{9}$/'],
            'lop'    => 'nullable|string|max:50',
            'nganh'  => 'nullable|string|max:100',
        ], [
    'sdt.regex' => 'Số điện thoại không đúng định dạng.',
    'email.email' => 'Email không hợp lệ.',
]);
    } catch (\Illuminate\Validation\ValidationException $e) {

        // Gửi ID modal cần mở lại
        session()->flash('edit_id', $id);

        throw $e; // để Laravel redirect back kèm lỗi
    }

    // Cập nhật DB
    $sv->update([
        'ho_ten' => $request->ho_ten,
        'lop'    => $request->lop,
        'nganh'  => $request->nganh,
        'email'  => $request->email,
        'sdt'    => $request->sdt,
    ]);

    if ($sv->user) {
        $sv->user->update([
            'username' => $sv->ma_sv
        ]);
    }

    return redirect()->back()->with('success', 'Cập nhật sinh viên thành công!');
}

    /**
     * Xóa mềm sinh viên
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $sinhvien = SinhVien::findOrFail($id);

            $sinhvien->update(['is_delete' => 1]);

            if ($sinhvien->user) {
                $sinhvien->user->update(['is_delete' => 1, 'status' => 'inactive']);
            }

            DB::commit();
            return redirect()->route('admin.sinhvien.index')->with('success', 'Xóa sinh viên thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    /**
 * Xuất danh sách sinh viên ra Excel
 */
public function export(Request $request)
{
    $nganh = $request->query('nganh'); // query param, không phải input POST
    $fileName = $nganh 
        ? 'DanhSachSinhVien_' . str_replace(' ', '_', $nganh) . '.xlsx'
        : 'DanhSachTatCaSinhVien.xlsx';

    return Excel::download(new SinhVienExport($nganh), $fileName);
}



/**
 * Nhập danh sách sinh viên từ file Excel
 */
// public function import(Request $request)
// {
//     $request->validate([
//         'file' => 'required|mimes:xlsx,xls'
//     ]);

//     try {
//         Excel::import(new SinhVienImport, $request->file('file'));
//         return redirect()->route('admin.sinhvien.index')->with('success', 'Nhập danh sách sinh viên thành công!');
//     } catch (\Exception $e) {
//         return redirect()->back()->with('error', 'Lỗi khi nhập dữ liệu: ' . $e->getMessage());
//     }
// }

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
    ]);

    try {
        // Tạo import instance
        $import = new \App\Imports\SinhVienImport;

        // Thực hiện import
        Excel::import($import, $request->file('file'));

        // Kiểm tra lỗi từ import
        $failures = $import->failures();
        if ($failures->isNotEmpty()) {
            $messages = [];
            foreach ($failures as $failure) {
                $row = $failure->row();
                $errors = $failure->errors();

                // Dịch sang tiếng Việt
                $translated = array_map(function($e) {
                    if (str_contains($e, 'has already been taken')) {
                        return 'đã tồn tại trong hệ thống';
                    }
                    if (str_contains($e, 'required')) {
                        return 'là bắt buộc';
                    }
                    if (str_contains($e, 'integer')) {
                        return 'phải là số nguyên';
                    }
                    if (str_contains($e, 'min')) {
                        return 'phải lớn hơn hoặc bằng giá trị tối thiểu';
                    }
                    return $e;
                }, $errors);

                $messages[] = "Dòng {$row}: " . implode(', ', $translated);
            }

            return redirect()->back()->with('import_error', implode('<br>', $messages));
        }

        return redirect()->route('admin.sinhvien.index')->with('success', 'Nhập danh sách sinh viên thành công!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Lỗi khi nhập dữ liệu: ' . $e->getMessage());
    }
}

}