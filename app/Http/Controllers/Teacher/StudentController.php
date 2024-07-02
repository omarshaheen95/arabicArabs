<?php

namespace App\Http\Controllers\Teacher;

use App\Classes\GeneralFunctions;
use App\Exports\StudentInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\UserRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Package;
use App\Models\School;
use App\Models\StudentTest;
use App\Models\Teacher;
use App\Models\TeacherUser;
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
            $request['teacher_id'] = null;
            $rows = User::query()->filter($request)
                ->with(['package', 'grade'])
                ->whereDoesntHave('teacherUser')
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                })
                ->addColumn('school', function ($row) {
                    $package = optional($row->package)->name;
                    $section = !is_null($row->section) ? $row->section : '<span class="text-danger">-</span>';
                    $gender = !is_null($row->gender) ? $row->gender : '<span class="text-danger">-</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Package') . ' </span> : ' . '<span> ' . $package . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Section') . ' </span> : ' . '<span> ' . $section . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'unpaid' : optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('package', function ($row) {
                    return optional($row->package)->name;
                })
                ->addColumn('student', function ($row) {
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . $row->grade->name . '</span> : ' .
                        '</div>';
                    return $student;
                })
                ->addColumn('dates', function ($row) {
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
                        '</div>';
                    return $data;
                })
                ->make();
        }
        $title = t('Show Users');
        $packages = Package::query()->where('active', 1)->get();
        $grades = Grade::query()->get();
        return view('teacher.user.index', compact('title', 'packages', 'grades'));
    }

    public function myStudents(Request $request)
    {
        if (request()->ajax()) {
            $rows = User::query()->with(['package', 'grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->login_sessions->count() ? Carbon::parse($row->login_sessions->first()->created_at)->toDateTimeString() : '-';
                })
                ->addColumn('school', function ($row) {
                    $package = optional($row->package)->name;
                    $section = !is_null($row->section) ? $row->section : '<span class="text-danger">-</span>';
                    $gender = !is_null($row->gender) ? $row->gender : '<span class="text-danger">-</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Package') . ' </span> : ' . '<span> ' . $package . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Section') . ' </span> : ' . '<span> ' . $section . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'unpaid' : optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('package', function ($row) {
                    return optional($row->package)->name;
                })
                ->addColumn('student', function ($row) {
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . $row->grade->name . '</span> : ' .
                        '</div>';
                    return $student;
                })
                ->addColumn('dates', function ($row) {
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
                        '</div>';
                    return $data;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Users');
        $packages = Package::query()->where('active', 1)->get();
        $grades = Grade::query()->get();
        return view('teacher.user.my_students', compact('title', 'packages', 'grades'));
    }

    public function edit(Request $request, $id)
    {
        $title = t('Edit User');
        $user = User::query()->filter($request)->findOrFail($id);
        $grades = Grade::query()->get();
        $years = Year::query()->get();
        return view('teacher.user.edit', compact('user', 'title', 'grades', 'years'));
    }

    public function update(UserRequest $request, $id)
    {
        $teacher = Auth::guard('teacher')->user();
        $user = User::query()->whereHas('teacherUser', function (Builder $query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'users');
        }
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $user->password;
        $user->update($data);
        return $this->redirectWith(false, 'teacher.student.my_students', 'Successfully Updated');
    }

    public function exportStudentsExcel(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $file_name = $teacher->school->name . " Students Information.xlsx";
        return (new StudentInformation($request, $teacher->school_id))->download($file_name);
    }

    public function studentAssign(Request $request)
    {
        $ids = $request->get('user_id', false);
        foreach ($ids as $id) {
            TeacherUser::query()->updateOrCreate(['user_id' => $id, 'teacher_id' => $request->get('teacher_id')],
                ['teacher_id' => $request->get('teacher_id'), 'user_id' => $id]
            );
        }
        return $this->sendResponse(null, t('Successfully Assigned'));
    }

    public function deleteStudentAssign(Request $request)
    {
        //request has user_id
        TeacherUser::query()->filter($request)->delete();
        return $this->sendResponse(null, t('Successfully deleted'));
    }


    public function review(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->review($request, $id, 'manager');
    }

    public function storyReview(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->storyReview($request, $id, 'manager');
    }

    public function report(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->userReport($request, $id);
    }

    public function cards(Request $request)
    {
        $students = User::with(['grade', 'school'])->filter($request)->get()->chunk(6);
        $student_login_url = config('app.url') . '/login';
        $school = School::find($request->get('school_id'));
        $title = $school ? $school->name . ' | ' . t('Students Cards') : t('Students Cards');
        return view('general.cards_and_qr', compact('students', 'student_login_url', 'school', 'title'));
    }


}
