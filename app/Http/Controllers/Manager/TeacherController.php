<?php

namespace App\Http\Controllers\Manager;

use App\Exports\TeacherExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\TeacherRequest;
use App\Models\School;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {

            $rows = Teacher::query()->with(['school'])->search($request)
                ->latest();
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
                ->addColumn('school', function ($row) {
                    return $row->school->name;
                })
                ->addColumn('status', function ($row) {
                    return $row->approved_status;
                })
                ->addColumn('actions', function ($row) {
                    $edit_url = route('manager.teacher.edit', $row->id);
                    $login_url = route('manager.teacher.login', $row->id);
                    return view('manager.setting.btn_actions', compact('row', 'edit_url', 'login_url'));
                })
                ->addColumn('check', function ($row) {
                    return $row->check;
                })
                ->make();
        }
        $title = "قائمة المعلمون";
        $schools = School::query()->get();
        return view('manager.teacher.index', compact('title', 'schools'));
    }

    public function create()
    {
        $title = "إضافة معلم";
        $schools = School::query()->get();
        return view('manager.teacher.edit', compact('title', 'schools'));
    }

    public function store(TeacherRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'teachers');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = 1;
        $data['password'] = bcrypt($request->get('password', 123456));
        Teacher::create($data);
        return redirect()->route('manager.teacher.index')->with('message', self::ADDMESSAGE);
    }

    public function edit($id)
    {
        $title = "تعديل معلم";
        $teacher = Teacher::query()->findOrFail($id);
        $schools = School::query()->get();
        return view('manager.teacher.edit', compact('title', 'teacher', 'schools'));
    }

    public function update(TeacherRequest $request, $id)
    {
        $teacher = Teacher::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('teacher')) {
            $data['teacher'] = $this->uploadImage($request->file('teacher'), 'teachers');
        }
        $data['active'] = $request->get('active', 0);
        $data['approved'] = $request->get('approved', $teacher->approved);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $teacher->password;
        $teacher->update($data);
        return redirect()->route('manager.teacher.index')->with('message', self::EDITMESSAGE);
    }

    public function destroy($id)
    {
        $teacher = Teacher::query()->findOrFail($id);
        $teacher->delete();
        return redirect()->route('manager.teacher.index')->with('message', self::DELETEMESSAGE);
    }

    public function approveTeacher(Request $request)
    {
        $id = $request->get('teacher_id', false);
        if ($id) {
            if (is_array($id)) {
                Teacher::query()->whereIn('id', $id)->update([
                    'approved' => 1
                ]);
                return $this->sendResponse(null, self::EDITMESSAGE);
            } else {
                $teacher = Teacher::query()->findOrFail($id);
                $teacher->update([
                    'approved' => 1
                ]);
            }
        }

        return redirect()->route('manager.teacher.index')->with('message', self::EDITMESSAGE);
    }

    public function activeTeacher(Request $request)
    {
        $id = $request->get('teacher_id', false);
        if ($id) {
            if (is_array($id)) {
                Teacher::query()->whereIn('id', $id)->update([
                    'active' => $request->get('active', 0)
                ]);
                return $this->sendResponse(null, self::EDITMESSAGE);
            } else {
                $teacher = Teacher::query()->findOrFail($id);
                $teacher->update([
                    'active' => $request->get('active', 0)
                ]);
            }
        }

        return redirect()->route('manager.teacher.index')->with('message', self::EDITMESSAGE);
    }

    public function exportTeachersExcel(Request $request)
    {
        return (new TeacherExport($request))
            ->download('Teachers Information.xlsx');
    }

    public function getTeacherBySchool(Request $request, $id)
    {
        $rows = Teacher::query()->where('school_id', $id)->get();
        $selected = $request->get('selected', 0);
        if ($selected) {
            $html = '<option selected disabled value="">اختر مدرس</option>';
        } else {
            $html = '<option selected value="">اختر مدرس</option>';
        }

        foreach ($rows as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function teacherLogin($id)
    {
        $user = Teacher::query()->findOrFail($id);
        Auth::guard('teacher')->loginUsingId($id);
        return redirect()->route('teacher.home');
    }
}
