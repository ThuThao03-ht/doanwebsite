<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DangKyThucTap;
use App\Models\SinhVien;
use App\Models\ViTriThucTap;

class DangKyThucTapController extends Controller
{
    // Hiển thị danh sách đăng ký thực tập
 public function index(Request $request)
{
    $query = DangKyThucTap::with(['sinhVien', 'viTriThucTap'])
        ->where('is_delete', 0);

    // Lọc theo trạng thái nếu có
    if ($request->filled('trang_thai')) {
        $query->where('trang_thai', $request->trang_thai);
    }

    $dangky = $query->orderBy('created_at', 'desc')->get();

    return view('admin.dangkythuctap', compact('dangky'));
}


    // Xem chi tiết đăng ký (AJAX hoặc modal)
    public function show($id)
    {
        $dk = DangKyThucTap::with(['sinhVien', 'viTriThucTap'])->findOrFail($id);
        return response()->json($dk);
    }
public function duyet(Request $request, $id)
{
    $request->validate([
        'trang_thai' => 'required|in:da_duyet,tu_choi'
    ]);

    $dk = DangKyThucTap::findOrFail($id);

    $oldStatus = $dk->trang_thai;
    $newStatus = $request->trang_thai;

    // Nếu tu_choi hoặc hoan_thanh → không được đổi
    if (in_array($oldStatus, ['tu_choi', 'hoan_thanh'])) {
        return back()->with('error', 'Trạng thái hiện tại không được phép cập nhật.');
    }

    // Nếu chuyển sang "từ chối" → cho phép luôn
    if ($newStatus === 'tu_choi') {
        $dk->trang_thai = 'tu_choi';
        $dk->save();
        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    // Kiểm tra theo quy tắc chuyển trạng thái
    if (!$this->isValidTransition($oldStatus, $newStatus)) {
        return back()->with('error', 'Không thể chuyển trạng thái không hợp lệ.');
    }

    // Cập nhật
    $dk->trang_thai = $newStatus;
    $dk->save();

    // Tạo phân công GV nếu từ cho_duyet → da_duyet
    if ($oldStatus === 'cho_duyet' && $newStatus === 'da_duyet') {
        $exists = \App\Models\PhanCongGiangVien::where('dk_id', $dk->dk_id)
            ->where('is_delete', 0)
            ->exists();

        if (!$exists) {
            \App\Models\PhanCongGiangVien::create([
                'dk_id' => $dk->dk_id,
                'gv_id' => null,
                'ngay_phancong' => null,
                'ghi_chu' => null,
                'is_delete' => 0,
            ]);
        }
    }

    return back()->with('success', 'Cập nhật trạng thái thành công!');
}

public function capNhatTrangThai(Request $request, $id)
{
    $request->validate([
        'trang_thai' => 'required|in:dang_thuctap,hoan_thanh'
    ]);

    $dk = DangKyThucTap::findOrFail($id);

    $oldStatus = $dk->trang_thai;
    $newStatus = $request->trang_thai;

    // tu_choi & hoan_thanh → không được đổi
    if (in_array($oldStatus, ['tu_choi', 'hoan_thanh'])) {
        return back()->with('error', 'Trạng thái hiện tại không được phép cập nhật.');
    }

    // Kiểm tra hợp lệ theo quy tắc
    if (!$this->isValidTransition($oldStatus, $newStatus)) {
        return back()->with('error', 'Không thể chuyển trạng thái không hợp lệ.');
    }

    // Cập nhật
    $dk->trang_thai = $newStatus;
    $dk->save();

    return back()->with('success', 'Cập nhật trạng thái thành công!');
}

    
// Hủy đăng ký thực tập (Admin hoặc sinh viên yêu cầu)
public function huyDangKy($id)
{
    $dk = DangKyThucTap::findOrFail($id);

    // Lấy vị trí thực tập tương ứng
    $vitri = ViTriThucTap::find($dk->vitri_id);

    if ($vitri) {
        // Giảm số lượng đã đăng ký
        $vitri->so_luong_da_dangky = max(0, $vitri->so_luong_da_dangky - 1);

        // Nếu trước đó hết hạn nhưng giờ còn chỗ, đổi lại con_han
        if ($vitri->trang_thai === 'het_han' && $vitri->so_luong_da_dangky < $vitri->soluong) {
            $vitri->trang_thai = 'con_han';
        }

        $vitri->save();
    }

    // Cập nhật trạng thái đăng ký và xóa mềm
    $dk->trang_thai = 'tu_choi';
    $dk->is_delete = 1;
    $dk->save();

    return redirect()->back()->with('success', 'Đã hủy đăng ký thực tập thành công!');
}

public function countDangKy()
{
    $data = [
        'cho_duyet'     => DangKyThucTap::where('is_delete', 0)->where('trang_thai', 'cho_duyet')->count(),
        'da_duyet'      => DangKyThucTap::where('is_delete', 0)->where('trang_thai', 'da_duyet')->count(),
        'dang_thuctap'  => DangKyThucTap::where('is_delete', 0)->where('trang_thai', 'dang_thuctap')->count(),
        'tong'          => DangKyThucTap::where('is_delete', 0)
                            ->whereIn('trang_thai', ['cho_duyet','da_duyet','dang_thuctap'])
                            ->count(),
    ];

    return response()->json($data);
}

 public function duyetBulk(Request $request)
{
    if (!$request->filled('ids')) {
        return redirect()->back()->with('error', 'Không có bản ghi nào được chọn!');
    }

    $ids = explode(',', $request->ids);
    $newStatus = $request->trang_thai;

    foreach ($ids as $id) {

        $dk = DangKyThucTap::find($id);
        if (!$dk) continue;

        $oldStatus = $dk->trang_thai;

        /**
         * ===============================
         *  QUY TẮC CHUYỂN TRẠNG THÁI
         * ===============================
         * cho_duyet     → da_duyet        (hợp lệ)
         * da_duyet      → dang_thuctap    (hợp lệ)
         * dang_thuctap  → hoan_thanh      (hợp lệ)
         * tu_choi       → không được đổi
         * hoan_thanh    → không được đổi
         */

        $validTransitions = [
            'cho_duyet'      => ['da_duyet'],
            'da_duyet'       => ['dang_thuctap'],
            'dang_thuctap'   => ['hoan_thanh'],
        ];

        // Nếu trạng thái mới không hợp lệ → bỏ qua
        if (!isset($validTransitions[$oldStatus]) || 
            !in_array($newStatus, $validTransitions[$oldStatus])) 
        {
            continue;
        }

        // Cập nhật trạng thái
        $dk->trang_thai = $newStatus;
        $dk->save();

        /**
         * ==========================================
         * TẠO PHÂN CÔNG GIẢNG VIÊN
         * → Chỉ khi từ "chờ duyệt" → "đã duyệt"
         * ==========================================
         */
        if ($oldStatus === 'cho_duyet' && $newStatus === 'da_duyet') {

            $exists = \App\Models\PhanCongGiangVien::where('dk_id', $dk->dk_id)
                ->where('is_delete', 0)
                ->exists();

            if (!$exists) {
                \App\Models\PhanCongGiangVien::create([
                    'dk_id' => $dk->dk_id,
                    'gv_id' => null,
                    'ngay_phancong' => null,
                    'ghi_chu' => null,
                    'is_delete' => 0,
                ]);
            }
        }
    }

    return redirect()->back()->with(
        'success', 
        'Đã cập nhật trạng thái hàng loạt thành công!'
    );
}
private function isValidTransition($old, $new)
{
    $validTransitions = [
        'cho_duyet'     => ['da_duyet'],
        'da_duyet'      => ['dang_thuctap'],
        'dang_thuctap'  => ['hoan_thanh'],
    ];

    return isset($validTransitions[$old]) &&
           in_array($new, $validTransitions[$old]);
}

}