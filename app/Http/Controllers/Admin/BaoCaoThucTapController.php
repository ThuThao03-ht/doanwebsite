<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BaoCaoThucTap;
use App\Models\DangKyThucTap;
use Illuminate\Support\Str;

class BaoCaoThucTapController extends Controller
{
    /**
     * Hiển thị danh sách báo cáo
     */
    public function index(Request $request)
    {
        $query = BaoCaoThucTap::with(['dangKyThucTap.sinhVien'])
                    ->chuaXoa()
                    ->orderBy('ngay_nop','desc');

        // Lọc theo tên sinh viên
        if ($request->filled('ten_sv')) {
            $query->whereHas('dangKyThucTap.sinhVien', function($q) use ($request){
                $q->where('ho_ten', 'like', '%'.$request->ten_sv.'%');
            });
        }

        $baocao = $query->paginate(10);

        return view('admin.baocaothuctap', compact('baocao'));
    }

    /**
     * Xem chi tiết báo cáo (AJAX)
     */
    public function show($id)
    {
        $baocao = BaoCaoThucTap::with('dangKyThucTap.sinhVien')->findOrFail($id);
        return response()->json($baocao);
    }

    /**
     * Upload hoặc tạo mới báo cáo
     */
    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'nullable|string',
            'ngay_nop' => 'required|date',
            'file_baocao' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // max 10MB
        ]);

        $filename = null;
        if($request->hasFile('file_baocao')) {
            $file = $request->file('file_baocao');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage'), $filename); // Lưu vào D:\doan\public\storage
        }

        BaoCaoThucTap::create([
            'dk_id' => $request->dk_id,
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
            'ngay_nop' => $request->ngay_nop,
            'file_baocao' => $filename,
        ]);

        return redirect()->back()->with('success', 'Tạo báo cáo thành công!');
    }

    /**
     * Xem trực tiếp file báo cáo
     */
    public function viewFile($id)
    {
        $baocao = BaoCaoThucTap::findOrFail($id);
        $path = public_path('storage/'.$baocao->file_baocao);

        if($baocao->file_baocao && file_exists($path)) {
            return response()->file($path);
        }

        abort(404, 'File không tồn tại');
    }

    /**
     * Tải về file báo cáo
     */
public function downloadFile($id)
{
    $baocao = BaoCaoThucTap::with(['dangKyThucTap.sinhVien'])->findOrFail($id);
    $path = public_path('storage/' . $baocao->file_baocao);

    if ($baocao->file_baocao && file_exists($path)) {
        // Lấy tên sinh viên
        $tenGoc = $baocao->dangKyThucTap->sinhVien->ho_ten ?? 'sinhvien';

        // Chuyển sang không dấu và loại bỏ ký tự đặc biệt
        $tenKhongDau = Str::slug($tenGoc, '_');

        // Lấy phần mở rộng file
        $fileExtension = pathinfo($path, PATHINFO_EXTENSION);

        // Tên file khi tải xuống
        $downloadName = $tenKhongDau . '_baocao_thuctap.' . $fileExtension;

        return response()->download($path, $downloadName);
    }

    abort(404, 'File không tồn tại');
}

    /**
     * Xóa báo cáo (mềm)
     */
    public function destroy($id)
    {
        $baocao = BaoCaoThucTap::findOrFail($id);
        $baocao->is_delete = 1;
        $baocao->save();

        return redirect()->back()->with('success', 'Xóa báo cáo thành công!');
    }
}