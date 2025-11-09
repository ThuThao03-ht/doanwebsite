<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class GiangVien extends Model
{
    use HasFactory;

    protected $table = 'giangvien';
    protected $primaryKey = 'gv_id';
    public $timestamps = true;

    protected $fillable = [
        'ma_gv',
        'ho_ten',
        'bo_mon',
        'email',
        'sdt',
        'user_id',
        'is_delete',
    ];

    protected $casts = [
        'is_delete' => 'boolean',
    ];

    /**
     * Quan hệ: Giảng viên thuộc về một tài khoản user.
     * user_id → users.user_id
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
     // Một giảng viên có thể đánh giá nhiều sinh viên
    public function danhGias()
    {
        return $this->hasMany(GiangVienDanhGia::class, 'gv_id', 'gv_id');
    }
}