<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Repositories;

use App\Exports\SupervisorExport;
use App\Helpers\Response;
use App\Http\Requests\General\SupervisorRequest;
use App\Interfaces\SupervisorRepositoryInterface;
use App\Models\School;
use App\Models\Supervisor;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;


class SupervisorRepository implements SupervisorRepositoryInterface
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = Supervisor::query()->withCount(['supervisor_teachers'])->with(['school'])->filter($request)->latest();
            return \Yajra\DataTables\DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('supervisor_data', function ($row){
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>';
                    if (getGuard() == 'manager') {
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('School') . ' : </span><span style="direction: ltr">' . optional($row->school)->name . '</span></div>';
                    }
                    $html .= '<div class="d-flex text-danger"><span class="cursor-pointer" style="direction: ltr" data-clipboard-text="' . $row->email . '" onclick="copyToClipboard(this)">' . $row->email . '</span></div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">'.t('Active').'</span>' : '<span class="badge badge-danger">'.t('Inactive').'</span>';
                })
                ->addColumn('approved', function ($row) {
                    return $row->approved ? '<span class="badge badge-primary">'.t('Approved').'</span>' : '<span class="badge badge-warning">'.t('Under review').'</span>';
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

                ->addColumn('teachers_count', function ($row) {
                    return $row->supervisor_teachers_count;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Supervisors');
        $compact = compact('title');
        if (guardIs('manager')){
            $compact['schools']  = School::query()->get();
        }
        return view('general.supervisor.index', $compact);
    }

    public function create()
    {
        $title = t('Add supervisor');
        $compact = compact('title');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }
        if (guardIs('school')){
            $compact['teachers'] = Teacher::query()->where('school_id',Auth::guard('school')->id())->get();
        }

        return view('general.supervisor.edit',$compact);
    }

    public function store(SupervisorRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'supervisors')['path'];
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = bcrypt($request->get('password', 123456));
        $data['force_password_change'] = $request->get('force_password_change', 0);
        $data['password_changed_at'] = now();

        if (guardIs('manager')){
            $data['approved'] = $request->get('approved', 0);
        }
        //$data['teachers'] = [];
        $supervisor = Supervisor::query()->create($data);

        if (count($request->get('teachers', []))) {
            $supervisor->teachers()->sync($request->get('teachers', []));
        }
        return redirect()->route(getGuard().'.supervisor.index')->with('message', t('Successfully Added'));
    }

    public function edit(Request $request,$id)
    {
        $title = t('Edit Supervisor');
        $supervisor = Supervisor::query()->with(['supervisor_teachers'])->findOrFail($id);

        $compact = compact('title','supervisor');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }
        $compact['teachers'] = Teacher::query()->where('school_id',$supervisor->school_id)->get();

        return view('general.supervisor.edit', $compact);
    }

    public function update(SupervisorRequest $request, $id)
    {
        $supervisor = Supervisor::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'supervisors')['path'];
        }
        $data['active'] = $request->get('active', 0);
        $password_changed = (bool) $request->get('password', false);
        $data['password'] = $password_changed ? bcrypt($request->get('password')) : $supervisor->password;
        if ($password_changed) {
            $data['password_changed_at'] = now();
        }
        // a password handed over by a manager or a school is temporary by default
        $data['force_password_change'] = $request->get('force_password_change', $password_changed ? 1 : 0);
        if (guardIs('manager')){
            $data['approved'] = $request->get('approved', 0);
        }
        $supervisor->update($data);
        $supervisor->teachers()->sync($request->get('teachers', []));
        return redirect()->route(getGuard().'.supervisor.index')->with('message', t('Successfully Updated'));
    }

    public function login($id)
    {
        Supervisor::query()->findOrFail($id);
        Auth::guard('supervisor')->loginUsingId($id);
        \App\Http\Middleware\ForcePasswordChange::impersonate('supervisor', $id);
        return redirect()->route('supervisor.home');
    }

    public function export(Request $request)
    {
        return (new SupervisorExport($request))->download('Supervisors Information.xlsx');
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        $supervisors = Supervisor::query()->whereIn('id',$request->get('row_id'))->get();
        foreach ($supervisors as $supervisor){
            $supervisor->delete();
        }
        return Response::response([Response::SUCCESS]);
    }

    public function activation(Request $request)
    {
        if (getGuard()!='manager'){
            return Response::response(t('You are not authorized to do that'));
        }
        $data = [];
        $activation_data = $request->get('activation_data',false);
        if ($activation_data){
            if ($activation_data['active']){
                $data['active'] = $activation_data['active']!=2;
            }
            if ($activation_data['approved']){
                $data['approved'] = $activation_data['approved']!=2;
            }
        }

        if (count($data)){
            $update = Supervisor::query()->filter($request)->update($data);
            return Response::response(t('Updated Successfully : ') .$update);
        }
        return Response::response(t('Successfully Updated'));
    }


    public function resetPasswords(Request $request)
    {
        $request->validate(['password'=>'required|string']);
        $password = $request->get('password');
        $update = $this->scopedSupervisors()->filter()->update([
            'password' => bcrypt($password),
            'password_changed_at' => now(),
            // a password handed over in bulk is temporary: the account
            // is sent to the change screen on its next sign in
            'force_password_change' => 1,
        ]);
        return Response::response(t('Password Reset Successfully').': '.$password.' for ('.$update.') '.t('supervisor'));
     }

    /**
     * Raise the forced password change flag on the supervisors the table is
     * currently showing. Same contract as the export: the filters come from the
     * #filter form and row_id narrows it down to the checked rows.
     */
    public function forcePasswordChange(Request $request)
    {
        $query = $this->scopedSupervisors()->filter($request);

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
     * Base query for supervisor wide writes.
     *
     * The manager works across the platform, a school only over its own staff.
     * The listing does not enforce this today, so anything that writes has to
     * pin it here rather than trust the incoming filters.
     */
    protected function scopedSupervisors()
    {
        $query = Supervisor::query();

        if (getGuard() === 'school' && ($school = Auth::guard('school')->user())) {
            $query->where('school_id', $school->id);
        }

        return $query;
    }
}
