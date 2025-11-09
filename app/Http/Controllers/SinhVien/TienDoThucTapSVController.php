<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\TienDo;
use App\Models\DangKyThucTap;
use Carbon\Carbon;

class TienDoThucTapSVController extends Controller
{
    //  Trang danh sách tiến độ
public function index()
{
    $user = Auth::user();
    $sinhVien = $user->sinhvien;

    if (!$sinhVien) {
        return back()->with('error', 'Không tìm thấy thông tin sinh viên!');
    }

    $dangKy = DangKyThucTap::where('sv_id', $sinhVien->sv_id)
        ->where('is_delete', 0)
        ->orderByDesc('created_at')
        ->first();

    // Nếu chưa đăng ký thì không báo lỗi, chỉ truyền null để hiển thị thông báo thân thiện
    $tienDos = [];

    if ($dangKy) {
        $tienDos = \App\Models\TienDo::where('dk_id', $dangKy->dk_id)
            ->where('is_delete', 0)
            ->orderByDesc('ngay_capnhat')
            ->get();
    }

    return view('sinhvien.tiendothuctapsv', compact('tienDos', 'dangKy'));
}

    // Xem chi tiết tiến độ (cho modal)
    public function xemChiTiet($id)
    {
        $tienDo = TienDo::with('dangKyThucTap.viTriThucTap.doanhNghiep')->findOrFail($id);
        return response()->json(['tienDo' => $tienDo]);
    }

    //  Xem file PDF trực tiếp
    public function xemFile($id)
    {
        $tienDo = TienDo::findOrFail($id);

        if (!$tienDo->file_dinhkem || !file_exists(public_path('storage/' . $tienDo->file_dinhkem))) {
            return response()->json(['error' => 'File không tồn tại.'], 404);
        }

        $filePath = public_path('storage/' . $tienDo->file_dinhkem);
        return response()->file($filePath);
    }

    //  Tải file PDF
    public function taiFile($id)
    {
        $tienDo = TienDo::findOrFail($id);
        $filePath = public_path('storage/' . $tienDo->file_dinhkem);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File không tồn tại!');
        }

        return Response::download($filePath);
    }

    // Sinh viên thêm tiến độ
    public function store(Request $request)
    {
        $request->validate([
            'noi_dung' => 'required|string',
            'file_dinhkem' => 'nullable|mimes:pdf|max:20480', // 20MB
        ]);

        $user = Auth::user();
        $sinhVien = $user->sinhvien;
        $dangKy = DangKyThucTap::where('sv_id', $sinhVien->sv_id)->first();

        $filePath = null;
        if ($request->hasFile('file_dinhkem')) {
            $fileName = 'baocao_kttt_' . time() . '.pdf';
            $request->file('file_dinhkem')->move(public_path('storage'), $fileName);
            $filePath = $fileName;
        }

        TienDo::create([
            'dk_id' => $dangKy->dk_id,
            'noi_dung' => $request->noi_dung,
            'ngay_capnhat' => Carbon::now(),
            'file_dinhkem' => $filePath,
        ]);

        return redirect()->route('sinhvien.tiendo.index')
            ->with('success', 'Thêm tiến độ thành công!');
    }

    // Sửa tiến độ
    public function update(Request $request, $id)
    {
        $tienDo = TienDo::findOrFail($id);

        $request->validate([
            'noi_dung' => 'required|string',
            'file_dinhkem' => 'nullable|mimes:pdf|max:20480',
        ]);

        $filePath = $tienDo->file_dinhkem;
        if ($request->hasFile('file_dinhkem')) {
            if ($filePath && file_exists(public_path('storage/' . $filePath))) {
                unlink(public_path('storage/' . $filePath));
            }
            $fileName = 'baocao_kttt_' . time() . '.pdf';
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


    public function destroy($id)
{
    $tienDo = TienDo::findOrFail($id);

    // Kiểm tra quyền: sinh viên chỉ được xóa tiến độ của chính mình
    $user = Auth::user();
    $sinhVien = $user->sinhvien;
    $dangKy = DangKyThucTap::where('sv_id', $sinhVien->sv_id)->first();

    if (!$dangKy || $tienDo->dk_id != $dangKy->dk_id) {
        return response()->json(['error' => 'Bạn không có quyền xóa tiến độ này.'], 403);
    }

    // Xóa file nếu có
    if ($tienDo->file_dinhkem && file_exists(public_path('storage/' . $tienDo->file_dinhkem))) {
        unlink(public_path('storage/' . $tienDo->file_dinhkem));
    }

    // Xóa mềm (đặt is_delete = 1)
    $tienDo->update(['is_delete' => 1]);

    return response()->json(['success' => 'Xóa tiến độ thành công!']);
}

}