<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VitriThuctap;
use App\Models\DoanhNghiep;
use App\Exports\VitriThuctapExport;
use App\Imports\VitriThuctapImport;
use Maatwebsite\Excel\Facades\Excel;


class VitriThuctapController extends Controller
{
  public function index(Request $request)
{
    $last = VitriThuctap::orderBy('vitri_id','desc')->first();
    $num = $last ? (int) substr($last->ma_vitri, 2) + 1 : 1;
    // $ma_vitri = 'VT'.str_pad($num, 4, '0', STR_PAD_LEFT);
    $ma_vitri = $this->taoMaViTri();

    $query = VitriThuctap::where('is_delete', 0);

    // Tìm kiếm theo tên vị trí
    if ($request->has('search') && $request->search != '') {
        $query->where('ten_vitri', 'like', '%'.$request->search.'%');
    }

    // Lọc theo doanh nghiệp
    if ($request->has('dn_id') && $request->dn_id != '') {
        $query->where('dn_id', $request->dn_id);
    }

    // Phân trang mỗi trang 10 dòng
    $vitrithuctaps = $query->paginate(10)->appends($request->all());

    $doanhnghieps = DoanhNghiep::where('is_delete', 0)->get();

    return view('admin.vitrithuctap', compact('vitrithuctaps', 'doanhnghieps', 'ma_vitri'));
}



    // Thêm mới
  // Thêm mới
// public function store(Request $request)
// {
//     // Sinh ma_vitri tự động
//     $last = VitriThuctap::orderBy('vitri_id','desc')->first();
//     $num = $last ? (int) substr($last->ma_vitri, 2) + 1 : 1;
//     $ma_vitri = 'VT'.str_pad($num, 4, '0', STR_PAD_LEFT);

//     VitriThuctap::create([
//         'dn_id' => $request->dn_id,
//         'ma_vitri' => $ma_vitri,
//         'ten_vitri' => $request->ten_vitri,
//         'mo_ta' => $request->mo_ta,
//         'yeu_cau' => $request->yeu_cau,
//         'soluong' => $request->soluong ?? 1,
//         'so_luong_da_dangky' => $request->so_luong_da_dangky ?? 0,
//         'trang_thai' => $request->trang_thai ?? 'con_han'
//     ]);

//     return redirect()->route('admin.vitrithuctap.index')->with('success','Thêm vị trí thực tập thành công!');
// }

public function store(Request $request)
{
    $ma_vitri = $this->taoMaViTri();

    VitriThuctap::create([
        'dn_id' => $request->dn_id,
        'ma_vitri' => $ma_vitri,
        'ten_vitri' => $request->ten_vitri,
        'mo_ta' => $request->mo_ta,
        'yeu_cau' => $request->yeu_cau,
        'soluong' => $request->soluong ?? 1,
        'so_luong_da_dangky' => 0,
        'trang_thai' => $request->trang_thai ?? 'con_han'
    ]);

    return redirect()->route('admin.vitrithuctap.index')
        ->with('success','Thêm vị trí thực tập thành công!');
}


// Sửa
public function update(Request $request, $id)
{
    $vitri = VitriThuctap::findOrFail($id);
    $vitri->update([
        'dn_id' => $request->dn_id,
        'ten_vitri' => $request->ten_vitri,
        'mo_ta' => $request->mo_ta,
        'yeu_cau' => $request->yeu_cau,
        'soluong' => $request->soluong ?? 1,
        'so_luong_da_dangky' => $request->so_luong_da_dangky ?? $vitri->so_luong_da_dangky,
        'trang_thai' => $request->trang_thai ?? 'con_han'
    ]);

    return redirect()->route('admin.vitrithuctap.index')->with('success','Cập nhật vị trí thực tập thành công!');
}

    // Xóa
    public function destroy($id)
    {
        $vitri = VitriThuctap::findOrFail($id);
        $vitri->is_delete = 1;
        $vitri->save();

        return redirect()->route('admin.vitrithuctap.index')->with('success','Xóa vị trí thực tập thành công!');
    }

    // Xem chi tiết (AJAX)
    public function show($id)
    {
        $vitri = VitriThuctap::with('doanhnghiep')->findOrFail($id);
        return response()->json($vitri);
    }
    // Xuất Excel
  

    // Import Excel
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
    ]);

    $import = new VitriThuctapImport;

    Excel::import($import, $request->file('file'));

    $failures = $import->failures();
    if ($failures->isNotEmpty()) {
        $messages = [];
        foreach ($failures as $failure) {
            $row = $failure->row();
            $errors = $failure->errors();
            
            // Dịch tiếng Việt
            $translated = array_map(function($e) {
                if(str_contains($e, 'has already been taken')) {
                    return 'đã tồn tại trong hệ thống';
                }
                if(str_contains($e, 'required')) {
                    return 'là bắt buộc';
                }
                if(str_contains($e, 'integer')) {
                    return 'phải là số nguyên';
                }
                if(str_contains($e, 'min')) {
                    return 'phải lớn hơn hoặc bằng giá trị tối thiểu';
                }
                return $e; // giữ nguyên nếu không dịch được
            }, $errors);

            $messages[] = "Dòng {$row}: ".implode(', ', $translated);
        }

        return redirect()->back()->with('import_error', implode('<br>', $messages));
    }

    return redirect()->route('admin.vitrithuctap.index')->with('success', 'Import dữ liệu thành công!');
}




public function export(Request $request)
{
    $dn_id = $request->query('dn_id'); // query param từ URL, ví dụ ?dn_id=3

    // Lấy tên doanh nghiệp để đặt tên file (nếu có)
    $dn_name = null;
    if ($dn_id) {
        $dn = DoanhNghiep::find($dn_id);
        $dn_name = $dn ? $dn->ten_dn : null;
    }

    $fileName = $dn_name
        ? 'DanhSachVitriThucTap_' . str_replace(' ', '_', $dn_name) . '.xlsx'
        : 'DanhSachTatCaVitriThucTap.xlsx';

    return Excel::download(new VitriThuctapExport($dn_id), $fileName);
}


private function taoMaViTri()
{
    $last = VitriThuctap::orderBy('ma_vitri', 'desc')->first();

    if (!$last) {
        return 'VT0001';
    }

    $number = intval(substr($last->ma_vitri, 2)) + 1;

    return 'VT' . str_pad($number, 4, '0', STR_PAD_LEFT);
}



}