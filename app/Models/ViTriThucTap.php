<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViTriThucTap extends Model
{
    use HasFactory;

    // Tên bảng trong CSDL
    protected $table = 'vitri_thuctap';

    //  Khóa chính
    protected $primaryKey = 'vitri_id';

    //  Các cột có thể ghi (fillable)
    protected $fillable = [
        'ma_vitri',
        'dn_id',
        'ten_vitri',
        'mo_ta',
        'yeu_cau',
        'soluong',
        'so_luong_da_dangky',
        'trang_thai',
        'is_delete',
    ];

    //  Quan hệ với bảng DoanhNghiep
    public function doanhNghiep()
    {
        return $this->belongsTo(DoanhNghiep::class, 'dn_id', 'dn_id');
    }

     //  (Tuỳ chọn) Quan hệ: Một vị trí có thể có nhiều đăng ký thực tập
    public function dangKyThucTap()
    {
        return $this->hasMany(DangKyThucTap::class, 'vitri_id', 'vitri_id');
    }
    //  Scope lọc theo trạng thái
    public function scopeConHan($query)
    {
        return $query->where('trang_thai', 'con_han');
    }

    public function scopeHetHan($query)
    {
        return $query->where('trang_thai', 'het_han');
    }

    public function scopeDay($query)
    {
        return $query->where('trang_thai', 'day');
    }

    //  Hàm kiểm tra còn slot trống
    public function conSlot()
    {
        return $this->so_luong_da_dangky < $this->soluong;
    }


    
}