<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tên bảng
    protected $table = 'users';

    // Khóa chính
    protected $primaryKey = 'user_id';

    // Các cột có thể gán (mass assignable)
    protected $fillable = [
        'username',
        'password_hash',
        'avatar',
        'role_id',
        'nguoi_tao_id',
        'mat_khau_moi',
        'status',
        'is_delete',
    ];

    // Ẩn trường khi trả JSON
    protected $hidden = [
        'password_hash',
    ];

    // Tự động cast kiểu dữ liệu
    protected $casts = [
        'mat_khau_moi' => 'boolean',
        'is_delete' => 'boolean',
    ];

    // Quan hệ: 1 user thuộc về 1 role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    // Quan hệ: 1 user có thể được tạo bởi 1 người khác (admin)
    public function nguoiTao()
    {
        return $this->belongsTo(User::class, 'nguoi_tao_id', 'user_id');
    }

    // Quan hệ: 1 user có thể tạo nhiều tài khoản khác
    public function nguoiDuocTao()
    {
        return $this->hasMany(User::class, 'nguoi_tao_id', 'user_id');
    }

    // Một user (có vai trò sinh viên) chỉ có 1 bản ghi sinhvien
    public function sinhvien()
    {
        return $this->hasOne(SinhVien::class, 'user_id', 'user_id');
    }
    // Một user (với vai trò giảng viên) có 1 thông tin giảng viên
    public function giangvien()
    {
        return $this->hasOne(GiangVien::class, 'user_id', 'user_id');
    }
    // Một user (leader doanh nghiệp) có thể đại diện cho một doanh nghiệp
    public function doanhnghiep()
    {
        return $this->hasOne(DoanhNghiep::class, 'leader_user_id', 'user_id');
    }
    public function danhGiaDoanhNghiep()
    {
        return $this->hasMany(DoanhNghiepDanhGia::class, 'nguoi_danhgia_id', 'user_id');
    }
    public function thongBaoDaNhan()
    {
        return $this->hasMany(ThongBaoUser::class, 'user_id', 'user_id');
    }

    public function thongBaoDaGui()
    {
        return $this->hasMany(ThongBao::class, 'nguoi_gui_id', 'user_id');
    }
   
// User.php

public function doanhnghiepLeader() {
    return $this->hasOne(DoanhNghiep::class, 'leader_user_id','user_id');
}

}