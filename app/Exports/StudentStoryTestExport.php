<?php

namespace App\Exports;

use App\Models\StudentStoryTest;
use App\Models\StudentTest;
use App\Models\User;
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

class StudentStoryTestExport implements WithMapping,Responsable,WithHeadings,FromCollection,WithEvents,ShouldAutoSize
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
            'Story',
            'Story Grade',
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
            $student->user->grade->name,
            $student->story->name,
            $student->story->grade_name,
            $student->total_per,
            $student->status,
            Carbon::parse($student->created_at)->toDateTimeString(),

        ];
    }

    public function collection()
    {
        $students = StudentStoryTest::query()->with(['story', 'user.school', 'user.grade'])->filter($this->request)->latest();
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

                $textWizard->contains("Pass")->setStyle($greenStyle);
                $conditionalStyles[] = $textWizard->getConditional();

                $textWizard->contains("Fail")->setStyle($redStyle);
                $conditionalStyles[] = $textWizard->getConditional();



                $event->sheet
                    ->getStyle($textWizard->getCellRange())
                    ->setConditionalStyles($conditionalStyles);
            },
        ];
    }
}

