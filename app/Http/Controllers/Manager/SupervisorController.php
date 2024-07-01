<?php

namespace App\Http\Controllers\Manager;

use App\Exports\SupervisorExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\SupervisorRequest;
use App\Models\School;
use App\Models\Supervisor;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show supervisors')->only('index');
        $this->middleware('permission:add supervisors')->only(['create','store']);
        $this->middleware('permission:edit supervisors')->only(['edit','update']);
        $this->middleware('permission:delete supervisors')->only('destroy');
        $this->middleware('permission:export supervisors')->only('export');
        $this->middleware('permission:supervisors activation')->only(['activation']);
    }

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = Supervisor::query()->withCount(['supervisor_teachers'])->with(['school'])->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                })
                ->addColumn('supervisor_data', function ($row){
                    return '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.'<span class="fw-bold me-1">'.t('Name').' : </span>'.$row->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary me-1">'.t('School').' : </span><span style="direction: ltr">'.optional($row->school)->name.'</span></div>'.
                        '<div class="d-flex text-danger">'.'<span style="direction: ltr">'.$row->email.'</span></div>'.
                        '</div>';
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">'.t('Active').'</span>' : '<span class="badge badge-danger">'.t('Inactive').'</span>';
                })
                ->addColumn('approved', function ($row) {
                    return $row->approved ? '<span class="badge badge-primary">'.t('Approved').'</span>' : '<span class="badge badge-warning">'.t('Under review').'</span>';
                })
                ->addColumn('teachers_count', function ($row) {
                    return $row->supervisor_teachers_count;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show supervisors');
        $schools = School::query()->get();
        return view('manager.supervisor.index', compact('title', 'schools'));
    }

    public function create()
    {
        $title = t('Add supervisor');
        $schools = School::query()->get();
        $teachers = [];
        return view('manager.supervisor.edit', compact('title', 'schools', 'teachers'));
    }

    public function store(SupervisorRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'supervisors');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = $request->get('approved', 0);
        $data['password'] = bcrypt($request->get('password', 123456));

        $supervisor = Supervisor::query()->create($data);
        $supervisor->teachers()->sync($request->get('teachers', []));
        return redirect()->route('manager.supervisor.index')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = t('Edit Supervisor');
        $supervisor = Supervisor::query()->with(['supervisor_teachers'])->findOrFail($id);
        $schools = School::query()->get();
        $teachers = Teacher::query()->where('school_id', $supervisor->school_id)->get();
        return view('manager.supervisor.edit', compact('title', 'teachers', 'supervisor', 'schools'));
    }

    public function update(SupervisorRequest $request, $id)
    {
        $supervisor = Supervisor::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'supervisors');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = $request->get('approved', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $supervisor->password;
        $supervisor->update($data);
        $supervisor->teachers()->sync($request->get('teachers', []));
        return redirect()->route('manager.supervisor.index')->with('message', t('Successfully Updated'));
    }

    public function login($id)
    {
        Supervisor::query()->findOrFail($id);
        Auth::guard('supervisor')->loginUsingId($id);
        return redirect()->route('supervisor.home');
    }

    public function export(Request $request)
    {
        return (new SupervisorExport($request))->download('Supervisors Information.xlsx');
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        Supervisor::destroy($request->get('row_id'));
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function activation(Request $request)
    {
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
            return $this->sendResponse(null,t('Updated Successfully : '.$update));
        }
        return $this->sendResponse(null,t('Successfully Updated'));
    }
}
