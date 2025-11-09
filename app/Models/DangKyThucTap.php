<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DangKyThucTap extends Model
{
    use HasFactory;

    //  Tên bảng
    protected $table = 'dangky_thuctap';

    //  Khóa chính
    protected $primaryKey = 'dk_id';

    // Laravel sẽ tự động quản lý created_at và updated_at
    public $timestamps = true;

    //  Các cột có thể gán giá trị hàng loạt
    protected $fillable = [
        'sv_id',
        'vitri_id',
        'ngay_dangky',
        'trang_thai',
        'is_delete',
    ];

    // ===============================
    //  Quan hệ giữa các bảng
    // ===============================

    //  Một bản đăng ký thuộc về một sinh viên
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'sv_id', 'sv_id');
    }

    //  Một bản đăng ký thuộc về một vị trí thực tập
    public function viTriThucTap()
    {
        return $this->belongsTo(ViTriThucTap::class, 'vitri_id', 'vitri_id');
    }

    // ===============================
    //  Các scope lọc dữ liệu nhanh
    // ===============================

    // Lấy các đăng ký chưa xóa
    public function scopeChuaXoa($query)
    {
        return $query->where('is_delete', false);
    }

    //  Lọc theo trạng thái
    public function scopeTrangThai($query, $trangThai)
    {
        return $query->where('trang_thai', $trangThai);
    }
     public function tienDo()
    {
        return $this->hasMany(TienDo::class, 'dk_id', 'dk_id');
    }

    public function baoCao()
    {
        return $this->hasMany(BaoCaoThucTap::class, 'dk_id', 'dk_id');
    }
    // Một đăng ký có thể có một đánh giá từ giảng viên
    public function danhGiaGiangVien()
    {
        return $this->hasOne(GiangVienDanhGia::class, 'dk_id', 'dk_id');
    }
        public function danhGiaDoanhNghiep()
    {
        return $this->hasOne(DoanhNghiepDanhGia::class, 'dk_id', 'dk_id');
    }
// Một đăng ký có thể có nhiều phân công giảng viên
   public function phanCongGiangViens()
{
    return $this->hasMany(PhanCongGiangVien::class, 'dk_id', 'dk_id')
                ->where('is_delete', 0) // chỉ lấy phân công còn hiệu lực
                ->with('giangVien');
}

public function viTri() {
    return $this->belongsTo(ViTriThucTap::class, 'vitri_id', 'vitri_id');
}



}