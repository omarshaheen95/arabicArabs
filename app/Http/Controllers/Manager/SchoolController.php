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
                        '<div class="d-flex text-danger">'.'<span style="direction: ltr">'.$row->email.'</span></div>'.
                        '</div>';
                })
                ->addColumn('name', function ($row){
                    return $row->name;
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
            $data['logo'] = $this->uploadFile($request->file('logo'), 'schools');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = 1;
        $data['password'] = bcrypt($request->get('password', 123456));
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
            $data['logo'] = $this->uploadFile($request->file('logo'), 'schools');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)):$school->password;
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
            return $this->sendResponse(null,t('Updated Successfully : '.$update));
        }
        return $this->sendResponse(null,t('Successfully Updated'));
    }

}
