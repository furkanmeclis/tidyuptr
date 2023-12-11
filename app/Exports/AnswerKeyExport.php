<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnswerKeyExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        // Şema başlıklarını belirtin
        return [
            'SORU NO',
            'DERS',
            'B',
            'CEVAP',
            'KONU'
        ];
    }

}
