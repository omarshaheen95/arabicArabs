<?php

namespace App\Exports;

use App\Models\Supervisor;
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

class TeacherExport implements WithMapping, Responsable, WithHeadings, FromCollection, WithEvents, ShouldAutoSize
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
            'Teacher Name',
            'Email',
            'Default Password',
            'Mobile',
            'School',
            'Active Status',
            'Status',
            'Active to',
        ];
    }

    public function map($teacher): array
    {
        return [
            $teacher->name,
            $teacher->email,
            '123456',
            $teacher->mobile . ' ',
            optional($teacher->school)->name,
            $teacher->active?t('Active'):t('Non-Active'),
            $teacher->approved?t('Approved'):t('Under review'),
            $teacher->active_to?Carbon::parse($teacher->active_to)->format('d/m/Y h:i A'):''
        ];
    }

    public function collection()
    {
        $teachers = Teacher::with('school')->filter($this->request)->latest();

        if ($teachers->count() >= 1) {
            $this->length = $teachers->count() + 1;
        }
        $this->length = $teachers->count() + 1;
        return $teachers->get();
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
                $cellRange = 'A1:H1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold('bold')->setSize(12);
                $event->sheet->styleCells(
                    "A1:H$this->length",
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
