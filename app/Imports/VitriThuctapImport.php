<?php

namespace App\Imports;

use App\Models\VitriThuctap;
use App\Models\DoanhNghiep;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class VitriThuctapImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    // Hàm tạo mã vị trí
    private function taoMaViTri()
    {
        $last = VitriThuctap::orderBy('ma_vitri', 'desc')->first();
        if (!$last) return 'VT0001';

        $num = intval(substr($last->ma_vitri, 2)) + 1;
        return 'VT' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function model(array $row)
    {
        $dnValue = $row['dn_id'] ?? null;

        // Không có DN
        if (!$dnValue) {
            $this->failures[] = new Failure(
                0,
                'dn_id',
                ["Cột 'Doanh nghiệp' không được để trống."],
                $row
            );
            return null;
        }

        // Cho phép nhập tên doanh nghiệp hoặc ID
        if (!is_numeric($dnValue)) {
            $dn = DoanhNghiep::where('ten_dn', trim($dnValue))->first();
            if (!$dn) {
                $this->failures[] = new Failure(
                    0,
                    'dn_id',
                    ["Doanh nghiệp '{$dnValue}' không tồn tại trong hệ thống."],
                    $row
                );
                return null;
            }
            $dn_id = $dn->dn_id;
        } else {
            $dn = DoanhNghiep::find($dnValue);
            if (!$dn) {
                $this->failures[] = new Failure(
                    0,
                    'dn_id',
                    ["Mã doanh nghiệp '{$dnValue}' không tồn tại trong hệ thống."],
                    $row
                );
                return null;
            }
            $dn_id = $dnValue;
        }

        // 🔥 KIỂM TRA TRÙNG TEN_VITRI + DN_ID
        $exists = VitriThuctap::where('dn_id', $dn_id)
            ->where('ten_vitri', trim($row['ten_vitri']))
            ->where('is_delete', 0)
            ->exists();

        if ($exists) {
            $this->failures[] = new Failure(
                0,
                'ten_vitri',
                ["Tên vị trí '{$row['ten_vitri']}' đã tồn tại trong doanh nghiệp này."],
                $row
            );
            return null;
        }

        // Tạo mã vị trí tự động
        $ma_vitri = $this->taoMaViTri();

        return new VitriThuctap([
            'dn_id' => $dn_id,
            'ma_vitri' => $ma_vitri,
            'ten_vitri' => $row['ten_vitri'],
            'mo_ta' => $row['mo_ta'] ?? null,
            'yeu_cau' => $row['yeu_cau'] ?? null,
            'soluong' => $row['soluong'] ?? 1,
            'so_luong_da_dangky' => 0,
            'trang_thai' => $row['trang_thai'] ?? 'con_han',
        ]);
    }

    public function rules(): array
    {
        return [
            '*.dn_id' => 'required',
            '*.ten_vitri' => 'required',
            '*.soluong' => 'required|integer|min:1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.dn_id.required' => "Cột 'Doanh nghiệp' không được để trống.",
            '*.ten_vitri.required' => "Cột 'Tên vị trí' là bắt buộc.",
            '*.soluong.required' => "Cột 'Số lượng' là bắt buộc.",
            '*.soluong.integer' => "Cột 'Số lượng' phải là số nguyên.",
            '*.soluong.min' => "Số lượng phải lớn hơn hoặc bằng 1.",
        ];
    }
}