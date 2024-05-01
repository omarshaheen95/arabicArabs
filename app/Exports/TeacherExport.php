<?php

namespace App\Exports;

use App\Models\Supervisor;
use App\Models\Teacher;
use App\Models\User;
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

    public $school_id;
    public $name;
    public $request;
    public $status;

    public function __construct(Request $request, $school_id = 0)
    {
        $this->school_id = $school_id ? $school_id : $request->get('school_id', false);
        $this->name = $request->get('name', false);
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
        ];
    }

    public function collection()
    {
        $school_id = $this->school_id;
        $name = $this->name;

        $teachers = Teacher::query()->latest()->when($name, function (Builder $query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%')
                ->orWhere('email', 'like', '%' . $name . '%')
                ->orWhere('mobile', 'like', '%' . $name . '%');
        })->when($school_id, function (Builder $query) use ($school_id) {
            $query->where('school_id', $school_id);
        })->when(auth()->user() instanceof Supervisor, function (Builder $query) {
            $query->whereHas('supervisor_teachers', function (Builder $query) {
                $query->where('supervisor_id', auth()->id());
            });
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
