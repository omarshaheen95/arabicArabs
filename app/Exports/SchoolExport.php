<?php

namespace App\Exports;

use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
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

class SchoolExport implements WithMapping, Responsable, WithHeadings, FromCollection, WithEvents, ShouldAutoSize
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    public $length;
    public $request;

    public function __construct(Request $request, $school_id = 0)
    {
        $this->request = $request;
        $this->length = 1;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Active',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->email,
            $row->mobile . ' ',
            $row->active?t('Active'):t('Non-Active'),
        ];
    }

    public function collection()
    {
        $schools = School::query()->filter($this->request)->latest();

        if ($schools->count() >= 1) {
            $this->length = $schools->count() + 1;
        }
        $this->length = $schools->count() + 1;
        return $schools->get();
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
                $cellRange = 'A1:E1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold('bold')->setSize(12);
                $event->sheet->styleCells(
                    "A1:E$this->length",
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
