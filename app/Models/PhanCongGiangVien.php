<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanCongGiangVien extends Model
{
    use HasFactory;

    protected $table = 'phancong_giangvien';
    protected $primaryKey = 'pc_id';
    public $timestamps = true;

    protected $fillable = [
        'dk_id',
        'gv_id',
        'ngay_phancong',
        'ghi_chu',
        'is_delete',
    ];

    protected $casts = [
        'is_delete' => 'boolean',
        'ngay_phancong' => 'date',
    ];

    /**
     * Quan hệ: Phân công thuộc về một giảng viên
     */
public function giangVien()
{
    return $this->belongsTo(GiangVien::class, 'gv_id', 'gv_id')
                ->where('is_delete', 0);
}


    /**
     * Quan hệ: Phân công thuộc về một đăng ký thực tập
     */
    public function dangKyThucTap()
    {
       return $this->belongsTo(DangKyThucTap::class, 'dk_id', 'dk_id')
        ->with(['sinhVien', 'viTriThucTap.doanhNghiep']);
    }
}