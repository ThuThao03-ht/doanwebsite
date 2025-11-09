<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ThongBao extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'thongbao';

    // Khóa chính
    protected $primaryKey = 'tb_id';

    // Các cột có thể gán hàng loạt
    protected $fillable = [
        'tieude',
        'noidung',
        'ngay_gui',
        'nguoi_gui_id',
        'doi_tuong',
        'is_delete',
    ];

    // Kiểu dữ liệu của các trường
    protected $casts = [
        'ngay_gui' => 'date',
        'is_delete' => 'boolean',
    ];

    /**
     * Mối quan hệ: Một thông báo được gửi bởi một người dùng (user)
     */
    public function nguoiGui()
    {
        return $this->belongsTo(User::class, 'nguoi_gui_id', 'user_id');
    }

    public function nguoiNhan()
    {
        return $this->hasMany(ThongBaoUser::class, 'thongbao_id', 'tb_id');
    }
    /**
     * Lọc thông báo theo đối tượng (tat_ca, sinhvien, giangvien, doanhnghiep)
     */
    public function scopeTheoDoiTuong($query, $doiTuong)
    {
        return $query->where(function ($q) use ($doiTuong) {
            $q->where('doi_tuong', '=', $doiTuong)
              ->orWhere('doi_tuong', '=', 'tat_ca');
        });
    }
}