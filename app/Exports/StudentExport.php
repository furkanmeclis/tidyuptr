<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentExport implements FromCollection,ShouldAutoSize,WithHeadings,WithStyles,WithDefaultStyles
{
    public function collection()
    {
        return Student::where('organization_id',auth('organization')->user()->id)->get()->map(function ($student) {
            return [
                "id" => $student->id,
                "name" => $student->name,
                "identity_number" => $student->identity_number,
                "email" => $student->email,
                "class" => $student->className(),
                "teacher" => $student->getTeacher()->name,
                "phone" => $student->phone ? $student->phone : "",
                "address" => $student->address ? $student->address : "",
                "register_date" => $student->created_at->format("d.m.Y"),
            ];
        });
    }

    public function headings(): array
    {
        return [
            "ID",
            "Ad Soyad",
            "Kimlik Numarası",
            "E-Posta",
            "Sınıf",
            "Öğretmen",
            "Telefon",
            "Adres",
            "Kayıt Tarihi",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ],

            ]
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
             'borders' => [
                 'allBorders' => [
                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                 ],
             ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],

        ];
    }
}
