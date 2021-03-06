<?php

namespace App\Exports;

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

    public $school_id;
    public $name;
    public $request;
    public $status;

    public function __construct(Request $request, $school_id = false)
    {
        $this->school_id = $school_id ? $school_id:$request->get('school_id', false);
        $this->name = $request->get('name', false);
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
            $teacher->last_login ? Carbon::parse($teacher->last_login)->toDateTimeString():'',
        ];
    }

    public function collection()
    {
        $school_id = $this->school_id;
        $name = $this->name;

        $teachers = Teacher::query()->latest()->when($name, function (Builder $query) use ($name) {
            $query->where('name', 'like','%' . $name . '%')
                ->orWhere('email', 'like', '%' . $name . '%')
                ->orWhere('mobile', 'like', '%' . $name . '%');
        })->when($school_id, function (Builder $query) use ($school_id) {
            $query->where('school_id', $school_id);
        });

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
                $cellRange = 'A1:G1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold('bold')->setSize(12);
                $event->sheet->styleCells(
                    "A1:G$this->length",
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
