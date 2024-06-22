<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Teacher;

use App\Exports\MotivationalCertificateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\MotivationalCertificateRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\MotivationalCertificate;
use App\Models\School;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MotivationalCertificateController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $rows = MotivationalCertificate::query()
                ->with(['model','user.grade'])
                ->filter($request)->with(['user', 'model'])
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('student', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.$row->user->name.'</div>'.
                        '<div class="d-flex text-danger"><span style="direction: ltr">'.$row->user->email.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->user->grade->name.'<span class="fw-bold ms-2 text-primary pe-1">'.t('Section').':</span>'.$row->user->section.'</div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('dates', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-success pe-1">'.t('Granted In').':</span>'.$row->granted_in.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Created At').':</span>'.Carbon::parse($row->created_at)->toDateString().'</div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('model', function ($row) {
                    if ($row->model_type == Lesson::class)
                    {
                        $html =  '<div class="d-flex flex-column">'.
                            '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.optional($row->model)->grade->name.'</div>'.
                            '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Lesson').':</span>'.optional($row->model)->name.'</div>'.
                            '</div>';
                        return $html;
                    }else{
                        $html =  '<div class="d-flex flex-column">'.
                            '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Level').':</span>'.optional($row->model)->grade_name.'</div>'.
                            '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Story').':</span>'.optional($row->model)->name.'</div>'.
                            '</div>';
                        return $html;
                    }
                })

                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Motivational Certificates');
        $grades = Grade::query()->get();
        return view('teacher.motivational_certificates.index', compact('title', 'grades'));
    }

    public function create()
    {
        $title = t('Add Certificate');
        $cer_type = request()->get('cer_type', 'lesson');
        $grades = Grade::query()->get();
        return view('teacher.motivational_certificates.edit', compact('title', 'grades','cer_type'));
    }

    public function store(MotivationalCertificateRequest $request)
    {
        $data = $request->validated();
        if ($data['model_type'] == 'lesson') {
            $items = $data['lesson_id'];
            $data['model_type'] = Lesson::class;
        } else {
            $items = $data['story_id'];
            $data['model_type'] = Story::class;
        }
        //remove all from array
        if (in_array('all', $data['students'])) {
            //remove of all from array
            $data['students'] = User::query()->filter(request())->pluck('id')->toArray();
        }
        foreach ($data['students'] as $student)
        {
            foreach ($items as $item) {
                MotivationalCertificate::query()->firstOrCreate([
                    'teacher_id' => $request->get('teacher_id'),
                    'user_id' => $student,
                    'model_type' => $data['model_type'],
                    'model_id' => $item,
                ],[
                    'teacher_id' => $request->get('teacher_id'),
                    'user_id' => $student,
                    'model_type' => $data['model_type'],
                    'model_id' => $item,
                    'granted_in' => $data['granted_in'],
                ]);
            }

        }
        return redirect()->route('teacher.motivational_certificate.index')->with('message', t('Successfully Added'));
    }

    public function show($id)
    {
        $title = t('Show Certificate');
        $certificate = MotivationalCertificate::query()->filter(request())->findOrFail($id);
        return view('general.user.motivational_certificate', compact('certificate', 'title'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        MotivationalCertificate::destroy($request->get('row_id'));
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'model_type' => 'required',
            'school_id' => 'required',
        ]);
        return (new MotivationalCertificateExport($request))->download('Motivational Certificates.xlsx');
    }

}
