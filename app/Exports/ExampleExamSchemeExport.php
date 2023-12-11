<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Concerns\FromArray;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use \PhpOffice\PhpSpreadsheet\Style\Border;

class ExampleExamSchemeExport implements FromArray,WithHeadings, ShouldAutoSize,WithEvents
{

    protected array $data = [];
    protected array $headings;
    protected array $mergedColumns;
    protected array $correctColumns;
    protected array $wrongColumns;

    public function __construct($headings,$mergedColumns,$correctColumns,$wrongColumns)
    {
        $this->headings = $headings;
        $this->mergedColumns = $mergedColumns;
        $this->correctColumns = $correctColumns;
        $this->wrongColumns = $wrongColumns;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $cellRange = $sheet->calculateWorksheetDimension();
                $sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
//                $sheet->getStyle('A1:A100')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E7FF7DAF');
//                foreach ($this->correctColumns as $column) {
//                    $sheet->getStyle($column)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00FF77');
//                }
//                foreach ($this->wrongColumns as $column) {
//                    $sheet->getStyle($column)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFA6AD');
//                }
                foreach ($this->mergedColumns as $column) {
                    $cell = $event->sheet->getDelegate()->mergeCells($column);
                    $cell->getStyle($column)->getAlignment()->setHorizontal('center');
                    $cell->getStyle($column)->getAlignment()->setVertical('center');
                    $cell->getStyle($column)->getFont()->setBold(true);
                    $cell->getStyle($column)->getFont()->setSize(12);
                    $cell->getStyle($column)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFA0A0A0');
                }
            },
        ];
    }
}
