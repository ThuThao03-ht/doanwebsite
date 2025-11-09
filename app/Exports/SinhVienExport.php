<?php

namespace App\Exports;

use App\Models\SinhVien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SinhVienExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles, 
    WithColumnWidths, 
    ShouldAutoSize,
    WithTitle,
    WithCustomStartCell
{
    protected $nganh;

    // ✅ Constructor nhận ngành được chọn
    public function __construct($nganh = null)
    {
        $this->nganh = $nganh;
    }

    public function collection()
    {
        $query = SinhVien::where('is_delete', 0)->with('user');

        if ($this->nganh) {
            $query->where('nganh', $this->nganh);
        }

        return $query->get()->map(function ($sv) {
            return [
                'Mã sinh viên' => $sv->ma_sv,
                'Họ tên' => $sv->ho_ten,
                'Lớp' => $sv->lop,
                'Ngành' => $sv->nganh,
                'Email' => $sv->email,
                'SĐT' => $sv->sdt,
                'Username' => $sv->user ? $sv->user->username : '-'
            ];
        });
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return ['Mã sinh viên', 'Họ tên', 'Lớp', 'Ngành', 'Email', 'SĐT', 'Username'];
    }

    public function styles(Worksheet $sheet)
    {
        // === Dòng tiêu đề lớn ===
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', '📘 DANH SÁCH ' . 
            ($this->nganh ? strtoupper($this->nganh) : 'TOÀN BỘ SINH VIÊN')
        );

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1F4E78'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);
        $sheet->getRowDimension('1')->setRowHeight(30);

        // === Hàng tiêu đề cột ===
        $sheet->getStyle('A2:G2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // === Nội dung ===
        $sheet->getStyle('A3:G' . $sheet->getHighestRow())->applyFromArray([
            'font' => ['size' => 13],
            'alignment' => ['vertical' => 'center'],
        ]);

        $sheet->getStyle('A:G')->getAlignment()->setHorizontal('center');

        foreach (range(1, $sheet->getHighestRow()) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(22);
        }

        $sheet->getStyle('A2:G' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => 'AAAAAA'],
                ],
            ],
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, 'B' => 25, 'C' => 15, 'D' => 20, 'E' => 30, 'F' => 18, 'G' => 20,
        ];
    }

    public function title(): string
    {
        return 'Danh sách sinh viên';
    }
}