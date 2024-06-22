<?php

namespace App\Exports;

use App\Models\Lesson;
use App\Models\MotivationalCertificate;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MotivationalCertificateExport implements WithMapping,Responsable,WithHeadings,FromCollection,WithEvents,ShouldAutoSize
{
    use Exportable;
    private $last_cell;
    private $last_row;
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        if ($this->request->get('model_type') == Lesson::class)
        {
            return [
                'Name',
                'Email',
                'Student Grade',
                'Lesson',
                'Grade',
                'Granted In',
                'Teacher'
            ];
        }else{
            return [
                'Name',
                'Email',
                'Student Grade',
                'Story',
                'Story Level',
                'Granted In',
                'Teacher'
            ];
        }

    }

    public function map($row): array
    {
        if ($this->request->get('model_type') == Lesson::class) {
            return [
                $row->user->name,
                $row->user->email,
                $row->user->grade->name,
                optional($row->model)->name,
                optional($row->model)->grade->name,
                $row->granted_in,
                $row->teacher->name,
            ];
        }else{
            return [
                $row->user->name,
                $row->user->email,
                $row->user->grade->name,
                optional($row->model)->name,
                optional($row->model)->grade_name,
                $row->granted_in,
                $row->teacher->name,
            ];
        }
    }

    public function collection()
    {
        $rows = MotivationalCertificate::with(['user.grade'])->filter($this->request);
        return $rows->orderBy('user_id')->get();
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

