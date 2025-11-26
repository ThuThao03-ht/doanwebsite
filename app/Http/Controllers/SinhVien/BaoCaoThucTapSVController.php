<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BaoCaoThucTap;
use App\Models\DangKyThucTap;
use App\Models\GiangVienDanhGia;
use App\Models\DoanhNghiepDanhGia;

class BaoCaoThucTapSVController extends Controller
{
    // ====== Kiểm tra điều kiện thêm/sửa/xóa báo cáo ======
    private function canModifyBaoCao($dangKy)
    {
        if (!$dangKy) return false;

        // Chỉ cho phép khi trạng thái là đã duyệt hoặc đang thực tập
        if (!in_array($dangKy->trang_thai, ['da_duyet', 'dang_thuctap'])) {
            return false;
        }

        // Kiểm tra đánh giá giảng viên
        $gvDanhGia = GiangVienDanhGia::where('dk_id', $dangKy->dk_id)
            ->where('is_delete', 0)
            ->exists();

        // Kiểm tra đánh giá doanh nghiệp
        $dnDanhGia = DoanhNghiepDanhGia::where('dk_id', $dangKy->dk_id)
            ->where('is_delete', 0)
            ->exists();

        if ($gvDanhGia || $dnDanhGia) return false;

        return true;
    }

    // ====== Trang danh sách báo cáo ======
    public function index()
    {
        $user = Auth::user();
        $sv = $user->sinhvien;

        $dangKy = DangKyThucTap::where('sv_id', $sv->sv_id)
            ->where('trang_thai', '!=', 'tu_choi')
            ->orderBy('dk_id', 'desc')
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
        $sv = Auth::user()->sinhvien;
        $dangKy = DangKyThucTap::where('sv_id', $sv->sv_id)
            ->orderBy('dk_id', 'desc')
            ->first();

        if (!$dangKy) {
            return response()->json(['status' => 'error', 'message' => 'Bạn chưa đăng ký thực tập.']);
        }

        if (!$this->canModifyBaoCao($dangKy)) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không thể nộp báo cáo do trạng thái đăng ký hoặc đã có đánh giá.']);
        }

        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'file_baocao' => 'required|mimes:pdf,doc,docx|max:5120'
        ]);

        $daCo = BaoCaoThucTap::where('dk_id', $dangKy->dk_id)
            ->where('is_delete', 0)
            ->first();

        if ($daCo) {
            return response()->json(['status' => 'error', 'message' => 'Bạn đã nộp báo cáo rồi. Hãy xóa báo cáo cũ trước.']);
        }

        $uploadPath = public_path('storage');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file = $request->file('file_baocao');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($uploadPath, $fileName);

        BaoCaoThucTap::create([
            'dk_id' => $dangKy->dk_id,
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
            'file_baocao' => $fileName,
            'ngay_nop' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Nộp báo cáo thành công!']);
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
            return response()->json(['status' => 'error', 'message' => 'File báo cáo không tồn tại.']);
        }

        return response()->file($path);
    }

    // ====== Tải file ======
    public function taiFile($id)
    {
        $baoCao = BaoCaoThucTap::findOrFail($id);
        $path = public_path('storage/' . $baoCao->file_baocao);

        if (!file_exists($path)) {
            return response()->json(['status' => 'error', 'message' => 'File báo cáo không tồn tại.']);
        }

        return response()->download($path);
    }

    // ====== Cập nhật báo cáo ======
    public function update(Request $request, $id)
    {
        $baoCao = BaoCaoThucTap::findOrFail($id);
        $dangKy = $baoCao->dangKyThucTap;

        if (!$this->canModifyBaoCao($dangKy)) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không thể sửa báo cáo do trạng thái đăng ký hoặc đã có đánh giá.']);
        }

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

        return response()->json(['status' => 'success', 'message' => 'Cập nhật báo cáo thành công!']);
    }

    // ====== Xóa báo cáo ======
    public function destroy($id)
    {
        $baoCao = BaoCaoThucTap::findOrFail($id);
        $dangKy = $baoCao->dangKyThucTap;

        if (!$this->canModifyBaoCao($dangKy)) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không thể xóa báo cáo do trạng thái đăng ký hoặc đã có đánh giá.']);
        }

        $filePath = public_path('storage/' . $baoCao->file_baocao);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $baoCao->is_delete = 1;
        $baoCao->save();

        return response()->json(['status' => 'success', 'message' => 'Xóa báo cáo thành công!']);
    }
}