<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\DoanhNghiep;
use App\Models\ViTriThucTap;
use App\Models\DangKyThucTap;

class KhoiPhucController extends Controller
{
    // ================== SINH VIÊN ==================
    public function sinhvien()
    {
        $sinhvien = SinhVien::where('is_delete', 1)->get();
        return view('admin.khoiphuc.sinhvien', compact('sinhvien'));
    }

    public function khoiPhucSinhVien($id)
    {
        SinhVien::where('sv_id', $id)->update(['is_delete' => 0]);
        return redirect()->back()->with('success', 'Khôi phục sinh viên thành công!');
    }

    // ================== GIẢNG VIÊN ==================
    public function giangvien()
    {
        $giangvien = GiangVien::where('is_delete', 1)->get();
        return view('admin.khoiphuc.giangvien', compact('giangvien'));
    }

    public function khoiPhucGiangVien($id)
    {
        GiangVien::where('gv_id', $id)->update(['is_delete' => 0]);
        return redirect()->back()->with('success', 'Khôi phục giảng viên thành công!');
    }

    // ================== DOANH NGHIỆP ==================
    public function doanhnghiep()
    {
        $doanhnghiep = DoanhNghiep::where('is_delete', 1)->get();
        return view('admin.khoiphuc.doanhnghiep', compact('doanhnghiep'));
    }

    public function khoiPhucDoanhNghiep($id)
    {
        DoanhNghiep::where('dn_id', $id)->update(['is_delete' => 0]);
        return redirect()->back()->with('success', 'Khôi phục doanh nghiệp thành công!');
    }

    // ================== VỊ TRÍ THỰC TẬP ==================
    public function vitri()
    {
        $vitri = ViTriThucTap::where('is_delete', 1)->with('doanhnghiep')->get();
        return view('admin.khoiphuc.vitri', compact('vitri'));
    }

    public function khoiPhucViTri($id)
    {
        ViTriThucTap::where('vitri_id', $id)->update(['is_delete' => 0]);
        return redirect()->back()->with('success', 'Khôi phục vị trí thực tập thành công!');
    }

    // ================== ĐĂNG KÝ THỰC TẬP ==================
    public function dangky()
    {
        $dangky = DangKyThucTap::where('is_delete', 1)
            ->with(['sinhvien', 'vitriThucTap'])
            ->get();
        return view('admin.khoiphuc.dangky', compact('dangky'));
    }

    public function khoiPhucDangKy($id)
    {
        DangKyThucTap::where('dk_id', $id)->update(['is_delete' => 0]);
        return redirect()->back()->with('success', 'Khôi phục đăng ký thực tập thành công!');
    }
}