<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\TienDo;
use App\Models\DangKyThucTap;
use App\Models\GiangVienDanhGia;
use App\Models\DoanhNghiepDanhGia;
use Carbon\Carbon;

class TienDoThucTapSVController extends Controller
{
    /**
     * Lấy đăng ký thực tập mới nhất của sinh viên
     */
    private function getDangKyMoiNhat()
    {
        $sv = Auth::user()->sinhvien;

        return DangKyThucTap::where('sv_id', $sv->sv_id)
            ->where('is_delete', 0)
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Kiểm tra xem có được phép thêm/sửa/xóa tiến độ không
     */
    private function canModifyTienDo($dangKy)
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

    /**
     * Trang danh sách tiến độ
     */
    public function index()
    {
        $dangKy = $this->getDangKyMoiNhat();
        $tienDos = [];

        if ($dangKy) {
            $tienDos = TienDo::where('dk_id', $dangKy->dk_id)
                ->where('is_delete', 0)
                ->orderByDesc('ngay_capnhat')
                ->get();
        }

        return view('sinhvien.tiendothuctapsv', compact('tienDos', 'dangKy'));
    }

    /**
     * Xem chi tiết tiến độ (cho modal)
     */
    public function xemChiTiet($id)
    {
        $tienDo = TienDo::with('dangKyThucTap.viTriThucTap.doanhNghiep')->findOrFail($id);
        return response()->json(['tienDo' => $tienDo]);
    }

    /**
     * Xem file PDF trực tiếp
     */
    public function xemFile($id)
    {
        $tienDo = TienDo::findOrFail($id);

        if (!$tienDo->file_dinhkem || !file_exists(public_path('storage/' . $tienDo->file_dinhkem))) {
            return response()->json(['error' => 'File không tồn tại.'], 404);
        }

        return response()->file(public_path('storage/' . $tienDo->file_dinhkem));
    }

    /**
     * Tải file PDF
     */
    public function taiFile($id)
    {
        $tienDo = TienDo::findOrFail($id);
        $path = public_path('storage/' . $tienDo->file_dinhkem);

        if (!file_exists($path)) {
            return back()->with('error', 'File không tồn tại!');
        }

        return Response::download($path);
    }

    /**
     * Thêm tiến độ
     */
    public function store(Request $request)
    {
        $dangKy = $this->getDangKyMoiNhat();

        if (!$this->canModifyTienDo($dangKy)) {
            return back()->with('error', 'Bạn không thể thêm tiến độ do trạng thái đăng ký hoặc đã có đánh giá.');
        }

        $request->validate([
            'noi_dung' => 'required|string',
            'file_dinhkem' => 'nullable|mimes:pdf|max:20480', // 20MB
        ]);

        $filePath = null;

        // Upload file
        if ($request->hasFile('file_dinhkem')) {
            $fileName = 'tiendo_' . time() . '.pdf';
            $request->file('file_dinhkem')->move(public_path('storage'), $fileName);
            $filePath = $fileName;
        }

        // Lưu dữ liệu
        TienDo::create([
            'dk_id' => $dangKy->dk_id,
            'noi_dung' => $request->noi_dung,
            'ngay_capnhat' => Carbon::now(),
            'file_dinhkem' => $filePath,
        ]);

        return redirect()->route('sinhvien.tiendo.index')
            ->with('success', 'Thêm tiến độ thành công!');
    }

    /**
     * Cập nhật tiến độ
     */
    public function update(Request $request, $id)
    {
        $tienDo = TienDo::findOrFail($id);
        $dangKy = $this->getDangKyMoiNhat();

        if (!$this->canModifyTienDo($dangKy) || $tienDo->dk_id != $dangKy->dk_id) {
            return back()->with('error', 'Bạn không thể sửa tiến độ do trạng thái đăng ký hoặc đã có đánh giá.');
        }

        $request->validate([
            'noi_dung' => 'required|string',
            'file_dinhkem' => 'nullable|mimes:pdf|max:20480',
        ]);

        $filePath = $tienDo->file_dinhkem;

        // Nếu upload file mới → xóa file cũ
        if ($request->hasFile('file_dinhkem')) {
            if ($filePath && file_exists(public_path('storage/' . $filePath))) {
                unlink(public_path('storage/' . $filePath));
            }

            $fileName = 'tiendo_' . time() . '.pdf';
            $request->file('file_dinhkem')->move(public_path('storage'), $fileName);
            $filePath = $fileName;
        }

        $tienDo->update([
            'noi_dung' => $request->noi_dung,
            'ngay_capnhat' => Carbon::now(),
            'file_dinhkem' => $filePath,
        ]);

        return redirect()->route('sinhvien.tiendo.index')
            ->with('success', 'Cập nhật tiến độ thành công!');
    }

    /**
     * Xóa tiến độ
     */
    public function destroy($id)
    {
        $tienDo = TienDo::findOrFail($id);
        $dangKy = $this->getDangKyMoiNhat();

        if (!$this->canModifyTienDo($dangKy) || $tienDo->dk_id != $dangKy->dk_id) {
            return response()->json(['error' => 'Bạn không có quyền xóa tiến độ này.'], 403);
        }

        // Xóa file nếu có
        if ($tienDo->file_dinhkem && file_exists(public_path('storage/' . $tienDo->file_dinhkem))) {
            unlink(public_path('storage/' . $tienDo->file_dinhkem));
        }

        // Xóa mềm
        $tienDo->update(['is_delete' => 1]);

        return response()->json(['success' => 'Xóa tiến độ thành công!']);
    }
}