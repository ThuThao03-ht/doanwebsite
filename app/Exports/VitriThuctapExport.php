<?php
namespace App\Exports;

use App\Models\VitriThuctap;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VitriThuctapExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles, 
    WithColumnWidths, 
    ShouldAutoSize,
    WithTitle,
    WithCustomStartCell
{
    protected $dn_id;

    public function __construct($dn_id = null)
    {
        $this->dn_id = $dn_id;
    }

    public function collection()
    {
        $query = VitriThuctap::with('doanhnghiep')->where('is_delete', 0);

        if ($this->dn_id) {
            $query->where('dn_id', $this->dn_id);
        }

        return $query->get()->map(function($vt) {
            return [
                'ID' => $vt->vitri_id,
                'Doanh nghiệp' => $vt->doanhnghiep->ten_dn ?? '',
                'Mã vị trí' => $vt->ma_vitri,
                'Tên vị trí' => $vt->ten_vitri,
                'Mô tả' => $vt->mo_ta,
                'Yêu cầu' => $vt->yeu_cau,
                'Số lượng' => $vt->soluong,
                'Số lượng đã đăng ký' => $vt->so_luong_da_dangky ?? 0,
                'Trạng thái' => $vt->trang_thai,
            ];
        });
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Doanh nghiệp',
            'Mã vị trí',
            'Tên vị trí',
            'Mô tả',
            'Yêu cầu',
            'Số lượng',
            'Số lượng đã đăng ký',
            'Trạng thái',
        ];
    }

  public function styles(Worksheet $sheet)
{
    $titleText = '📘 DANH SÁCH VỊ TRÍ THỰC TẬP';
    if ($this->dn_id) {
        $dn = \App\Models\DoanhNghiep::find($this->dn_id);
        if ($dn) $titleText .= ' - ' . strtoupper($dn->ten_dn);
    }

    // Merge tiêu đề lớn
    $sheet->mergeCells('A1:I1');
    $sheet->setCellValue('A1', $titleText);

    $sheet->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F4E78']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);
    $sheet->getRowDimension('1')->setRowHeight(30);

    // Style header cột
    $sheet->getStyle('A2:I2')->applyFromArray([
        'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F81BD']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
    ]);

    // Nội dung chung: canh giữa
    $sheet->getStyle('A3:I' . $sheet->getHighestRow())->applyFromArray([
        'font' => ['size' => 12],
        'alignment' => ['vertical' => 'center', 'horizontal' => 'center'],
    ]);

    // Cột Mô tả (E) và Yêu cầu (F) canh trái
    $sheet->getStyle('E3:E' . $sheet->getHighestRow())
          ->getAlignment()->setHorizontal('left');
    $sheet->getStyle('F3:F' . $sheet->getHighestRow())
          ->getAlignment()->setHorizontal('left');

    // Chiều cao row
    foreach (range(1, $sheet->getHighestRow()) as $row) {
        $sheet->getRowDimension($row)->setRowHeight(22);
    }

    // Border cho toàn bộ bảng
    $sheet->getStyle('A2:I' . $sheet->getHighestRow())->applyFromArray([
        'borders' => [
            'allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'AAAAAA']],
        ],
    ]);

    return [];
}


    public function columnWidths(): array
    {
        return [
            'A' => 10, 'B' => 25, 'C' => 15, 'D' => 45, 'E' => 80, 'F' => 80, 'G' => 12, 'H' => 18, 'I' => 12,
        ];
    }

    public function title(): string
    {
        return 'Vị trí thực tập';
    }
}