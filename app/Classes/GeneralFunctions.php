<?php

namespace App\Classes;

use App\Models\Grade;
use App\Models\Lesson;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class GeneralFunctions
{
    public function review(Request $request, $id,$guard)
    {
        if (request()->ajax()) {
            $rows = UserTracker::query()->where('user_id', $id)->has('lesson')->with(['lesson.grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('type', function ($row) {
                    return t(ucfirst($row->type));
                })
                ->addColumn('lesson', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->lesson->grade_name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Lesson') . ':</span>' . $row->lesson->name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('time_spent', function ($row) {
                    if (!is_null($row->start_at) && !is_null($row->end_at)) {
                        $time1 = new \DateTime($row->start_at);
                        $time2 = new \DateTime($row->end_at);
                        $interval = $time1->diff($time2);
                        return $interval->format('%i ' . t('minute(s)'));
                    } else {
                        return '-';
                    }
                })
                ->make();
        }

        $title = t('Student Lessons review');
        $user = User::with(['grade','alternateGrade'])->findOrFail($id);
        $teacher = $user->teacher;

        $grades = Grade::query()->get();
        $tests = UserTest::query()->where('user_id', $id)->count();
        $passed_tests = UserTest::query()->where('user_id', $id)->where('total', '>=', 40)->count();


        $user_tracker = UserTracker::query()->where('user_id', $id)->get();
        $total = $user_tracker->where('user_id', $id)->count();
        $data['learn'] = $user_tracker->where('user_id', $id)->where('type', 'learn')->count();
        $data['practise'] = $user_tracker->where('user_id', $id)->where('type', 'practise')->count();
        $data['test'] = $user_tracker->where('user_id', $id)->where('type', 'test')->count();
        $data['play'] = $user_tracker->where('user_id', $id)->where('type', 'play')->count();

        $data['learn_avg'] = $total && $data['learn'] ? round(($data['learn'] / $total) * 100, 2) : 0;
        $data['practise_avg'] = $total && $data['practise'] ? round(($data['practise'] / $total) * 100, 2) : 0;
        $data['test_avg'] = $total && $data['test'] ? round(($data['test'] / $total) * 100, 2) : 0;
        $data['play_avg'] = $total && $data['play'] ? round(($data['play'] / $total) * 100, 2) : 0;

        $data['passed_tests'] = UserTest::query()->where('user_id', $id)->where('status', 'Pass')->count();
        $data['failed_tests'] = UserTest::query()->where('user_id', $id)->where('status', 'Fail')->count();

        return view('general.user.user_lesson_review', compact('user', 'teacher',
            'tests', 'passed_tests', 'title', 'data', 'guard','grades'));

    }

    public function storyReview(Request $request, $id,$guard)
    {
        if (request()->ajax()) {
            $rows = UserTrackerStory::query()->where('user_id', $id)->has('story')->with(['story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('type', function ($row) {
                    return t(ucfirst($row->type));
                })
                ->addColumn('story', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Level') . ':</span>' . $row->story->grade_name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story') . ':</span>' . $row->story->name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('time_spent', function ($row) {
                    if (!is_null($row->start_at) && !is_null($row->end_at)) {
                        $time1 = new \DateTime($row->start_at);
                        $time2 = new \DateTime($row->end_at);
                        $interval = $time1->diff($time2);
                        return $interval->format('%i ' . t('minute(s)'));
                    } else {
                        return '-';
                    }
                })
                ->make();
        }
        $title = t('Student story review');
        $user = User::query()->findOrFail($id);
        $teacher = $user->teacher;


        $tests = StudentStoryTest::query()->where('user_id', $id)->get();

        $user_tracker_stories = UserTrackerStory::query()->where('user_id', $id)->get();

        $total = $user_tracker_stories->where('user_id', $id)->count();
        $data['watching'] = $user_tracker_stories->where('type', 'watching')->count();
        $data['reading'] = $user_tracker_stories->where('type', 'reading')->count();
        $data['test'] = $user_tracker_stories->where('type', 'test')->count();
        $data['watching_avg'] = $total && $data['watching'] ? round(($data['watching'] / $total) * 100, 2) : 0;
        $data['reading_avg'] = $total && $data['reading'] ? round(($data['reading'] / $total) * 100, 2) : 0;
        $data['test_avg'] = $total && $data['test'] ? round(($data['test'] / $total) * 100, 2) : 0;
        $data['passed_tests'] = $tests->where('status', 'Pass')->count();
        $data['failed_tests'] = $tests->where('status', 'Fail')->count();

        $tests = $tests->count();

        return view('general.user.user_story_review', compact('user', 'teacher',
            'tests', 'title', 'data', 'total', 'guard'));

    }


    public function userReport(Request $request, $id)
    {
        $title = t('Student report');
        $student = User::query()->filter($request)->findOrFail($id);
        if ($student->teacherUser && $student->teacherUser->teacher) {
            $teacher = $student->teacherUser->teacher;
        } else {
            $teacher = null;
        }

        $start_date = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $grade = $request->get('grade', $student->grade);

        $student_tests = UserTracker::query()->where('user_id', $student->id)
            ->pluck('lesson_id')->unique()->values()->all();
        $user_tracker_data = UserTracker::query()->where('user_id', $student->id)
            ->whereIn('lesson_id', $student_tests)->get();
        $user_tests_data = UserTest::query()->where('user_id', $student->id)->whereIn('lesson_id', $student_tests)->get();
        $user_lessons_data = UserLesson::query()->where('user_id', $student->id)->whereIn('lesson_id', $student_tests)->get();
        $lessons = Lesson::query()->whereIn('id',$student_tests)->get();

        $user_games = 0;
        $user_tests = 0;
        $user_learning = 0;
        $user_training = 0;
        $user_tracker = 0;
        $lessons_info = [];

        foreach ($student_tests as $lesson) {
            $lesson_info = [];
            $user_games = $user_tracker_data->where('type', 'play')->where('lesson_id', $lesson)->count();
            $user_tests = $user_tracker_data->where('type', 'test')->where('lesson_id', $lesson)->count();
            $user_learning = $user_tracker_data->where('type', 'learn')->where('lesson_id', $lesson)->count();
            $user_training = $user_tracker_data->where('type', 'practise')->where('lesson_id', $lesson)->count();

            $user_tracker = $user_tracker_data->where('lesson_id', $lesson)->count();
            if ($user_tracker) {
                $lesson_info['games'] = round(($user_games / $user_tracker) * 100, 1);
                $lesson_info['tests'] = round(($user_tests / $user_tracker) * 100, 1);
                $lesson_info['trainings'] = round(($user_training / $user_tracker) * 100, 1);
                $lesson_info['learnings'] = round(($user_learning / $user_tracker) * 100, 1);
                $lesson_info['tracker'] = $user_tracker;
            } else {
                $lesson_info['games'] = 0;
                $lesson_info['tests'] = 0;
                $lesson_info['trainings'] = 0;
                $lesson_info['learnings'] = 0;
                $lesson_info['tracker'] = 0;
            }

            $lesson_info['user_test'] = $user_tests_data->where('lesson_id', $lesson)->latest('total')->first();

            if (isset($user_test) && !is_null($user_test->start_at) && !is_null($user_test->end_at)) {
                $time1 = new \DateTime($user_test->start_at);
                $time2 = new \DateTime($user_test->end_at);
                $interval = $time1->diff($time2);

                $lesson_info['time_consumed'] = $interval->format('%i minute(s)');

            } else {
                $lesson_info['time_consumed'] = '-';
            }

            $lesson_info['user_lesson'] = $user_lessons_data->where('lesson_id', $lesson)->where('status', 'corrected')->first();

            $lesson_info['lesson'] = $lessons->where('id',$lesson);

            array_push($lessons_info, $lesson_info);
        }

        $lessons_info = array_chunk($lessons_info, 2);
        return view('general.reports.user_report', compact('student', 'teacher', 'lessons_info'));

    }

    public function teacherReport(Request $request, $id):View
    {
        $teacher = Teacher::with('teacher_users')->filter($request)->findOrFail($id);

        $teacher_students = TeacherUser::query()->where('teacher_id', $teacher->id)->pluck('user_id')->unique()->values()->all();
        $students_grades = User::with('grade')->whereIn('id', $teacher_students)
            ->orderBy('grade_id')
            ->pluck('grade_id')->unique()->values()->all();

        $students_alternate_grades = User::with('alternateGrade')->whereIn('id', $teacher_students)->whereNotNull('alternate_grade_id')
            ->orderBy('alternate_grade_id')
            ->pluck('alternate_grade_id')->unique()->values()->all();

        $students_grades = array_merge($students_grades, $students_alternate_grades);
        sort($students_grades);
        $grades_info = [];

        $user_tracker_data = UserTracker::with('lesson')->has('lesson')->whereIn('user_id', $teacher_students)->get();

        foreach ($students_grades as $student_grade) {
            $grade_info = [];
            $user_games = $user_tracker_data->where('type', 'play')
                ->filter(function ($item) use ($student_grade){
                    return $item->lesson->grade_id = $student_grade;
                })->count();

            $user_tests = $user_tracker_data->where('type', 'test')
                ->filter(function ($item) use ($student_grade){
                    return $item->lesson->grade_id = $student_grade;
                })->count();

            $user_learning = $user_tracker_data->where('type', 'learn')
                ->filter(function ($item) use ($student_grade){
                    return $item->lesson->grade_id = $student_grade;
                })->count();

            $user_training = $user_tracker_data->where('type', 'practise')
                ->filter(function ($item) use ($student_grade){
                    return $item->lesson->grade_id = $student_grade;
                })->count();


            $user_tracker = $user_tracker_data->filter(function ($item) use ($student_grade){
                return $item->lesson->grade_id = $student_grade;
            })->count();



            $user_lessons = UserLesson::query()->whereHas('user', function (Builder $query) use ($teacher) {
                $query->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                });
            })->whereHas('lesson', function (Builder $query) use ($student_grade) {
                $query->where('grade_id', $student_grade);
            })->get();


            $students_tests = UserTest::query()->whereHas('user', function (Builder $query) use ($teacher) {
                $query->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                });
            })->whereHas('lesson', function (Builder $query) use ($student_grade) {
                $query->where('grade_id', $student_grade);
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

            $grade_info['grade'] = $student_grade;

            array_push($grades_info, $grade_info);

        }

        return view('general.reports.teacher_report', compact('teacher', 'grades_info'));
    }

    public function usageReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if (request()->get('current_guard') == 'supervisor'){
            $supervisor = Auth::guard('supervisor')->user();
            $school = $supervisor->school;
        }elseif (request()->get('current_guard') == 'school'){
            $school = Auth::guard('school')->user();
        }

        $grades = $request->get('grades', []);
        $start_date = $request->get('start_date', []);
        $end_date = $request->get('end_date', []);

        $data['total_students'] = User::query()
            ->where(function (Builder $query) use ($grades) {
                $query->whereIn('grade_id', $grades)
                    ->orWhereIn('alternate_grade_id', $grades);
            })
            ->where('school_id', $school->id)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->count();

        $data['total_teachers'] = Teacher::query()
            ->where('school_id', $school->id)
            ->where('last_login', '>=', $start_date)
            ->where('last_login', '<=', $end_date)
            ->count();

        $data['top_teacher'] = Teacher::query()
            ->where('school_id', $school->id)
            ->where('last_login', '>=', $start_date)
            ->where('last_login', '<=', $end_date)
            ->orderBy('passed_tests', 'desc')
            ->first();
        $data['top_student'] = User::query()
            ->where(function (Builder $query) use ($grades) {
                $query->whereIn('grade_id', $grades)
                    ->orWhereIn('alternate_grade_id', $grades);
            })
            ->where('school_id', $school->id)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->withCount(['user_test' => function ($query) {
                $query->where('status', 'Pass');
            }])
            ->orderBy('user_test_count', 'desc')
            ->first();
        $data['total_tests'] = UserTest::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->count();
        $data['total_pass_tests'] = UserTest::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('status', 'Pass')
            ->count();
        $data['total_fail_tests'] = UserTest::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('status', 'Fail')
            ->count();
        $data['total_assignments'] = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->count();
        $data['total_corrected_assignments'] = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('status', 'corrected')
            ->count();
        $data['total_uncorrected_assignments'] = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->whereIn('status', ['pending', 'returned'])
            ->count();

        $teachers = Teacher::query()
            ->where('school_id', $school->id)
            ->where('last_login', '>=', $start_date)
            ->where('last_login', '<=', $end_date)
            ->get();

        $tracks = UserTracker::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->latest()->get();

        if ($data['total_practice'] = $tracks->count()) {
            $data['learn'] = $tracks->where('type', 'learn')->count();
            $data['practise'] = $tracks->where('type', 'practise')->count();
            $data['test'] = $tracks->where('type', 'test')->count();
            $data['play'] = $tracks->where('type', 'play')->count();
            $data['learn_avg'] = ($data['learn'] / $data['total_practice']) * 100;
            $data['practise_avg'] = ($data['practise'] / $data['total_practice']) * 100;
            $data['test_avg'] = ($data['test'] / $data['total_practice']) * 100;
            $data['play_avg'] = ($data['play'] / $data['total_practice']) * 100;
        } else {
            $data['total_practice'] = 0;
            $data['learn'] = 0;
            $data['practise'] = 0;
            $data['test'] = 0;
            $data['play'] = 0;
            $data['learn_avg'] = 0;
            $data['practise_avg'] = 0;
            $data['test_avg'] = 0;
            $data['play_avg'] = 0;
        }

        $grades_data = [];
        foreach ($grades as $grade) {
            $grades_data[$grade]['total_students'] = User::query()
                ->where(function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade)
                        ->orWhere('alternate_grade_id', $grade);
                })
                ->where('school_id', $school->id)
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->count();

            $grades_data[$grade]['total_teachers'] = Teacher::query()
                ->where('school_id', $school->id)
                ->where('last_login', '>=', $start_date)
                ->where('last_login', '<=', $end_date)
                ->whereHas('teacher_users', function (Builder $query) use ($grade) {
                    $query->whereHas('user', function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade);
                    });
                })
                ->count();

            $grades_data[$grade]['top_teacher'] = Teacher::query()
                ->where('school_id', $school->id)
                ->where('last_login', '>=', $start_date)
                ->where('last_login', '<=', $end_date)
                ->whereHas('teacher_users', function (Builder $query) use ($grade) {
                    $query->whereHas('user', function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade);
                    });
                })
                ->orderBy('passed_tests', 'desc')
                ->first();

            $grades_data[$grade]['top_student'] = User::query()
                ->where(function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade)
                        ->orWhere('alternate_grade_id', $grade);
                })
                ->where('school_id', $school->id)
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->withCount(['user_test' => function ($query) {
                    $query->where('status', 'Pass');
                }])
                ->orderBy('user_test_count', 'desc')
                ->first();
            $grades_data[$grade]['total_tests'] = UserTest::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->count();
            $grades_data[$grade]['total_pass_tests'] = UserTest::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->where('status', 'Pass')
                ->count();
            $grades_data[$grade]['total_fail_tests'] = UserTest::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->where('status', 'Fail')
                ->count();
            $grades_data[$grade]['total_assignments'] = UserLesson::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->count();
            $grades_data[$grade]['total_corrected_assignments'] = UserLesson::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->where('status', 'corrected')
                ->count();
            $grades_data[$grade]['total_uncorrected_assignments'] = UserLesson::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->whereIn('status', ['pending', 'returned'])
                ->count();

            $tracks = UserTracker::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->latest()->get();

            if ($grades_data[$grade]['total_practice'] = $tracks->count()) {
                $grades_data[$grade]['learn'] = $tracks->where('type', 'learn')->count();
                $grades_data[$grade]['practise'] = $tracks->where('type', 'practise')->count();
                $grades_data[$grade]['test'] = $tracks->where('type', 'test')->count();
                $grades_data[$grade]['play'] = $tracks->where('type', 'play')->count();
                $grades_data[$grade]['learn_avg'] = ($grades_data[$grade]['learn'] / $grades_data[$grade]['total_practice']) * 100;
                $grades_data[$grade]['practise_avg'] = ($grades_data[$grade]['practise'] / $grades_data[$grade]['total_practice']) * 100;
                $grades_data[$grade]['test_avg'] = ($grades_data[$grade]['test'] / $grades_data[$grade]['total_practice']) * 100;
                $grades_data[$grade]['play_avg'] = ($grades_data[$grade]['play'] / $grades_data[$grade]['total_practice']) * 100;
            } else {
                $grades_data[$grade]['total_practice'] = 0;
                $grades_data[$grade]['learn'] = 0;
                $grades_data[$grade]['practise'] = 0;
                $grades_data[$grade]['test'] = 0;
                $grades_data[$grade]['play'] = 0;
                $grades_data[$grade]['learn_avg'] = 0;
                $grades_data[$grade]['practise_avg'] = 0;
                $grades_data[$grade]['test_avg'] = 0;
                $grades_data[$grade]['play_avg'] = 0;
            }
        }

        return view('general.reports.usage_report.usage_report', compact('grades', 'grades_data', 'data', 'school', 'teachers', 'start_date', 'end_date'));
    }


}
