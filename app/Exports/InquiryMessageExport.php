<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class InquiryMessageExport implements
    FromView,
    WithEvents
{
    use Exportable;

    protected $inquiry;

    public function __construct($inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function view(): View
    {
        $inquiry = $this->inquiry;

        return view('backend.inquiries.detail.export.contact', [
            'inquiry' => $inquiry,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $styleHeader = [
                    'font' => [
                        'bold' => true,
                        'size' => 12
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $styleContent = [
                    'borders' => [
                        'font' => [
                            'size' => 12
                        ],
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $styleTotalSistem = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ];
                $highestColumn = $event->sheet->getHighestColumn();
                $highestRow = $event->sheet->getHighestRow();

                // $event->sheet->mergeCells('A' . $highestRow . ':' . 'B' . $highestRow);
                // $event->sheet->mergeCells('A' . '1' . ':' . 'K' . '1');

                $event->sheet->getStyle('A2:' . $highestColumn . '2')->applyFromArray($styleHeader);
                $event->sheet->getStyle('A3:' . $highestColumn . $highestRow)->applyFromArray($styleContent);
                $event->sheet->getStyle('A' . $highestRow . ':' . 'B' . $highestRow)->applyFromArray($styleTotalSistem);
            },
        ];
    }
}
