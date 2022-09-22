<?php

namespace App\Exports;

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

class StudentTestExport implements WithMapping,Responsable,WithHeadings,FromCollection,WithEvents,ShouldAutoSize
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
            'Level Grade',
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
            $student->lesson->level->name,
            $student->lesson->level->grade_name,
            $student->total_per,
            $student->status,
            Carbon::parse($student->created_at)->toDateTimeString(),

        ];
    }

    public function collection()
    {
        $username = request()->get('username', false);
        $grade = request()->get('grade', false);
        $level_id = request()->get('level_id', false);
        $lesson_id = request()->get('lesson_id', false);
        $start_at = request()->get('start_at', false);
        $end_at = request()->get('end_at', false);
        $status = request()->get('status', false);
        $teacher = $this->teacher_id;
        $school_id = $this->school_id;

        $students = UserTest::query()->whereHas('user', function (Builder $query) use ($teacher, $username, $school_id){
             $query->when($teacher, function (Builder $query) use ($teacher){
                 $query->whereHas('teacherUser', function (Builder $query) use($teacher){
                     $query->where('teacher_id', $teacher);
                 });
             });
            $query->when($username, function (Builder $query) use ($username){
                $query->where('name', 'like', '%'.$username.'%');
            });
//            $query->when($school_id, function (Builder $query) use ($school_id){
//                $query->where('school_id', $school_id);
//            });
        })->when($grade, function (Builder $query) use ($grade){
            $query->whereHas('lesson', function (Builder $query) use ($grade){
                $query->whereHas('level', function (Builder $query) use ($grade){
                    $query->where('grade', $grade);
                });
            });
        })->when($level_id, function (Builder $query) use ($level_id){
            $query->whereHas('lesson', function (Builder $query) use ($level_id){
                $query->where('level_id', $level_id);
            });
        })->when($lesson_id, function (Builder $query) use ($lesson_id){
            $query->where('lesson_id', $lesson_id);
        })->when($start_at, function (Builder $query) use ($start_at){
            $query->where('created_at', '<=', $start_at);
        })->when($end_at, function (Builder $query) use ($end_at){
            $query->where('created_at', '>=', $end_at);
        })->when($status && $status == 1, function (Builder $query) {
            $query->where('total', '>=', 40);
        })->when($status && $status == 2, function (Builder $query) {
            $query->where('total', '<', 40);
        })->latest();

        if($students->count() >= 1){
            $this->length = $students->count()+1;
        }
        $this->length = $students->count() +1;
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
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:I1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold('bold')->setSize(12);
                $event->sheet->styleCells(
                    "A1:I$this->length",
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

