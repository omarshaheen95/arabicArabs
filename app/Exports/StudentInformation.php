<?php

namespace App\Exports;

use App\Models\StudentTerm;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\BeforeExport;
use Illuminate\Database\Eloquent\Builder;

class StudentInformation implements WithMapping, Responsable, WithHeadings, FromCollection, WithEvents, ShouldAutoSize
{
    use Exportable;

    public $length;

    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->length = 1;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Grade',
            'Section',
            'Email',
            'Default Password',
            'Mobile',
            'Gender',
            'School Name',
            'Teacher Name',
            'Active To',
            'Last Login',
        ];
    }

    public function map($student): array
    {
        return [
            $student->name,
            $student->grade_name,
            $student->section,
            $student->email,
            '123456',
            $student->mobile . ' ',
            $student->gender,
            optional($student->school)->name,
            optional(optional($student->teacher_student)->teacher)->name,
            is_null($student->active_to) ? 'unpaid' : optional($student->active_to)->format('Y-m-d'),
            $student->last_login?Carbon::parse($student->last_login)->toDateTimeString():''

        ];
    }

    public function collection()
    {


        $students = User::query()->with(['teacherUser.teacher','school'])->filter($this->request)->latest();

        if ($students->count() >= 1) {
            $this->length = $students->count() + 1;
        }

        $this->length = $students->count() + 1;
        return $students->orderBy('grade_id', 'desc')->get();
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
                $cellRange = 'A1:J1';
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
