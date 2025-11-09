<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class GiangVienDanhGia extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'giangvien_danhgia';

    // Khóa chính
    protected $primaryKey = 'dg_id';

    // Các cột có thể gán hàng loạt (mass assignable)
    protected $fillable = [
        'dk_id',
        'gv_id',
        'diemso',
        'nhanxet',
        'ngay_danhgia',
        'is_delete',
    ];

    // Kiểu dữ liệu cho các trường
    protected $casts = [
        'diemso' => 'decimal:2',
        'ngay_danhgia' => 'date',
        'is_delete' => 'boolean',
    ];

    // Mối quan hệ: Một đánh giá thuộc về một giảng viên
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'gv_id', 'gv_id');
    }

    // Mối quan hệ: Một đánh giá thuộc về một đăng ký thực tập
    public function dangKyThucTap()
    {
        return $this->belongsTo(DangKyThucTap::class, 'dk_id', 'dk_id');
    }
    
}