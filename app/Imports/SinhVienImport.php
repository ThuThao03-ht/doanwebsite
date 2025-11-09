<?php

namespace App\Imports;

use App\Models\SinhVien;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class SinhVienImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures, Importable;

    public function model(array $row)
    {
        // Bỏ qua dòng trống
        if (!isset($row['ma_sinh_vien']) || empty(trim($row['ma_sinh_vien']))) {
            return null;
        }

        // Nếu mã sinh viên đã tồn tại → bỏ qua, không thêm mới
        if (SinhVien::where('ma_sv', $row['ma_sinh_vien'])->exists()) {
            return null;
        }

        // Tạo user tương ứng
        $user = User::create([
            'username'      => $row['ma_sinh_vien'],
            'password_hash' => Hash::make('123456'),
            'role_id'       => 2, // Sinh viên
            'nguoi_tao_id'  => 1,
            'mat_khau_moi'  => 1,
            'status'        => 'active',
        ]);

        return new SinhVien([
            'ma_sv'   => $row['ma_sinh_vien'],
            'ho_ten'  => $row['ho_ten'],
            'lop'     => $row['lop'],
            'nganh'   => $row['nganh'],
            'email'   => $row['email'],
            'sdt'     => $row['sdt'],
            'user_id' => $user->user_id,
        ]);
    }

    public function rules(): array
    {
        return [
            'ma_sinh_vien' => 'required|unique:sinhvien,ma_sv',
            'ho_ten'       => 'required|string',
            'lop'          => 'nullable|string',
            'nganh'        => 'nullable|string',
            'email'        => 'nullable|email',
            'sdt'          => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ma_sinh_vien.required' => 'Mã sinh viên là bắt buộc',
            'ma_sinh_vien.unique'   => 'Mã sinh viên đã tồn tại trong hệ thống',
            'ho_ten.required'       => 'Họ tên là bắt buộc',
            'email.email'           => 'Email không hợp lệ',
        ];
    }
}