<?php

namespace App\Http\Controllers\School;

use App\Events\NewTeacherEvent;
use App\Exports\TeacherExport;
use App\Exports\TeacherStatisticsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\TeacherRequest;
use App\Models\School;
use App\Models\UserTest;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTracker;
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
            $school = Auth::guard('school')->user();
            $search = $request->get('search', false);
            $rows = Teacher::query()->when($search, function (Builder $query) use ($search) {
                $query->where('name', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%');
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
                    $edit_url = route('school.teacher.edit', $row->id);
                    return view('manager.setting.btn_actions', compact('row', 'edit_url'));
                })
                ->make();
        }
        $title = "المعلمين";
        return view('school.teacher.index', compact('title'));
    }

    public function create()
    {
        $title = "إضافة معلم";
        return view('school.teacher.edit', compact('title'));
    }

    public function store(TeacherRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'teachers');
        }
        $school = Auth::guard('school')->user();
        $data['school_id'] = $school->id;
        $data['active'] = $request->get('active', 0);
        $data['approved'] = 0;
        $data['password'] = bcrypt($request->get('password', 123456));
        $teacher = Teacher::create($data);

//        event(new NewTeacherEvent($teacher));
        $message = "$school->name added new teacher ($teacher->name) check it form follow link: " . route('manager.teacher.edit', $teacher->id);
//        supportMessage($message);
        return redirect()->route('school.teacher.index')->with('message', "تم الإضافة بنجاح");
    }

    public function edit($id)
    {
        $title = "تعديل معلم";
        $school = Auth::guard('school')->user();
        $teacher = Teacher::query()->where('school_id', $school->id)->findOrFail($id);
        $schools = School::query()->get();
        return view('school.teacher.edit', compact('title', 'teacher', 'schools'));
    }

    public function update(TeacherRequest $request, $id)
    {
        $school = Auth::guard('school')->user();
        $teacher = Teacher::query()->where('school_id', $school->id)->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('teacher')) {
            $data['teacher'] = $this->uploadImage($request->file('teacher'), 'teachers');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $teacher->password;
        $teacher->update($data);
        return redirect()->route('school.teacher.index')->with('message', "تم التحديث بنجاح");
    }

    public function destroy($id)
    {
        $school = Auth::guard('school')->user();
        $teacher = Teacher::query()->where('school_id', $school->id)->findOrFail($id);
        $teacher->delete();
        return redirect()->route('school.teacher.index')->with('message', "تم الحذف بنجاح");
    }

    public function teachersStatistics(Request $request)
    {
        if (request()->ajax()) {
            $school = Auth::guard('school')->user();
            $search = $request->get('search', false);
            $rows = Teacher::query()->when($search, function (Builder $query) use ($search) {
                $query->where('name', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%');
            })->where('school_id', $school->id)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('school.teacher.statistics_report', $row->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
                })
                ->make();
        }
        $title = "إحصائيات المعلمين";
        return view('school.teacher.statistics', compact('title'));
    }

    public function teachersStatisticsExport(Request $request)
    {
        return (new TeacherStatisticsExport($request, Auth::guard('school')->user()->id))
            ->download('Teachers statistics.xlsx');
    }

    public function teachersStatisticsReport($id)
    {
        $school = Auth::guard('school')->user();
        $teacher = Teacher::query()->where('school_id', $school->id)->findOrFail($id);

        $teacher_students = TeacherUser::query()->where('teacher_id', $teacher->id)->pluck('user_id')->unique()->values()->all();
        $students_grades = User::query()->whereIn('id', $teacher_students)
            ->orderBy('grade_id')
            ->pluck('grade_id')->unique()->values()->all();

        $students_alternate_grades = User::query()->whereIn('id', $teacher_students)->whereNotNull('alternate_grade_id')
            ->orderBy('alternate_grade_id')
            ->pluck('alternate_grade_id')->unique()->values()->all();

        $students_grades = array_merge($students_grades, $students_alternate_grades);
        sort($students_grades);
        $grades_info = [];

        foreach ($students_grades as $students_grade) {
            $grade_info = [];
            $user_games = UserTracker::query()->where('type', 'play')->whereIn('user_id', $teacher_students)
                ->whereHas('lesson', function (Builder $query) use ($students_grade) {
                    $query->whereHas('level', function (Builder $query) use ($students_grade) {
                        $query->where('grade', $students_grade);
                    });
                })->count();
            $user_tests = UserTracker::query()->where('type', 'test')->whereIn('user_id', $teacher_students)
                ->whereHas('lesson', function (Builder $query) use ($students_grade) {
                    $query->whereHas('level', function (Builder $query) use ($students_grade) {
                        $query->where('grade', $students_grade);
                    });
                })->count();
            $user_learning = UserTracker::query()->where('type', 'learn')->whereIn('user_id', $teacher_students)
                ->whereHas('lesson', function (Builder $query) use ($students_grade) {
                    $query->whereHas('level', function (Builder $query) use ($students_grade) {
                        $query->where('grade', $students_grade);
                    });
                })->count();
            $user_training = UserTracker::query()->where('type', 'practise')->whereIn('user_id', $teacher_students)
                ->whereHas('lesson', function (Builder $query) use ($students_grade) {
                    $query->whereHas('level', function (Builder $query) use ($students_grade) {
                        $query->where('grade', $students_grade);
                    });
                })->count();

            $user_tracker = UserTracker::query()->whereIn('user_id', $teacher_students)
                ->whereHas('lesson', function (Builder $query) use ($students_grade) {
                    $query->whereHas('level', function (Builder $query) use ($students_grade) {
                        $query->where('grade', $students_grade);
                    });
                })
                ->count();

            $user_lessons = UserLesson::query()->whereHas('user', function (Builder $query) use ($teacher) {
                    $query->whereHas('teacher_student', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    });
                })
                ->whereHas('lesson', function (Builder $query) use ($students_grade) {
                    $query->whereHas('level', function (Builder $query) use ($students_grade) {
                        $query->where('grade', $students_grade);
                    });
                })->get();

            $students_tests = UserTest::query()->whereHas('user', function (Builder $query) use ($teacher) {
                    $query->whereHas('teacher_student', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    });
                })
                ->whereHas('lesson', function (Builder $query) use ($students_grade) {
                    $query->whereHas('level', function (Builder $query) use ($students_grade) {
                        $query->where('grade', $students_grade);
                    });
                })->get();

            $pending_tasks = $user_lessons->where('status', 'pending')->count();
            $completed_tasks = $user_lessons->where('status', 'corrected')->count();
            $returned_tasks = $user_lessons->where('status', 'returned')->count();

            $passed_tests = $students_tests->where('status', 'Pass')->count();
            $failed_tests = $students_tests->where('status', 'Fail')->count();

            $grade_info['pending_tasks'] = $pending_tasks;
            $grade_info['completed_tasks'] = $completed_tasks;
            $grade_info['returned_tasks'] = $returned_tasks;
            $grade_info['total_tasks'] = $user_lessons->count();
            $grade_info['passed_tests'] = $passed_tests;
            $grade_info['failed_tests'] = $failed_tests;
            $grade_info['total_tests'] = $students_tests->count();
            if ($user_tracker) {
                $grade_info['games'] = round(($user_games / $user_tracker) * 100, 1);
                $grade_info['tests'] = round(($user_tests / $user_tracker) * 100, 1);
                $grade_info['trainings'] = round(($user_training / $user_tracker) * 100, 1);
                $grade_info['learnings'] = round(($user_learning / $user_tracker) * 100, 1);
                $grade_info['tracker'] = $user_tracker;

            } else {
                $grade_info['games'] = 0;
                $grade_info['tests'] = 0;
                $grade_info['trainings'] = 0;
                $grade_info['learnings'] = 0;
                $grade_info['tracker'] = 0;
            }

            $grade_info['grade'] = $students_grade;

            array_push($grades_info, $grade_info);
        }

//        dd($grades_info);


        return view('school.teacher.report', compact('teacher', 'grades_info'));


    }

    public function teacherExport(Request $request)
    {
        $school = Auth::guard('school')->user();
        return (new TeacherExport($request, $school->id))
            ->download('Teachers Information.xlsx');
    }

}
