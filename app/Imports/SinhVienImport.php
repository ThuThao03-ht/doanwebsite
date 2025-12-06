<?php

namespace App\Imports;

use App\Models\SinhVien;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class SinhVienImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures, Importable;

    // Tạo mã SV tự động
    private function taoMaSV()
    {
        $maxNumber = SinhVien::selectRaw("MAX(CAST(SUBSTRING(ma_sv, 3) AS UNSIGNED)) AS max_number")
            ->value('max_number');

        $newNumber = $maxNumber ? $maxNumber + 1 : 1;

        return 'SV' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function model(array $row)
    {
        // Nếu không có họ tên → bỏ
        if (!isset($row['ho_ten']) || empty(trim($row['ho_ten']))) {
            return null;
        }

        // Tạo mã SV
        $newMaSV = $this->taoMaSV();

        // Tạo user
        $user = User::create([
            'username'      => $newMaSV,
            'password_hash' => Hash::make('123456'),
            'role_id'       => 2,
            'nguoi_tao_id'  => 1,
            'mat_khau_moi'  => 1,
            'status'        => 'active',
        ]);

        // Tạo sinh viên
        return new SinhVien([
            'ma_sv'   => $newMaSV,
            'ho_ten'  => $row['ho_ten'],
            'lop'     => $row['lop']     ?? null,
            'nganh'   => $row['nganh']   ?? null,
            'email'   => $row['email']   ?? null,
            'sdt'     => $row['sdt']     ?? null,
            'user_id' => $user->user_id,
        ]);
    }

    // Validate
    public function rules(): array
    {
        return [
            'ho_ten' => ['required', 'string'],

            'email' => [
                'nullable',
                'email',
                Rule::unique('sinhvien', 'email')
            ],

            'sdt' => [
                'nullable',
                'regex:/^\d{10}$/',
                Rule::unique('sinhvien', 'sdt')
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ho_ten.required' => 'Họ tên là bắt buộc',

            'email.email'     => 'Email không hợp lệ',
            'email.unique'    => 'Email đã tồn tại trong hệ thống',

            'sdt.regex'       => 'Số điện thoại phải đúng 10 chữ số',
            'sdt.unique'      => 'Số điện thoại đã tồn tại trong hệ thống',
        ];
    }
}