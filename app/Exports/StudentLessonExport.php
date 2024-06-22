<?php

namespace App\Exports;

use App\Models\UserLesson;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class StudentLessonExport implements WithMapping, Responsable, WithHeadings, FromCollection, WithEvents, ShouldAutoSize
{
    use Exportable;
    public $request;
    public $last_cell;
    public $last_row;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Grade',
            'Section',
            'School Name',
            'Teacher Name',
            'Lesson',
            'Status',
            'Summited At',
        ];
    }

    public function map($row): array
    {
        \Log::alert($row->id);
        return [
            optional($row->user)->name,
            $row->user->grade->name,
            $row->user->section,
            optional(optional($row->user)->school)->name,
            optional(optional($row->user)->teacher)->name ?:null,
            $row->lesson->name,
            $row->status_name,
            $row->created_at->format('Y-m-d H:i')
        ];
    }

    public function collection()
    {
        $rows = UserLesson::query()->has('user')->has('lesson')->with(['user.school','user.grade', 'user.teacher', 'lesson.level'])->filter($this->request)->latest()->get();
        return $rows;
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

                $redStyle = new Style(false, true);
                $redStyle->getFont()->setColor(new Color('1E4397'));
                $greenStyle = new Style(false, true);
                $greenStyle->getFont()->setColor(new Color('50cd89'));
                $orangeStyle = new Style(false, true);
                $orangeStyle->getFont()->setColor(new Color("ffc700"));

                $cellRange = "A1:".$this->last_cell.$this->last_row;
                $conditionalStyles = [];
                $wizardFactory = new Wizard($cellRange);
                /** @var Wizard\TextValue $textWizard */
                $textWizard = $wizardFactory->newRule(Wizard::TEXT_VALUE);

                $textWizard->contains(t(ucfirst('Waiting list')))->setStyle($redStyle );
                $conditionalStyles[] = $textWizard->getConditional();

                $textWizard->contains(t(ucfirst('Marking Completed')))->setStyle($greenStyle);
                $conditionalStyles[] = $textWizard->getConditional();


                $textWizard->contains( t(ucfirst('Send back')))->setStyle($orangeStyle);
                $conditionalStyles[] = $textWizard->getConditional();

                $event->sheet
                    ->getStyle($textWizard->getCellRange())
                    ->setConditionalStyles($conditionalStyles);
            },
        ];
    }
}
