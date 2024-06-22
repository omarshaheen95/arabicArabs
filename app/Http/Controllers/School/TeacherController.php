<?php

namespace App\Http\Controllers\School;

use App\Classes\GeneralFunctions;
use App\Events\NewTeacherEvent;
use App\Exports\TeacherExport;
use App\Exports\TeacherStatisticsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\TeacherRequest;
use App\Models\School;
use App\Models\UserTest;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTracker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {

            $rows = Teacher::with('school','students')->withCount('students')->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('teacher', function ($row){
                    return '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.'<span class="fw-bold me-1">'.t('ID').' : </span>'.$row->id.'</div>'.
                        '<div class="d-flex fw-bold">'.'<span class="fw-bold me-1">'.t('Name').' : </span>'.$row->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">'.t('Mobile').' : </span><span style="direction: ltr">'.$row->mobile.'</span></div>'.
                        '<div class="d-flex text-danger">'.'<span class="fw-bold text-primary me-1">'.t('Email').' : </span>'.'<span style="direction: ltr">'.$row->email.'</span></div>'.
                        '</div>';
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login?Carbon::parse($row->last_login)->toDateTimeString():'';
                })
                ->addColumn('active_to', function ($row) {
                    return $row->active_to;
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">'.t('Active').'</span>' : '<span class="badge badge-danger">'.t('Inactive').'</span>';
                })
                ->addColumn('approved', function ($row) {
                    return $row->approved ? '<span class="badge badge-primary">'.t('Approved').'</span>' : '<span class="badge badge-warning">'.t('Under review').'</span>';
                })->addColumn('students_count', function ($row) {
                    return $row->students_count;
                })
                ->addColumn('school', function ($row) {
                    return $row->school->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show teachers');
        return view('school.teacher.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add teacher');
        return view('school.teacher.edit', compact('title'));
    }

    public function store(TeacherRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'teachers');
        }
        $school = Auth::guard('school')->user();
        $data['school_id'] = $school->id;
        $data['active'] = $request->get('active', 0);
        $data['approved'] = 0;
        $data['password'] = bcrypt($request->get('password', 123456));
        $teacher = Teacher::create($data);

//        event(new NewTeacherEvent($teacher));
        $message = "$school->name added new teacher ($teacher->name) check it form follow link: " . route('manager.teacher.edit', $teacher->id);
//        supportMessage($message);
        return redirect()->route('school.teacher.index')->with('message', t('Successfully Added'));
    }

    public function edit(Request $request,$id)
    {
        $title = t('Edit teacher');
        $teacher = Teacher::query()->filter($request)->findOrFail($id);
        $schools = School::query()->get();
        return view('school.teacher.edit', compact('title', 'teacher', 'schools'));
    }

    public function update(TeacherRequest $request, $id)
    {
        $teacher = Teacher::query()->filter($request)->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'teachers');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $teacher->password;
        $teacher->update($data);
        return redirect()->route('school.teacher.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        Teacher::query()->filter($request)->delete();
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function teachersStatistics(Request $request)
    {
        if (request()->ajax()) {
            $rows = Teacher::query()->withCount(['students'])->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login?Carbon::parse($row->last_login)->toDateTimeString():'';
                })
                ->addColumn('teacher', function ($row) {
                    return '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>' .
                        '<div class="d-flex text-danger">' . '<span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Students') . ' : </span><span style="direction: ltr">' . $row->students_count . '</span></div>' .
                        '</div>';
                })
                ->addColumn('actions', function ($row) {
                    $actions = [['key'=>'blank','name'=>t('Report'),'route'=>route('school.teacher.statistics_report', $row->id)],];
                    return view('general.action_menu')->with('actions',$actions);
                })
                ->make();
        }
        $title = t('Show teachers statistics');
        return view('school.teacher.statistics', compact('title'));
    }

    public function teachersStatisticsExport(Request $request)
    {
        return (new TeacherStatisticsExport($request, Auth::guard('school')->user()->id))
            ->download('Teachers statistics.xlsx');
    }

    public function teachersStatisticsReport(Request $request,$id)
    {
        $general =  new GeneralFunctions();
        return $general->teacherReport($request,$id);
    }

    public function teacherExport(Request $request)
    {
        $school = Auth::guard('school')->user();
        return (new TeacherExport($request, $school->id))
            ->download('Teachers Information.xlsx');
    }

}
