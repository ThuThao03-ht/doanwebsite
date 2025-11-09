<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoanhNghiep extends Model
{
    use HasFactory;

    protected $table = 'doanhnghiep';
    protected $primaryKey = 'dn_id';
    public $timestamps = true;

    protected $fillable = [
        'ten_dn',
        'dia_chi',
        'email',
        'lien_he',
        'website',
        'logo',
        'mo_ta',
        'leader_user_id',
        'is_delete',
    ];

    protected $casts = [
        'is_delete' => 'boolean',
    ];

    /**
     * Quan hệ: Doanh nghiệp thuộc về 1 tài khoản user (leader)
     * leader_user_id → users.user_id
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_user_id', 'user_id');
    }

    /**
     * Nếu bạn có bảng vitri_thuctap thì có thể thêm quan hệ:
     * Một doanh nghiệp có nhiều vị trí thực tập
     */
    public function vitriThucTap()
    {
        return $this->hasMany(ViTriThucTap::class, 'dn_id', 'dn_id');
    }

     // Một doanh nghiệp có thể đánh giá nhiều sinh viên
    public function danhGias()
    {
        return $this->hasMany(DoanhNghiepDanhGia::class, 'dn_id', 'dn_id');
    }
}