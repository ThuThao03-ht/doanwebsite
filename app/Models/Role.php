<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $timestamps = true; // Có created_at và updated_at

    protected $fillable = [
        'role_name',
        'is_delete',
    ];

    protected $casts = [
        'is_delete' => 'boolean',
    ];

    // Quan hệ: Một role có nhiều user
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}