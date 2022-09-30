<?php

namespace App\Http\Controllers\School;

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
            $school = Auth::guard('school')->user();
            $search = $request->get('search', false);
            $rows = Supervisor::query()->when($search, function (Builder $query) use ($search) {
                $query->where('name', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })->where('school_id', $school->id)->latest();
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
                    $edit_url = route('school.supervisor.edit', $row->id);
                    return view('manager.setting.btn_actions', compact('row', 'edit_url'));
                })
                ->make();
        }
        $title = "المشرفين";
        return view('school.supervisor.index', compact('title'));
    }

    public function create()
    {
        $title = "إضافة مشرف";
        $school = Auth::guard('school')->user();
        $teachers = Teacher::query()->where('school_id', $school->id)->get();
        return view('school.supervisor.edit', compact('title', 'teachers'));
    }

    public function store(SupervisorRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'supervisors');
        }
        $school = Auth::guard('school')->user();
        $data['school_id'] = $school->id;
        $data['active'] = $request->get('active', 0);
        $data['password'] = bcrypt($request->get('password', 123456));
        $supervisor = Supervisor::query()->create($data);

        return redirect()->route('school.supervisor.index')->with('message', "تم الإضافة بنجاح");
    }

    public function edit($id)
    {
        $title = "تعديل مشرف";
        $school = Auth::guard('school')->user();
        $supervisor = Supervisor::query()->where('school_id', $school->id)->findOrFail($id);
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
        return redirect()->route('school.supervisor.index')->with('message', "تم التعديل بنجاح");
    }

    public function destroy($id)
    {
        $school = Auth::guard('school')->user();
        $supervisor = Supervisor::query()->where('school_id', $school->id)->findOrFail($id);
        $supervisor->delete();
        return redirect()->route('school.supervisor.index')->with('message', "تم الحذف بنجاح");
    }

}
