<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BaoCaoThucTap extends Model
{
    use HasFactory;

    //  Tên bảng trong CSDL
    protected $table = 'baocao_thuctap';

    //  Khóa chính
    protected $primaryKey = 'baocao_id';

    //  Laravel sẽ tự động quản lý created_at và updated_at
    public $timestamps = true;

    //  Các cột có thể gán giá trị hàng loạt
    protected $fillable = [
        'dk_id',
        'tieu_de',
        'noi_dung',
        'ngay_nop',
        'file_baocao',
        'is_delete',
    ];

    // =====================================
    //  Quan hệ giữa các bảng (Relationships)
    // =====================================

    //  Một báo cáo thực tập thuộc về một đăng ký thực tập
    public function dangKyThucTap()
    {
        return $this->belongsTo(DangKyThucTap::class, 'dk_id', 'dk_id');
    }


    // =====================================
    //  Các scope lọc dữ liệu nhanh
    // =====================================

    //  Lọc các báo cáo chưa bị xóa
    public function scopeChuaXoa($query)
    {
        return $query->where('is_delete', false);
    }

    //  Lấy báo cáo theo mã đăng ký
    public function scopeTheoDangKy($query, $dk_id)
    {
        return $query->where('dk_id', $dk_id);
    }
}