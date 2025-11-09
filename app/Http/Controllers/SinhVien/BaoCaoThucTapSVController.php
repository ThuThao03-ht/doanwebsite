<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BaoCaoThucTap;
use App\Models\DangKyThucTap;

class BaoCaoThucTapSVController extends Controller
{
    // ====== Trang danh sách báo cáo ======
    public function index()
    {
        $user = Auth::user();
        $sv = $user->sinhvien;

        $dangKy = DangKyThucTap::where('sv_id', $sv->sv_id)
            ->where('trang_thai', '!=', 'tu_choi')
            ->first();

        if (!$dangKy) {
            return view('sinhvien.baocaothuctapsv', [
                'baoCao' => null,
                'dangKy' => null
            ]);
        }

        $baoCao = BaoCaoThucTap::where('dk_id', $dangKy->dk_id)
            ->where('is_delete', 0)
            ->first();

        return view('sinhvien.baocaothuctapsv', compact('baoCao', 'dangKy'));
    }

    // ====== Nộp báo cáo ======
    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'file_baocao' => 'required|mimes:pdf,doc,docx|max:5120'
        ]);

        $sv = Auth::user()->sinhvien;
        $dangKy = DangKyThucTap::where('sv_id', $sv->sv_id)->first();

        if (!$dangKy) {
            return redirect()->back()->with('error', 'Bạn chưa đăng ký thực tập.');
        }

        $daCo = BaoCaoThucTap::where('dk_id', $dangKy->dk_id)
            ->where('is_delete', 0)
            ->first();

        if ($daCo) {
            return redirect()->back()->with('error', 'Bạn đã nộp báo cáo rồi. Hãy xóa báo cáo cũ trước.');
        }

        // Đường dẫn lưu file
        $uploadPath = public_path('storage');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Lưu file vào D:\doan\public\storage
        $file = $request->file('file_baocao');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($uploadPath, $fileName);

        // Lưu vào DB
        BaoCaoThucTap::create([
            'dk_id' => $dangKy->dk_id,
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
            'file_baocao' => $fileName,
            'ngay_nop' => now(),
        ]);

        return redirect()->back()->with('success', 'Nộp báo cáo thành công!');
    }

    // ====== Xem chi tiết báo cáo ======
    public function xemChiTiet($id)
    {
        $baoCao = BaoCaoThucTap::with([
            'dangKyThucTap.sinhVien',
            'dangKyThucTap.viTriThucTap.doanhNghiep',
            'dangKyThucTap.phanCongGiangViens.giangVien'
        ])->findOrFail($id);

        return response()->json($baoCao);
    }

    // ====== Xem file trực tiếp ======
    public function xemFile($id)
    {
        $baoCao = BaoCaoThucTap::findOrFail($id);
        $path = public_path('storage/' . $baoCao->file_baocao);

        if (!file_exists($path)) {
            abort(404, 'File báo cáo không tồn tại.');
        }

        return response()->file($path);
    }

    // ====== Tải file ======
    public function taiFile($id)
    {
        $baoCao = BaoCaoThucTap::findOrFail($id);
        $path = public_path('storage/' . $baoCao->file_baocao);

        if (!file_exists($path)) {
            abort(404, 'File báo cáo không tồn tại.');
        }

        return response()->download($path);
    }

    // ====== Cập nhật báo cáo ======
    public function update(Request $request, $id)
    {
        $baoCao = BaoCaoThucTap::findOrFail($id);

        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'file_baocao' => 'nullable|mimes:pdf,doc,docx|max:5120'
        ]);

        $baoCao->tieu_de = $request->tieu_de;
        $baoCao->noi_dung = $request->noi_dung;

        $uploadPath = public_path('storage');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if ($request->hasFile('file_baocao')) {
            $oldPath = $uploadPath . '/' . $baoCao->file_baocao;
            if ($baoCao->file_baocao && file_exists($oldPath)) {
                unlink($oldPath);
            }

            $file = $request->file('file_baocao');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $fileName);
            $baoCao->file_baocao = $fileName;
        }

        $baoCao->save();

        return redirect()->back()->with('success', 'Cập nhật báo cáo thành công!');
    }

    // ====== Xóa báo cáo ======
    public function destroy($id)
    {
        $baoCao = BaoCaoThucTap::findOrFail($id);
        $filePath = public_path('storage/' . $baoCao->file_baocao);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $baoCao->is_delete = 1;
        $baoCao->save();

        return redirect()->back()->with('success', 'Xóa báo cáo thành công!');
    }
}