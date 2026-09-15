<?php

namespace App\Http\Controllers\Manager;

use App\Exports\SchoolExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\SchoolRequest;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:edit schools')->only('forcePasswordChange');
        $this->middleware('permission:show schools')->only('index');
        $this->middleware('permission:add schools')->only(['create','store']);
        $this->middleware('permission:edit schools')->only(['edit','update']);
        $this->middleware('permission:delete schools')->only('destroy');
        $this->middleware('permission:export schools')->only('export');
        $this->middleware('permission:school login')->only('login');
        $this->middleware('permission:school activation')->only('activation');
    }
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $rows = School::query()->filter($request)->withCount(['students','teachers'])
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('school', function ($row){
                    return '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.'<span class="fw-bold me-1">'.t('Name').' : </span>'.$row->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">'.t('Mobile').' : </span><span style="direction: ltr">'.$row->mobile.'</span></div>'.
                        '<div class="d-flex text-danger"><span class="cursor-pointer" style="direction: ltr" data-clipboard-text="'.$row->email.'" onclick="copyToClipboard(this)">' . $row->email . '</span></div>' .
                        '</div>';
                })
                ->addColumn('name', function ($row){
                    return $row->name;
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
                ->addColumn('last_login', function ($row){
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">'.t('Active').'</span>' : '<span class="badge badge-warning">'.t('Inactive').'</span>';
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Schools');
        return view('manager.school.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add School');
        return view('manager.school.edit', compact('title'));
    }

    public function store(SchoolRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('logo'))
        {
            $data['logo'] = uploadFile($request->file('logo'), 'schools')['path'];
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = 1;
        $data['password'] = bcrypt($request->get('password', 123456));
        $data['force_password_change'] = $request->get('force_password_change', 0);
        $data['password_changed_at'] = now();
        $data['student_login'] = $request->get('student_login', false) ? 1 : 0;
        $data['suspend_student_login'] = $request->get('suspend_student_login', false) ? 1 : 0;

        School::create($data);
        return redirect()->route('manager.school.index')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = t('Edit School');
        $school = school::query()->findOrFail($id);
        return view('manager.school.edit', compact('title', 'school'));
    }

    public function update(SchoolRequest $request, $id)
    {
        $school = School::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('logo'))
        {
            $data['logo'] = uploadFile($request->file('logo'), 'schools')['path'];
        }
        $data['active'] = $request->get('active', 0);
        $password_changed = (bool) $request->get('password', false);
        $data['password'] = $password_changed ? bcrypt($request->get('password')) : $school->password;
        if ($password_changed) {
            $data['password_changed_at'] = now();
        }
        // a password handed over by a manager is temporary by default
        $data['force_password_change'] = $request->get('force_password_change', $password_changed ? 1 : 0);
        $data['student_login'] = $request->get('student_login', false) ? 1 : 0;
        $data['suspend_student_login'] = $request->get('suspend_student_login', false) ? 1 : 0;

        $school->update($data);
        return redirect()->route('manager.school.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        School::destroy($request->get('row_id'));
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function login($id)
    {
        $user = School::query()->findOrFail($id);
        Auth::guard('school')->loginUsingId($id);
        \App\Http\Middleware\ForcePasswordChange::impersonate('school', $id);
        return redirect()->route('school.home');
    }

    public function export(Request $request)
    {
        return (new SchoolExport($request))->download('Schools Information.xlsx');
    }

    public function activation(Request $request)
    {
        $data = [];
        $activation_data = $request->get('activation_data',false);
        if ($activation_data){
            if ($activation_data['active']){
                $data['active'] = $activation_data['active']!=2;
            }

        }

        if (count($data)){
            $update = School::query()->filter($request)->update($data);
            return $this->sendResponse(null,t('Updated Successfully').':'.$update);
        }
        return $this->sendResponse(null,t('Successfully Updated'));
    }

    /**
     * Raise the forced password change flag on the accounts the table is
     * currently showing. Same contract as the export: the filters come from the
     * #filter form and row_id narrows it down to the checked rows.
     */
    public function forcePasswordChange(Request $request)
    {
        $query = School::query()->filter($request);

        // count the matched rows, not the changed ones: MySQL does not report a
        // row that already carried the flag
        $affected = (clone $query)->count();
        $query->update(['force_password_change' => 1]);

        return $this->sendResponse(['affected' => $affected],
            t('Password change was enforced on :count account(s).', ['count' => $affected]));
    }
}
