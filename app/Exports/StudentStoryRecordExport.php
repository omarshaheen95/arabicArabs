<?php

namespace App\Exports;

use App\Models\StoryUserRecord;
use App\Models\StudentStoryTest;
use App\Models\StudentTest;
use App\Models\User;
use App\Models\UserRecord;
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

class StudentStoryRecordExport implements WithMapping,Responsable,WithHeadings,FromCollection,WithEvents,ShouldAutoSize
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
            'Story',
            'Story Grade',
            'Status',
            'Mark',
            'Show as model',
            'Submitted at',
        ];
    }

    public function map($student): array
    {
        return [
            $student->user->name,
            $student->user->email,
            $student->user->grade->name,
            $student->user->school->name,
            $student->story->name,
            $student->story->grade_name,
            $student->status_name,
            $student->mark,
            $student->approved ? t('Yes'): t('No'),
            Carbon::parse($student->created_at)->toDateTimeString(),

        ];
    }

    public function collection()
    {
        $students = StoryUserRecord::query()->with(['story', 'user.school', 'user.grade'])->filter($this->request)->latest();
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

