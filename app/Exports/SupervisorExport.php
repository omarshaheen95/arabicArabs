<?php

namespace App\Exports;

use App\Models\Supervisor;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SupervisorExport implements WithMapping, Responsable, WithHeadings, FromCollection, WithEvents, ShouldAutoSize
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    public $school_id;
    public $name;
    public $request;
    public $status;

    public function __construct(Request $request, $school_id = 0)
    {
        $this->request = $request;
        $this->school_id = $school_id ? $school_id : $request->get('school_id', false);
        $this->name = $request->get('name', false);
        $this->length = 1;
    }

    public function headings(): array
    {
        return [
            'اسم المشرف',
            'البريد الإلكتروني',
            'كلمة المرور الافتراضية',
            'المدرسة',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->email,
            '123456',
            optional($row->school)->name,
        ];
    }

    public function collection()
    {
        $school_id = $this->school_id;
        $name = $this->name;

        $rows = Supervisor::query()->latest()->search($this->request);

        if ($rows->count() >= 1) {
            $this->length = $rows->count() + 1;
        }
        $this->length = $rows->count() + 1;
        return $rows->get();
    }

    public function drawings()
    {
        return new Drawing();
    }

    public function registerEvents(): array
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:D1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold('bold')->setSize(12);
                $event->sheet->styleCells(
                    "A1:D$this->length",
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],

                    ]
                );
            },
        ];
    }
}

