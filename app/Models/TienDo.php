<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TienDo extends Model
{
    use HasFactory;

    // Tên bảng trong CSDL
    protected $table = 'tiendo';

    //  Khóa chính
    protected $primaryKey = 'tiendo_id';

    //  Cho phép Laravel tự quản lý created_at và updated_at
    public $timestamps = true;

    // Các cột có thể gán hàng loạt
    protected $fillable = [
        'dk_id',
        'noi_dung',
        'ngay_capnhat',
        'file_dinhkem',
        'is_delete',
    ];

    // =====================================
    //  Quan hệ giữa các bảng (Relationships)
    // =====================================

    // Một bản tiến độ thuộc về một đăng ký thực tập
    public function dangKyThucTap()
    {
        return $this->belongsTo(DangKyThucTap::class, 'dk_id', 'dk_id')->with(['sinhVien', 'viTriThucTap']);
    }

    // =====================================
    //  Các scope lọc dữ liệu nhanh
    // =====================================

    //  Lọc các bản ghi chưa xóa
    public function scopeChuaXoa($query)
    {
        return $query->where('is_delete', false);
    }

    //  Lọc tiến độ theo mã đăng ký
    public function scopeTheoDangKy($query, $dk_id)
    {
        return $query->where('dk_id', $dk_id);
    }


}