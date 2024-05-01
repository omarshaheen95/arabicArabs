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
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = Supervisor::query()->with(['school'])->search($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('active', function ($row) {
                    return $row->active_status;
                })
                ->addColumn('status', function ($row) {
                    return $row->approved_status;
                })
                ->addColumn('school', function ($row) {
                    return $row->school->name;
                })
                ->addColumn('actions', function ($row) {
                    $edit_url = route('manager.supervisor.edit', $row->id);
                    $login_url = route('manager.supervisor.login', $row->id);
                    return view('manager.setting.btn_actions', compact('row', 'edit_url', 'login_url'));
                })
                ->make();
        }
        $title = "قائمة المشرفين";
        $schools = School::query()->get();
        return view('manager.supervisor.index', compact('title', 'schools'));
    }

    public function create()
    {
        $title = "إضافة مشرف";
        $schools = School::query()->get();
        $teachers = [];
        return view('manager.supervisor.edit', compact('title', 'schools', 'teachers'));
    }

    public function store(SupervisorRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'supervisors');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = $request->get('approved', 0);
        $data['password'] = bcrypt($request->get('password', 123456));
        $supervisor = Supervisor::query()->create($data);
        if ($request->has('teachers') && count($request->get('teachers', [])) > 0){
            $supervisor->teachers()->sync($request->get('teachers', []));
        }
        return redirect()->route('manager.supervisor.index')->with('message', self::ADDMESSAGE);
    }

    public function edit($id)
    {
        $title = "تعديل مشرف";
        $supervisor = Supervisor::query()->findOrFail($id);
        $schools = School::query()->get();
        $teachers = Teacher::query()->where('school_id', $supervisor->school_id)->get();
        return view('manager.supervisor.edit', compact('title', 'teachers', 'supervisor', 'schools'));
    }

    public function update(SupervisorRequest $request, $id)
    {
        $supervisor = Supervisor::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'supervisors');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = $request->get('approved', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $supervisor->password;
        $supervisor->update($data);
        $supervisor->teachers()->sync($request->get('teachers', []));
        return redirect()->route('manager.supervisor.index')->with('message', self::EDITMESSAGE);
    }

    public function destroy($id)
    {
        $supervisor = Supervisor::query()->findOrFail($id);
        $supervisor->delete();
        return redirect()->route('manager.supervisor.index')->with('message', self::DELETEMESSAGE);
    }

    public function exportSupervisorsExcel(Request $request)
    {
        return (new SupervisorExport($request))
            ->download('Supervisors Information.xlsx');
    }

    public function supervisorLogin($id)
    {
        $supervisor = Supervisor::query()->findOrFail($id);
        Auth::guard('supervisor')->login($supervisor);
        return redirect()->route('supervisor.home');
    }
}
