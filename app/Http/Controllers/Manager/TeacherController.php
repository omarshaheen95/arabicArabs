<?php

namespace App\Http\Controllers\Manager;

use App\Classes\GeneralFunctions;
use App\Exports\TeacherExport;
use App\Exports\TeacherStatisticsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\TeacherRequest;
use App\Models\School;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\UserTracker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show teachers')->only('index');
        $this->middleware('permission:add teachers')->only(['create','store']);
        $this->middleware('permission:edit teachers')->only(['edit','update']);
        $this->middleware('permission:delete teachers')->only('destroy');
        $this->middleware('permission:export teachers')->only('export');
        $this->middleware('permission:teacher login')->only('login');
        $this->middleware('permission:teachers activation')->only('activation');
        $this->middleware('permission:teacher users unsigned')->only('usersUnsigned');
        $this->middleware('permission:teacher tracking')->only(['teachersTracking','teachersTrackingExport']);
        $this->middleware('permission:teacher tracking report')->only(['teachersTrackingReport']);
    }


    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = Teacher::query()->with(['school','students'])->withCount('users')->filter($request)->withCount('students')->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                })
                ->addColumn('teacher', function ($row) {
                    return '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Mobile') . ' : </span><span style="direction: ltr">' . $row->mobile . '</span></div>' .
                        '<div class="d-flex text-danger">' . '<span style="direction: ltr">' . $row->email . '</span></div>' .
                        '</div>';
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">' . t('Active') . '</span>' : '<span class="badge badge-danger">' . t('Inactive') . '</span>';
                })
                ->addColumn('approved', function ($row) {
                    return $row->approved ? '<span class="badge badge-primary">' . t('Approved') . '</span>' : '<span class="badge badge-warning">' . t('Under review') . '</span>';
                })
                ->addColumn('school', function ($row) {
                    return '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . optional($row->school)->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Students') . ' : </span><span style="direction: ltr">' . $row->students_count . '</span></div>' .
                        '</div>';
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->addColumn('active_to', function ($row) {
                    return $row->active_to ? Carbon::parse($row->active_to)->toDateString() : '-';
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                })
                ->make();
        }
        $title = t('Show teachers');
        $schools = School::query()->get();
        return view('manager.teacher.index', compact('title', 'schools'));
    }

    public function create()
    {
        $title = t('Add teacher');
        $schools = School::query()->get();
        return view('manager.teacher.edit', compact('title', 'schools'));
    }

    public function store(TeacherRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'teachers');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = 1;
        $data['password'] = bcrypt($request->get('password', 123456));
        Teacher::create($data);
        return redirect()->route('manager.teacher.index')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = t('Edit teacher');
        $teacher = Teacher::query()->findOrFail($id);
        $schools = School::query()->get();
        return view('manager.teacher.edit', compact('title', 'teacher', 'schools'));
    }

    public function update(TeacherRequest $request, $id)
    {
        $teacher = Teacher::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('teacher')) {
            $data['teacher'] = $this->uploadFile($request->file('teacher'), 'teachers');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = $request->get('approved', $teacher->approved);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $teacher->password;
        $teacher->update($data);
        return redirect()->route('manager.teacher.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        Teacher::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Successfully Deleted'));
    }

    public function activation(Request $request)
    {
        $data = [];
        $activation_data = $request->get('activation_data', false);
        if ($activation_data) {
            if ($activation_data['active']) {
                $data['active'] = $activation_data['active'] != 2;
            }
            if ($activation_data['approved']) {
                $data['approved'] = $activation_data['approved'] != 2;
            }
            if ($activation_data['active_to']) {
                $data['active_to'] = Carbon::parse($activation_data['active_to'])->toDateTime();
            }
        }

        if (count($data)) {
            $update = Teacher::query()->filter($request)->update($data);
            return $this->sendResponse(null, t('Updated Successfully :' . $update));
        }
        return $this->sendResponse(null, t('No teachers updated'));

    }
    public function usersUnsigned(Request $request)
    {
        $request->validate([
            'delete_students' => 'required|array',
            'delete_students.type' => 'required',
        ], [
            'delete_students.type.required' => t('Please select type'),
        ]);
        $teachers = Teacher::query()->filter($request)->get();
        $type = $request->get('delete_students')['type'];
        foreach ($teachers as $teacher) {
            if ($type == 1) {
                $teacher->users()->delete();
            } elseif ($type == 2) {
                $teacher->users()->where('active_to', '>=', Carbon::now())->delete();
            } elseif ($type == 3) {
                $teacher->users()->where('active_to', '<', Carbon::now())->delete();
            }
        }
        return $this->sendResponse(null, t('Students Unsigned Successfully'));
    }

    public function export(Request $request)
    {
        return (new TeacherExport($request))->download('Teachers Information.xlsx');
    }

    public function login($id)
    {
        $user = Teacher::query()->findOrFail($id);
        Auth::guard('teacher')->loginUsingId($id);
        return redirect()->route('teacher.home');
    }



    public function teachersTracking(Request $request)
    {
        if (request()->ajax()) {
            $rows = Teacher::query()->with(['school'])->withCount(['students'])->filter($request)->latest();
            $has_permission = Auth::guard('manager')->user()->hasDirectPermission('teacher tracking report');
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('teacher', function ($row) {
                    return '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>' .
                        '<div class="d-flex text-danger">' . '<span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('School') . ' : </span> ' . optional($row->school)->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Students') . ' : </span><span style="direction: ltr">' . $row->students_count . '</span></div>' .
                        '</div>';
                })
                ->addColumn('actions', function ($row) use ($has_permission) {
                    $actions = [];
                    if ($has_permission){
                        $actions[]= ['key' => 'blank', 'name' => t('Report'), 'route' => route('manager.teacher.tracking_report', $row->id)];
                    }
                    return view('general.action_menu')->with('actions', $actions);
                })
                ->make();
        }
        $title = t('Show tracking teachers');
        $schools = School::query()->get();
        return view('manager.teacher.tracking', compact('title', 'schools'));
    }

    public function teachersTrackingExport(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new TeacherStatisticsExport($request))->download('Teachers statistics.xlsx');
    }

    public function teachersTrackingReport(Request $request, $id)
    {
       $general =  new GeneralFunctions();
       return $general->teacherReport($request,$id);
    }



}
