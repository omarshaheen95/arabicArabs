<?php

namespace App\Repositories;

use App\Classes\GeneralFunctions;
use App\Exports\TeacherExport;
use App\Exports\TeacherStatisticsExport;
use App\Helpers\Response;
use App\Http\Requests\General\TeacherRequest;
use App\Interfaces\TeacherRepositoryInterface;
use App\Models\School;
use App\Models\StudentTest;
use App\Models\Supervisor;
use App\Models\Teacher;
use App\Models\TeacherStudent;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTracker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;


class TeacherRepository implements TeacherRepositoryInterface
{


    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = Teacher::query()->with(['school','login_sessions'])->withCount(['students' => function ($q)
            {
                $q->whereHas('user');
            }
            ])->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('teacher', function ($row) {
                    return '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span class="cursor-pointer" style="direction: ltr" data-clipboard-text="'.$row->email.'" onclick="copyToClipboard(this)">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Mobile') . ' : </span><span style="direction: ltr">' . $row->mobile . '</span></div>' .
                        '</div>';
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">' . t('Active') . '</span>' : '<span class="badge badge-danger">' . t('Inactive') . '</span>';
                })
                ->addColumn('role', function ($row) {
                    $roles = '<div class="d-flex  gap-1">';
                    if ($row->roles->count()>0){
                        foreach ($row->roles as $role){
                            $roles .= '<span class="badge badge-info">'.$role->name.'</span>';
                        }
                    }else{
                        $roles .= '<span class="badge badge-warning">'.t('No Role').'</span>';
                    }

                    $roles .= '</div>';
                    return $roles;
                })
                ->addColumn('approved', function ($row) {
                    return $row->approved ? '<span class="badge badge-primary">' . t('Approved') . '</span>' : '<span class="badge badge-warning">' . t('Under review') . '</span>';
                })
                ->addColumn('school', function ($row) {
                    $html = '<div class="d-flex flex-column">';
                    if (guardIs('manager')){
                        $html .= '<div class="d-flex fw-bold">' . optional($row->school)->name . '</div>';
                    }
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Students') . ' : </span><span style="direction: ltr">' . $row->students_count . '</span></div>';
                    $html .= '</div>';
                    return $html;

                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->addColumn('active_to', function ($row) {
                    return $row->active_to ? Carbon::parse($row->active_to)->toDateString() : '';
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count()>0 ? Carbon::parse($row->login_sessions->last()->created_at)->format('Y-m-d H:i') : '';
                })
                ->make();

        }
        $title = t('Show teachers');
        $compact = compact('title');
        $compact['schools'] = School::query()->get();
        return view('general.teacher.index', $compact);
    }

    public function create()
    {
        $title = t('Add teacher');
        $compact = compact('title');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }
        return view('general.teacher.edit', $compact);
    }

    public function store(TeacherRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'teachers')['path'];
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = $request->get('approved', 0);
        $data['password'] = bcrypt($request->get('password', 123456));
        $data['force_password_change'] = $request->get('force_password_change', 0);
        $data['password_changed_at'] = now();
        Teacher::create($data);
        return redirect()->route(getGuard().'.teacher.index')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = t('Edit teacher');
        $teacher = Teacher::query()->filter()->findOrFail($id);
        $compact = compact('title','teacher');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }
        return view('general.teacher.edit', $compact);
    }

    public function update(TeacherRequest $request, $id)
    {
        $data = $request->validated();
        $teacher = Teacher::query()
            ->when($request->get('school_id'), function (Builder $query) use ($request) {
            $query->where('school_id', $request->get('school_id'));
        })->findOrFail($id);

        if ($request->hasFile('teacher')) {
            $data['teacher'] = uploadFile($request->file('teacher'), 'teachers')['path'];
        }
        $data['active'] = $request->get('active', 0);
        if (guardIs('manager')){
            $data['approved'] = $request->get('approved', 0);
        }
        $password_changed = (bool) $request->get('password', false);
        $data['password'] = $password_changed ? bcrypt($request->get('password')) : $teacher->password;
        if ($password_changed) {
            $data['password_changed_at'] = now();
        }
        // a password handed over by a manager or a school is temporary by default
        $data['force_password_change'] = $request->get('force_password_change', $password_changed ? 1 : 0);
        $teacher->update($data);
        return redirect()->route(getGuard().'.teacher.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        $teachers = Teacher::query()->when($request->get('school_id'), function (Builder $query) use ($request) {
            $query->where('school_id', $request->get('school_id'));
        })->whereIn('id', $request->get('row_id'))->get();
        foreach ($teachers as $teacher){
            $teacher->delete();
        }
        return Response::response([Response::DELETED_SUCCESSFULLY]);
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
            return Response::response( t('Updated Successfully :' ). $update);
        }
        return Response::response(t('No teachers updated'));


    }

    public function exportTeachersExcel(Request $request)
    {
        $name = 'Teachers Information.xlsx';
        if ($request->get('school_id', false))
        {
            $school = School::query()->findOrFail($request->get('school_id'));
            $name =  $school->name . ' Teachers Information.xlsx';
        }
        return (new TeacherExport($request))->download($name);
    }

    public function login($id)
    {
        $user = Teacher::query()->when(\request()->get('school_id'), function (Builder $query) {
            $query->where('school_id', \request()->get('school_id'));
        })->findOrFail($id);
        Auth::guard('teacher')->loginUsingId($id);
        \App\Http\Middleware\ForcePasswordChange::impersonate('teacher', $id);
        return redirect()->route('teacher.home');
    }

    public function deleteStudents(Request $request)
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
                $teacher->students()->delete();
            } elseif ($type == 2) {
                $teacher->students()->where('active_to', '>=', Carbon::now())->delete();
            } elseif ($type == 3) {
                $teacher->students()->where('active_to', '<', Carbon::now())->delete();
            }
        }
        return Response::response(t('Students Unsigned Successfully'));
    }

    public function teachersTracking(Request $request)
    {
        if (request()->ajax()) {
            $rows = Teacher::query()->with(['school','login_sessions'])->withCount(['students'])->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('teacher', function ($row) {
                        $html ='<div class="d-flex flex-column">' ;
                        $html.='<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>' ;
                        $html.='<div class="d-flex text-danger">' . '<span style="direction: ltr">' . $row->email . '</span></div>' ;
                        if (guardIs('manager'))
                        {
                            $html.= '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('School') . ' : </span> ' . optional($row->school)->name . '</div>';
                        }
                        $html.='<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('Students') . ' : </span><span style="direction: ltr">' . $row->students_count . '</span></div>';
                        $html.='</div>';
                    return $html;

                })
                ->addColumn('tests', function ($row) {
                    $html ='<div class="d-flex flex-column gap-1">' ;
                    $html.='<div class="fw-bold">' . '<span class="fw-bold badge badge-success me-1">' . t('Passed') . '  </span>: ' . $row->passed_tests . '</div>' ;
                    $html.='<div class="fw-bold">' . '<span class="fw-bold badge badge-danger me-1">' . t('Failed') . '  </span>: ' . $row->failed_tests . '</div>' ;
                    $html.='</div>';
                    return $html;
                })->addColumn('tasks', function ($row) {
                    $html ='<div class="d-flex flex-column gap-1">' ;
                    $html.='<div class="fw-bold">' . '<span class="fw-bold badge badge-warning me-1">' . t('Pending') . '  </span>: ' . $row->pending_tasks . '</div>' ;
                    $html.='<div class="fw-bold">' . '<span class="fw-bold badge badge-success me-1">' . t('Completed') . '  </span>: ' . $row->corrected_tasks . '</div>' ;
                    $html.='<div class="fw-bold">' . '<span class="fw-bold badge badge-danger me-1">' . t('Returned') . '  </span>: ' . $row->returned_tasks . '</div>' ;
                    $html.='</div>';
                    return $html;
                })->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count()>0 ? Carbon::parse($row->login_sessions->last()->created_at)->format('Y-m-d H:i') : '';
                })->addColumn('actions', function ($row) {
                    return '<a class="btn btn-sm btn-success" href="'.route(getGuard().'.teacher.tracking_report', $row->id).'" target="_blank">'.t('Report').'</a>';
                })
                ->make();
        }
        $title = t('Teachers Tracking');
        $compact = compact('title');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }
        return view('general.teacher.tracking', $compact);
    }

    public function teachersTrackingExport(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new TeacherStatisticsExport($request, $request->get('school_id')))
            ->download('Teachers statistics.xlsx');
    }

    public function teachersTrackingReport(Request $request, $id)
    {
        $general =  new GeneralFunctions();
        return $general->teacherReport($request,$id);
    }

    public function resetPasswords(Request $request)
    {
        $request->validate(['password'=>'required|string']);
        $password = $request->get('password');
        $update = $this->scopedTeachers()->filter()->update([
            'password' => bcrypt($password),
            'password_changed_at' => now(),
            // a password handed over in bulk is temporary: the account
            // is sent to the change screen on its next sign in
            'force_password_change' => 1,
        ]);
        return Response::response(t('Password Reset Successfully').': '.$password.' for ('.$update.') '.t('teacher'));
     }

    /**
     * Raise the forced password change flag on the teachers the table is
     * currently showing. Same contract as the export: the filters come from the
     * #filter form and row_id narrows it down to the checked rows.
     */
    public function forcePasswordChange(Request $request)
    {
        $query = $this->scopedTeachers()->filter($request);

        // count the matched rows, not the changed ones: MySQL does not report a
        // row that already carried the flag
        $affected = (clone $query)->count();
        $query->update(['force_password_change' => 1]);

        return response()->json([
            'status' => true,
            'message' => t('Password change was enforced on :count account(s).', ['count' => $affected]),
            'data' => ['affected' => $affected],
        ]);
    }

    /**
     * Base query for teacher wide writes.
     *
     * The manager works across the platform, a school only over its own staff.
     * The listing does not enforce this today, so anything that writes has to
     * pin it here rather than trust the incoming filters.
     */
    protected function scopedTeachers()
    {
        $query = Teacher::query();

        if (getGuard() === 'school' && ($school = Auth::guard('school')->user())) {
            $query->where('school_id', $school->id);
        }

        return $query;
    }
}
