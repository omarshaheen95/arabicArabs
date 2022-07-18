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

    public $school_id;
    public $teacher_id;
    public $name;
    public $grade;
    public $length;
    public $status;
    public $section;
    public $created_at;
    public $request;

    public function __construct(Request $request,$school_id = 0)
    {
        $this->school_id = request()->get('school_id', $school_id);
        $this->request = $request;
        $this->length = 1;
    }

    public function headings(): array
    {
        return [
            'اسم الطالب',
            'الصف',
            'الشعبة',
            'البريد الإلكتروني',
            'كلمة المرور الإفتراضية',
            'الموبايل',
            'المدرسة',
            'المعلم',
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
            optional($student->school)->name,
            optional(optional($student->teacher_student)->teacher)->name,

        ];
    }

    public function collection()
    {


        $students = User::query()->latest()->search($this->request);

        if ($students->count() >= 1) {
            $this->length = $students->count() + 1;
        }

        $this->length = $students->count() + 1;
        return $students->orderBy('grade', 'desc')->get();
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
