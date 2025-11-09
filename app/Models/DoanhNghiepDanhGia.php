<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DoanhNghiepDanhGia extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'doanhnghiep_danhgia';

    // Khóa chính
    protected $primaryKey = 'dg_dn_id';

    // Các cột có thể gán hàng loạt
    protected $fillable = [
        'dk_id',
        'dn_id',
        'nguoi_danhgia_id',
        'diemso',
        'nhanxet',
        'ngay_danhgia',
        'is_delete',
    ];

    // Kiểu dữ liệu của các trường
    protected $casts = [
        'diemso' => 'decimal:2',
        'ngay_danhgia' => 'date',
        'is_delete' => 'boolean',
    ];

    /**
     * Mối quan hệ: Một đánh giá thuộc về một doanh nghiệp
     */
    public function doanhNghiep()
    {
        return $this->belongsTo(DoanhNghiep::class, 'dn_id', 'dn_id');
    }

    /**
     * Mối quan hệ: Một đánh giá thuộc về một đăng ký thực tập
     */
    public function dangKyThucTap()
    {
        return $this->belongsTo(DangKyThucTap::class, 'dk_id', 'dk_id');
    }

    /**
     * Mối quan hệ: Người đánh giá là một user (leader)
     */
    public function nguoiDanhGia()
    {
        return $this->belongsTo(User::class, 'nguoi_danhgia_id', 'user_id');
    }
    
}