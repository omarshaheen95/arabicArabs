<?php

namespace App\Http\Controllers\Manager;

use App\Classes\GeneralFunctions;
use App\Exports\StudentInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\UserRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Package;
use App\Models\School;
use App\Models\SortResult;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show users')->only('index');
        $this->middleware('permission:add users')->only(['create','store']);
        $this->middleware('permission:edit users')->only(['edit','update']);
        $this->middleware('permission:delete users')->only('destroy');
        $this->middleware('permission:export users')->only('export');
        $this->middleware('permission:review users')->only('review');
        $this->middleware('permission:users story review')->only('storyReview');
        $this->middleware('permission:users login')->only('login');
        $this->middleware('permission:restore deleted users')->only('restore');
        $this->middleware('permission:assign teacher')->only('assignedUserToTeacher');
        $this->middleware('permission:unassign teacher')->only('unassignedUserTeacher');
        $this->middleware('permission:users activation')->only('activation');
    }
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = User::query()->with(['school', 'package', 'teacher', 'year','grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('school', function ($row) {
                    $school = optional($row->school)->name;
                    $teacher = optional($row->teacher)->name ? optional($row->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $package = optional($row->package)->name;
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('School') . ' </span> : ' . '<span> ' . $school . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Teacher') . ' </span> : ' . '<span> ' . $teacher . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Package') . ' </span> : ' . '<span> ' . $package . '</span></div>' .
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
                    $section = !is_null($row->section) ? $row->section : '<span class="text-danger">-</span>';

                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . $row->grade->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . t('Learning Years') . '</span> : ' . $row->year_learning . '</div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . t('Section') . '</span> : ' . $section . '</div></div>';
                    return $student;
                })
                ->addColumn('dates', function ($row) {
                    $register_date = Carbon::parse($row->created_at)->format('Y-m-d');
                    $active_to = $row->active_to ? optional($row->active_to)->format('Y-m-d') : t('unpaid');
                    $last_login = $row->last_login ? Carbon::parse($row->last_login)->format('Y-m-d H:i') : '';
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
        $title = t('Users');
        $schools = School::query()->get();
        $packages = Package::query()->get();
        $years = Year::query()->get();
        $grades = Grade::query()->get();
        return view('manager.user.index', compact('title', 'schools', 'packages', 'years','grades'));
    }

    public function create()
    {
        $title = t('Add User');
        $schools = School::query()->get();
        $grades = Grade::query()->get();
        $years_learning = [0,1,2,3,4,5,6,7,8,9,10,11,12];
        $packages = Package::query()->get();
        $years = Year::query()->get();
        return view('manager.user.edit', compact('schools', 'grades', 'title','years', 'packages', 'years_learning'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'users');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = bcrypt($request->get('password', 123456));
        $data['manager_id'] = Auth::guard('manager')->user()->id;
        $data['active_from'] = now();
        $data['year_id'] = Year::query()->latest()->first()->id;
        $user = User::query()->create($data);
        $teacher_id = $request->get('teacher_id', false);
        if ($teacher_id) {
            $user->teacherUser()->updateOrCreate([
                'teacher_id' => $teacher_id,
            ], [
                'teacher_id' => $teacher_id,
            ]);
        }
        return $this->redirectWith(false, 'manager.user.index', self::ADDMESSAGE);
    }

    public function edit($id)
    {
        $title = t('Edit User');
        $user = User::query()->findOrFail($id);
        $schools = School::query()->get();
        $grades = Grade::query()->get();
        $years_learning = [0,1,2,3,4,5,6,7,8,9,10,11,12];
        $packages = Package::query()->get();
        $teachers = Teacher::query()->where('school_id', $user->school_id)->get();
        $years = Year::query()->get();
        return view('manager.user.edit', compact('user', 'schools', 'grades','years', 'title', 'packages', 'teachers', 'years_learning'));
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'users');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $user->password;
        $user->update($data);
        $teacher_id = $request->get('teacher_id', false);
        if ($teacher_id) {
            $user->teacherUser()->forceDelete();
            $user->teacherUser()->updateOrCreate([
                'teacher_id' => $teacher_id,
            ], [
                'teacher_id' => $teacher_id,
            ]);
        } else {
            $user->teacherUser()->delete();
        }
        return $this->redirectWith(false, 'manager.user.index', self::EDITMESSAGE);
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        User::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Deleted Successfully'));
    }

    public function export(Request $request)
    {
        $file_name = "Students Information.xlsx";
        if ($request->get('school_id', false)) {
            $school = School::query()->findOrFail($request->get('school_id'));
            $file_name = $school->name . " Students Information.xlsx";
        }
        return (new StudentInformation($request))->download($file_name);
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
        $request->validate([
            'import_file_id' => 'sometimes|exists:import_student_files,id',
            'school_id' => 'required_if:import_file_id,null|exists:schools,id',
        ]);

        $students = User::with(['grade','school'])->filter($request)->get()->chunk(6);
        $student_login_url = config('app.url') . '/login';
        $school = School::find($request->get('school_id'));
        $title = $school ? $school->name . ' | ' . t('Students Cards') : t('Students Cards');
        return view('general.cards_and_qr', compact('students', 'student_login_url', 'school', 'title'));
    }

    public function login($id)
    {
        $user = User::query()->findOrFail($id);
        Auth::guard('web')->loginUsingId($id);
        return redirect()->route('home');
    }

    public function updateUsers()
    {
        $users = User::query()->where('email', 'like', '% %')->orWhere('mobile', 'like', '% %')->get();
        foreach ($users as $user) {
            $user->update([
                'email' => str_replace(' ', '', $user->email),
                'mobile' => str_replace(' ', '', $user->mobile),
            ]);
        }
        return $users;
    }


    public function activation(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        $data['active'] = $request['activation_data']['activation_status'] == 1 ? 1 : 0;
        if (isset($request['activation_data']['active_to_date']) && !is_null($request['activation_data']['active_to_date'])) {
            $data['active_to'] = Carbon::parse($request['activation_data']['active_to_date'])->format('Y-m-d');
        }
        User::query()->filter($request)->update($data);
        return $this->sendResponse(null, t('Activation Updated Successfully'));
    }

    public function assignedUserToTeacher(Request $request)
    {
        $request->validate([
            'school_id' => 'required',
            'users_data' => 'required|array',
            'users_data.teacher_school_id' => 'required',
            'users_data.users_teacher_id' => 'required',
        ]);
        $users = User::query()->filter($request)->get();

        foreach ($users as $user) {
            if ($user->teacherUser) {
                $user->teacher_student->delete();
            }
            $user->teacherUser()->create([
                'teacher_id' => $request->get('users_data')['users_teacher_id'],
            ]);
        }

        return $this->sendResponse(null, t('Users Updated Successfully'));
    }

    public function unassignedUserTeacher(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        $users = User::query()->with('teacherUser')->filter($request)->get();
        foreach ($users as $user) {
            if ($user->teacherUser) {
                $user->teacherUser->delete();
            }
        }
        return $this->sendResponse(null, t('Unsigned Successfully'));
    }


    public function restore($id)
    {
        $user = User::query()->withTrashed()->findOrFail($id);
        if ($user) {
            $other_users = User::query()->where('email', $user->email)->where('id', '!=', $user->id)->get();
            if ($other_users->count() > 0) {
                return $this->sendError(t('Cannot Restore Student Before Email Already Exist'), 402);
            }
            $user->restore();
            return $this->sendResponse(null, t('Successfully Restored'));
        }
        return $this->sendError(t('Student Not Restored'), 402);


    }

    public function updateGrades(Request $request)
    {
        $request->validate([
            'school_id' => 'required',
        ]);
        $data = [];
        //check if grade is not null and not false then update users
        if (isset($request['users_grades']['grade']) && !is_null($request['users_grades']['grade'])) {
            $data['grade_id'] = $request['users_grades']['grade'];
        }

        //check if grade is not null and not false then update users
        if (isset($request['users_grades']['alternate_grade']) && !is_null($request['users_grades']['alternate_grade'])) {
            $data['alternate_grade_id'] = $request['users_grades']['alternate_grade'];
        }

        //check if learning_years is null and not false then update users
        if (isset($request['users_grades']['learning_years']) && !is_null($request['users_grades']['learning_years'])) {
            $data['year_learning'] = $request['users_grades']['learning_years'];
        }

        //check if assigned_year_id is null and not false then update users
        if (isset($request['users_grades']['assigned_year_id']) && !is_null($request['users_grades']['assigned_year_id'])) {
            $data['year_id'] = $request['users_grades']['assigned_year_id'];
        }

        User::query()->filter($request)->update($data);

        return $this->sendResponse(null, t('Users Updated Successfully'));
    }

    public function duplicateIndex(Request $request)
    {
        if (request()->ajax()) {
            $rows = User::query()->with(['school', 'package'])
                ->filter($request)
                ->whereIn('name', function ($q) {
                    $q->select('name')
                        ->from('users')
                        ->whereNull('deleted_at')
                        ->groupBy('name')
                        ->havingRaw('COUNT(*) > 1');
                })->orderBy('name')
                ->latest('id');

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('school', function ($row) {
                    $school = optional($row->school)->name;
                    $teacher = optional($row->teacher)->name ? optional($row->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $package = optional($row->package)->name;
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('School') . ' </span> : ' . '<span> ' . $school . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Teacher') . ' </span> : ' . '<span> ' . $teacher . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Package') . ' </span> : ' . '<span> ' . $package . '</span></div>' .
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
                    $section = !is_null($row->section) ? $row->section : '<span class="text-danger">-</span>';

                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . $row->grade->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . t('Learning Years') . '</span> : ' . $row->year_learning . '</div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . t('Section') . '</span> : ' . $section . '</div></div>';
                    return $student;
                })
                ->addColumn('dates', function ($row) {
                    $register_date = Carbon::parse($row->created_at)->format('Y-m-d');
                    $active_to = $row->active_to ? optional($row->active_to)->format('Y-m-d') : t('unpaid');
                    $last_login = $row->last_login ? Carbon::parse($row->last_login)->format('Y-m-d H:i') : '';
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
        $title = t('Duplicated Users');
        $schools = School::query()->get();
        $packages = Package::query()->get();
        $years = Year::query()->get();
        $grades = Grade::query()->get();
        return view('manager.user.duplicate_index', compact('years','grades','title', 'schools', 'packages'));
    }
    public function destroyDuplicate(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        $user = User::query()->findOrFail($request->get('row_id'))->forceDelete();
        if (request()->ajax()) {
            return $this->sendResponse(null, self::DELETEMESSAGE);
        } else {
            return $this->redirectWith(true, null, self::DELETEMESSAGE);
        }


    }

}
