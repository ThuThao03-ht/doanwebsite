<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThongBaoUser extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'thongbao_user';

    // Khóa chính
    protected $primaryKey = 'id';

    // Các trường có thể gán hàng loạt
    protected $fillable = [
        'thongbao_id',
        'user_id',
        'da_doc',
        'thoi_gian_doc',
    ];

    // Kiểu dữ liệu tự động chuyển đổi
    protected $casts = [
        'da_doc' => 'boolean',
        'thoi_gian_doc' => 'datetime',
    ];

    /**
     * Mối quan hệ: Một bản ghi thuộc về một thông báo
     */
    public function thongBao()
    {
        return $this->belongsTo(ThongBao::class, 'thongbao_id', 'tb_id');
    }

    /**
     * Mối quan hệ: Một bản ghi thuộc về một người dùng
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope: Lọc các thông báo chưa đọc của người dùng
     */
    public function scopeChuaDoc($query)
    {
        return $query->where('da_doc', false);
    }

    /**
     * Scope: Lọc các thông báo đã đọc của người dùng
     */
    public function scopeDaDoc($query)
    {
        return $query->where('da_doc', true);
    }
}