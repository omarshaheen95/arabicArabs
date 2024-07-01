<?php

namespace App\Http\Controllers\School;

use App\Exports\SupervisorExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\SupervisorRequest;
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
            $rows = Supervisor::with('school')->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                })
                ->addColumn('active_to', function ($row) {
                    return $row->active_to ? Carbon::parse($row->active_to)->format('d/m/Y h:i A') : '';
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">'.t('Active').'</span>' : '<span class="badge badge-danger">'.t('Inactive').'</span>';
                })
                ->addColumn('approved', function ($row) {
                    return $row->approved ? '<span class="badge badge-primary">'.t('Approved').'</span>' : '<span class="badge badge-warning">'.t('Under review').'</span>';
                })
                ->addColumn('school', function ($row) {
                    return $row->school->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show supervisors');
        return view('school.supervisor.index', compact('title'));
    }


    public function create()
    {
        $title = t('Add supervisor');
        $school = Auth::guard('school')->user();
        $teachers = Teacher::query()->where('school_id', $school->id)->get();
        return view('school.supervisor.edit', compact('title', 'teachers'));
    }

    public function store(SupervisorRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'supervisors');
        }
        $school = Auth::guard('school')->user();
        $data['school_id'] = $school->id;
        $data['active'] = $request->get('active', 0);
        $data['password'] = bcrypt($request->get('password', 123456));
        $supervisor = Supervisor::query()->create($data);
        $supervisor->teachers()->sync($request->get('teachers', []));
        return redirect()->route('school.supervisor.index')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = t('Edit Supervisor');
        $school = Auth::guard('school')->user();
        $supervisor = Supervisor::with('supervisor_teachers')->where('school_id', $school->id)->findOrFail($id);
        $teachers = Teacher::query()->where('school_id', $school->id)->get();
        return view('school.supervisor.edit', compact('title', 'teachers', 'supervisor'));
    }

    public function update(SupervisorRequest $request, $id)
    {
        $school = Auth::guard('school')->user();
        $supervisor = Supervisor::query()->where('school_id', $school->id)->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'supervisors');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $supervisor->password;
        $supervisor->update($data);
        $supervisor->teachers()->sync($request->get('teachers', []));
        return redirect()->route('school.supervisor.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        Supervisor::query()->filter($request)->delete();
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function export(Request $request){
        return (new SupervisorExport($request))->download('Supervisors Informations.xlsx');
    }


}
