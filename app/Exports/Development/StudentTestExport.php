<?php

namespace App\Exports\Development;

use App\Models\StudentTest;
use App\Models\User;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class StudentTestExport implements WithMapping, Responsable, WithHeadings, FromCollection, WithEvents, ShouldAutoSize
{
    use Exportable;

    public $school_id;
    public $teacher_id;
    public $name;
    public $grade;
    public $length;

    public function __construct($school_id = 0, $teacher_id = 0)
    {
        $this->school_id = $school_id;
        $this->teacher_id = $teacher_id;
        $this->length = 1;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Student Grade',
            'Lesson',
            'Level',
            'Total',
            'Status',
            'Submitted at',
        ];
    }

    public function map($student): array
    {
        return [
            $student->user->name,
            $student->user->email,
            $student->user->grade_name,
            $student->lesson->name,
            $student->lesson->grade->name,
            $student->total,
            $student->status,
            Carbon::parse($student->created_at)->toDateTimeString(),

        ];
    }

    public function collection()
    {
        $students = UserTest::query()->with(['user', 'lesson', 'lesson.grade'])->has('lesson')->has('user')->search(request())->latest();

        if ($students->count() >= 1) {
            $this->length = $students->count() + 1;
        }
        $this->length = $students->count() + 1;
        return $students->orderBy('user_id')->get();
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

