<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinhvien';
    protected $primaryKey = 'sv_id';
    public $timestamps = true;

    protected $fillable = [
        'ma_sv',
        'ho_ten',
        'lop',
        'nganh',
        'email',
        'sdt',
        'user_id',
        'is_delete',
    ];

    protected $casts = [
        'is_delete' => 'boolean',
    ];

    /**
     * Quan hệ: Sinh viên thuộc về một tài khoản user.
     * user_id → users.user_id
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Quan hệ: 1 sinh viên có thể đăng ký nhiều vị trí thực tập
    public function dangKyThucTap()
    {
        return $this->hasMany(DangKyThucTap::class, 'sv_id', 'sv_id');
    }
}