<?php

namespace App\Http\Controllers\School;

use App\Classes\GeneralFunctions;
use App\Exports\StudentInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\UserRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\MatchResult;
use App\Models\OptionResult;
use App\Models\Package;
use App\Models\Payment;
use App\Models\School;
use App\Models\SortResult;
use App\Models\UserTest;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\TrueFalseResult;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTracker;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = User::with(['teacher','grade'])->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('student', function ($row){
                    $section = !is_null($row->section) ? $row->section : '<span class="text-danger">-</span>';

                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . $row->grade->name . '</span> : ' . '</div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . t('Section') . '</span> : ' . $section . '</div></div>';
                    return $student;
                })
                ->addColumn('teacher', function ($row) {
                    $teacher = optional($row->teacher)->name ? optional($row->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $package = optional($row->package)->name;
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Teacher') . ' </span> : ' . '<span> ' . $teacher . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Package') . ' </span> : ' . '<span> ' . $package . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'unpaid' : optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('grade', function ($row) {
                    return $row->grade->name;
                })->addColumn('active', function ($row) {
                    return $row->active?'<span class="badge badge-primary">'.t('Active').'</span>':'<span class="badge badge-danger">'.t('Non-Active').'</span>';
                })
                ->addColumn('dates', function ($row){
                    $register_date = Carbon::parse($row->created_at)->format('Y-m-d');
                    $active_to = $row->active_to ? optional($row->active_to)->format('Y-m-d') : t('unpaid');
                    $last_login = $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                    if ($row->active == 0) {
                        $status = '<span class="text-danger">' . t('Suspend') . '</span>';
                    } elseif ($row->active == 1 && !is_null($row->active_to) && optional($row->active_to)->format('Y-m-d') <= now()) {
                        $status = '<span class="text-danger">' . t('Expired') . '</span>';
                    } elseif ($row->active == 1 && !is_null($row->active_to) && optional($row->active_to)->format('Y-m-d') > now()) {
                        $status = '<span class="text-success">' . t('Active') . '</span>';
                    } else {
                        $status = '<span class="text-warning">' . t('Unknown') . '</span>';
                    }

                    if ($row->active_to) {
                        $active_to = optional($row->active_to)->format('Y-m-d') <= now() ? '<span class="text-danger">' . optional($row->active_to)->format('Y-m-d') . '</span>' : '<span class="text-success">' . optional($row->active_to)->format('Y-m-d') . '</span>';
                    }
                    $data = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Register Date') . '</span> : ' . $register_date . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Active To') . '</span> : ' . $active_to . '-' . $status . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Last Login') . '</span> : ' . $last_login . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Year') . '</span> : ' . optional($row->year)->name . '</div>' .
                        '</div>';
                    return $data;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Users');
        $teachers = Teacher::query()->filter($request)->get();
        $packages = Package::query()->where('active', 1)->get();
        $grades = Grade::query()->get();
        return view('school.user.index', compact('title', 'teachers', 'packages','grades'));
    }

    public function edit($id)
    {
        $title = t('Edit Student');
        $school = Auth::guard('school')->user();
        $user = User::query()->where('school_id', $school->id)->findOrFail($id);
        $teachers = Teacher::query()->where('school_id', $school->id)->get();
        $grades = Grade::query()->get();
        $years = Year::query()->get();
        return view('school.user.edit', compact('user', 'teachers', 'title','grades','years'));
    }

    public function update(UserRequest $request, $id)
    {
        $school = Auth::guard('school')->user();
        $user = User::query()->where('school_id', $school->id)->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image'))
        {
            $data['image'] = $this->uploadFile($request->file('image'), 'users');
        }
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)):$user->password;
        $user->update($data);
        $teacher_id = $request->get('teacher_id', false);
        if ($teacher_id)
        {
            $user->teacherUser()->forceDelete();
            $user->teacherUser()->updateOrCreate([
                'teacher_id' => $teacher_id,
            ],[
                'teacher_id' => $teacher_id,
            ]);
        }else{
            $user->teacherUser()->delete();
        }
        return $this->redirectWith(false, 'school.student.index', 'Successfully Updated');
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        User::destroy($request->get('row_id'));
        return $this->sendResponse(null,t('Successfully Deleted'));
    }


    public function exportStudentsExcel(Request $request)
    {
        $school = Auth::guard('school')->user();
        $file_name = "$school->name Students Information.xlsx";
        return (new StudentInformation($request))
            ->download($file_name);
    }

    public function review(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->review($request,$id,'manager');
    }

    public function storyReview(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->storyReview($request,$id,'manager');
    }

    public function report(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->userReport($request,$id);
    }


    public function cards(Request $request)
    {
        $students = User::with(['grade','school'])->filter($request)->get()->chunk(6);
        $student_login_url = config('app.url') . '/login';
        $school = School::find($request->get('school_id'));
        $title = $school ? $school->name . ' | ' . t('Students Cards') : t('Students Cards');
        return view('general.cards_and_qr', compact('students', 'student_login_url', 'school', 'title'));
    }



}
