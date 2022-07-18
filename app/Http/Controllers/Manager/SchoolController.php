<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\SchoolRequest;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $rows = School::query()->search($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row){
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString():'';
                })
                ->addColumn('active', function ($row) {
                    return $row->active_status;
                })
                ->addColumn('actions', function ($row) {
                    $edit_url = route('manager.school.edit', $row->id);
                    return view('manager.setting.btn_actions', compact('row', 'edit_url'));
                })
                ->make();
        }
        $title = "قائمة المدارس";
        return view('manager.school.index', compact('title'));
    }

    public function create()
    {
        $title = "إضافة مدرسة";
        return view('manager.school.edit', compact('title'));
    }

    public function store(SchoolRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('logo'))
        {
            $data['logo'] = $this->uploadImage($request->file('logo'), 'schools');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = 1;
        $data['password'] = bcrypt($request->get('password', 123456));
        School::create($data);
        return redirect()->route('manager.school.index')->with('message', "تم الإضافة بنجاح");
    }

    public function edit($id)
    {
        $title = "تعديل مدرسة";
        $school = school::query()->findOrFail($id);
        return view('manager.school.edit', compact('title', 'school'));
    }

    public function update(SchoolRequest $request, $id)
    {
        $school = School::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('logo'))
        {
            $data['logo'] = $this->uploadImage($request->file('logo'), 'schools');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)):$school->password;
        $school->update($data);
        return redirect()->route('manager.school.index')->with('message', "تم التعديل بنجاح");
    }

    public function destroy($id)
    {
        $school = School::query()->findOrFail($id);
        User::query()->where('school_id', $id)->update(['school_id' => null]);
        $school->forceDelete();
        return redirect()->route('manager.school.index')->with('message', "تم الحذف بنجاح");
    }
}
