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

    public function model(array $row)
    {
        $dnValue = $row['dn_id'] ?? null;

        // Nếu không có dn_id
        if (!$dnValue) {
            $this->failures[] = new Failure(
                0, // dùng 0 thay vì null để tránh lỗi
                'dn_id',
                ["Cột 'Doanh nghiệp' không được để trống."],
                $row
            );
            return null;
        }

        // Nếu người dùng nhập tên doanh nghiệp thay vì ID
        if (!is_numeric($dnValue)) {
            $dn = DoanhNghiep::where('ten_dn', trim($dnValue))->first();
            if (!$dn) {
                Log::warning("Import lỗi: Doanh nghiệp '{$dnValue}' không tồn tại.", $row);
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
            // Nếu nhập mã doanh nghiệp (số ID)
            $dn = DoanhNghiep::find($dnValue);
            if (!$dn) {
                Log::warning("Import lỗi: Mã doanh nghiệp '{$dnValue}' không tồn tại.", $row);
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

        // Tạo bản ghi mới
        return new VitriThuctap([
            'dn_id' => $dn_id,
            'ma_vitri' => $row['ma_vitri'],
            'ten_vitri' => $row['ten_vitri'],
            'mo_ta' => $row['mo_ta'] ?? null,
            'yeu_cau' => $row['yeu_cau'] ?? null,
            'soluong' => $row['soluong'] ?? 1,
            'so_luong_da_dangky' => $row['so_luong_da_dangky'] ?? 0,
            'trang_thai' => $row['trang_thai'] ?? 'con_han',
        ]);
    }

    public function rules(): array
    {
        return [
            '*.dn_id' => 'required',
            '*.ma_vitri' => 'required|unique:vitri_thuctap,ma_vitri',
            '*.ten_vitri' => 'required',
            '*.soluong' => 'required|integer|min:1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.dn_id.required' => "Cột 'Doanh nghiệp' không được để trống.",
            '*.ma_vitri.required' => "Cột 'Mã vị trí' là bắt buộc.",
            '*.ma_vitri.unique' => "Mã vị trí này đã tồn tại trong hệ thống.",
            '*.ten_vitri.required' => "Cột 'Tên vị trí' là bắt buộc.",
            '*.soluong.required' => "Cột 'Số lượng' là bắt buộc.",
            '*.soluong.integer' => "Cột 'Số lượng' phải là số nguyên.",
            '*.soluong.min' => "Số lượng phải lớn hơn hoặc bằng 1.",
        ];
    }
}