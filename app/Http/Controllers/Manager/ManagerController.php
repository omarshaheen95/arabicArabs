<?php

namespace App\Http\Controllers\Manager;

use App\Exports\ManagerExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ManagerPasswordRequest;
use App\Http\Requests\Manager\ManagerProfileRequest;
use App\Http\Requests\Manager\ManagerRequest;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use App\Traits\UpdatesPassword;

class ManagerController extends Controller
{
    use UpdatesPassword;

    public function __construct()
    {
        $this->middleware('permission:edit managers')->only('forcePasswordChange');
        $this->middleware('permission:show managers')->only('index');
        $this->middleware('permission:add managers')->only(['create','store']);
        $this->middleware('permission:edit managers')->only(['edit','update']);
        $this->middleware('permission:delete managers')->only('destroy');
        $this->middleware('permission:export managers')->only('export');
        $this->middleware('permission:edit managers permissions')->only(['editPermissions','updatePermissions']);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = Manager::query()->with(['login_sessions' => function ($query) {
                $query->latest()->limit(1);
            }])->filter()->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('email', function ($row) {
                    return '<span class="cursor-pointer" style="direction: ltr" data-clipboard-text="'.$row->email.'" onclick="copyToClipboard(this)">' . $row->email . '</span>';
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
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
                ->addColumn('active', function ($row) {
                    return $row->active ? '<div class="badge badge-primary">'.t('Active').'</div>' : '<div class="badge badge-warning">'.t('Non-Active').'</div>';
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Managers');
        return view('manager.managers.index', compact('title'));
    }

    public function create()
    {
        $title = t('Create Manager');
        return view('manager.managers.edit', compact('title'));
    }

    public function store(ManagerRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request->get('password', 123456));
        $data['force_password_change'] = $request->get('force_password_change', 0);
        $data['password_changed_at'] = now();
        $data['active'] = $request->get('active', 0);
        Manager::query()->create($data);
        return redirect()->route('manager.manager.index')->with('message', t('Successfully Created'));
    }

    public function edit($id)
    {
        $title = t('Edit Manager');
        $manager = Manager::query()->findOrFail($id);
        return view('manager.managers.edit', compact('title', 'manager'));
    }

    public function update(ManagerRequest $request, $id)
    {
        $manager = Manager::query()->findOrFail($id);
        $data = $request->validated();
        $data['active'] = $request->get('active', 0);
        $password_changed = (bool) $request->get('password', false);
        $data['password'] = $password_changed ? bcrypt($request->get('password')) : $manager->password;
        if ($password_changed) {
            $data['password_changed_at'] = now();
        }
        // a password handed over by a manager is temporary by default
        $data['force_password_change'] = $request->get('force_password_change', $password_changed ? 1 : 0);
        $manager->update($data);
        return redirect()->route('manager.manager.index')->with('message', t('Successfully Updated'));
    }

    public function editPermissions($id)
    {
        $title = t('Edit Permissions');
        $manager_id = $id;
        $permissions = Permission::query()->where('guard_name','manager')
            ->get()->groupBy('group');
        $manager_permissions = \DB::table('model_has_permissions')->where('model_id',$id)->get();

        //dd($manager_permissions);
        return view('manager.managers.permissions', compact('title', 'permissions','manager_permissions','manager_id'));
    }

    public function updatePermissions(Request $request,$id)
    {
        $request->validate(['permissions'=>'nullable']);

        $manager = Manager::query()->findOrFail($id);
        $manager->syncPermissions($request->get('permissions'));

        return redirect()->route('manager.manager.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        Manager::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Successfully Deleted'));
    }

    public function export(Request $request)
    {
        return (new ManagerExport($request))->download('Managers Details.xlsx');
    }

    public function editProfile()
    {
        $title = t('Edit Profile');
        return view('manager.managers.profile', compact('title'));
    }
    public function updateProfile(ManagerProfileRequest $request)
    {
        $data = $request->validated();
        $manager = Auth::guard('manager')->user();
        $manager->update($data);
        return redirect()->back()->with('message', t('Successfully Updated'));
    }
    public function editPassword()
    {
        $title = t('Edit Password');
        $reason = $this->passwordChangeReason('manager');
        $forced = $reason !== null;
        return view('manager.managers.password', compact('title', 'forced', 'reason'));
    }
    public function updatePassword(ManagerPasswordRequest $request)
    {
        return $this->applyPasswordUpdate($request, 'manager');
    }

    /**
     * Raise the forced password change flag on the accounts the table is
     * currently showing. Same contract as the export: the filters come from the
     * #filter form and row_id narrows it down to the checked rows.
     */
    public function forcePasswordChange(Request $request)
    {
        $query = Manager::query()->filter($request);

        // never lock yourself out of the panel with a bulk action
        $query->where('id', '!=', Auth::guard('manager')->id());

        // count the matched rows, not the changed ones: MySQL does not report a
        // row that already carried the flag
        $affected = (clone $query)->count();
        $query->update(['force_password_change' => 1]);

        return $this->sendResponse(['affected' => $affected],
            t('Password change was enforced on :count account(s).', ['count' => $affected]));
    }
}
