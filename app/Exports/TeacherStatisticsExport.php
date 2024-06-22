<?php

namespace App\Exports;

use App\Models\Supervisor;
use App\Models\Teacher;
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

class TeacherStatisticsExport implements WithMapping, Responsable, WithHeadings, FromCollection, WithEvents, ShouldAutoSize
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    public $length;
    public $request;
    private $last_cell;
    private $last_row;
    public $status;

    public function __construct(Request $request, $school_id = false)
    {
        $this->request = $request;
        $this->length = 1;
    }

    public function headings(): array
    {
        return [
            'Teacher Name',
            'Passed tests',
            'Failed tests',
            'Pending teaks',
            'Completed tasks',
            'Returned tasks',
            'Last login',
        ];
    }

    public function map($teacher): array
    {
        return [
            $teacher->name,
            "$teacher->passed_tests",
            "$teacher->failed_tests",
            "$teacher->pending_tasks",
            "$teacher->corrected_tasks",
            "$teacher->returned_tasks",
            $teacher->last_login,
        ];
    }

    public function collection()
    {
        $teachers = Teacher::query()->filter($this->request)->latest();
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
                $this->last_cell = $event->sheet->getHighestColumn();
                $this->last_row = $event->sheet->getHighestRow();
                $cellRange = 'A1:'.$this->last_cell.'1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold('bold')->setSize(12);
                $event->sheet->styleCells(
                    "A1:$this->last_cell$this->last_row",
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
