<?php

namespace App\Exports;

use App\Models\StudentTest;
use App\Models\User;
use App\Models\UserAssignment;
use Carbon\Carbon;
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
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class StudentAssignmentExport implements WithMapping,Responsable,WithHeadings,FromCollection,WithEvents,ShouldAutoSize
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
        return [
            'Name',
            'Email',
            'Student Grade',
            'School',
            'Teacher',
            'Lesson',
            'Grade',
            'Tasks',
            'Test',
            'Status',
            'Assigned in',
            'Deadline',
            'Submit Status',
        ];
    }

    public function map($student): array
    {
        return [
            $student->user->name,
            $student->user->email,
            $student->user->grade->name,
            $student->user->school->name,
            optional($student->user->teacher)->name,
            $student->lesson->name,
            $student->lesson->grade->name,

            $student->done_tasks_assignment ? 'Completed':'Incomplete',
            $student->done_test_assignment ? 'Completed':'Incomplete',
            $student->completed ? 'Completed':'Incomplete',
            Carbon::parse($student->created_at)->toDateTimeString(),
            $student->deadline ? Carbon::parse($student->deadline)->toDateTimeString():'',
            $student->submit_status

        ];
    }

    public function collection()
    {
        $students = UserAssignment::query()->has('user')->with(['lesson.grade', 'user.school','user.grade', 'user.teacher'])->filter($this->request)->latest();
        return $students->get();
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

                $redStyle = new Style(false, true);
                $redStyle->getFont()->setColor(new Color(Color::COLOR_RED));
                $greenStyle = new Style(false, true);
                $greenStyle->getFont()->setColor(new Color(Color::COLOR_GREEN));
                $orangeStyle = new Style(false, true);
                $orangeStyle->getFont()->setColor(new Color("FFC107"));

                $cellRange = "A1:".$this->last_cell.$this->last_row;
                $conditionalStyles = [];
                $wizardFactory = new Wizard($cellRange);
                /** @var Wizard\TextValue $textWizard */
                $textWizard = $wizardFactory->newRule(Wizard::TEXT_VALUE);

                $textWizard->contains("Completed")->setStyle($greenStyle);
                $conditionalStyles[] = $textWizard->getConditional();

                $textWizard->contains("Incomplete")->setStyle($redStyle);
                $conditionalStyles[] = $textWizard->getConditional();

                $textWizard->contains(t("late"))->setStyle($redStyle);
                $conditionalStyles[] = $textWizard->getConditional();

                $event->sheet
                    ->getStyle($textWizard->getCellRange())
                    ->setConditionalStyles($conditionalStyles);
            },
        ];
    }
}

