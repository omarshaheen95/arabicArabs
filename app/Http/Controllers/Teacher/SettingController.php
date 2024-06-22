<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\TeacherProfileRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Story;
use App\Models\UserTest;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTracker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class SettingController extends Controller
{
    public function home()
    {
        $title = t('Home');
        $teacher = Auth::guard('teacher')->user();
        $school_students = User::query()->where('school_id', $teacher->school_id)->count();
        $students = TeacherUser::query()->where('teacher_id', $teacher->id)->count();
        $tests = UserTest::query()->whereHas('user', function (Builder $query) use($teacher){
            $query->whereHas('teacherUser', function (Builder $query) use ($teacher){
                $query->where('teacher_id', $teacher->id);
            });
        })->count();

        return view('teacher.home', compact('title', 'students', 'school_students', 'tests'));
    }

    public function lang($local)
    {
        session(['lang' => $local]);
        if(Auth::guard('teacher')->check()){
            $user = Auth::guard('teacher')->user();
            $user->update([
                'local' => $local,
            ]);
        }
        app()->setLocale($local);
        return back();
    }

    public function editProfile()
    {
        $title = t('Show Profile');
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.profile.profile', compact('title', 'teacher'));
    }

    public function updateProfile(TeacherProfileRequest $request)
    {
        $user = Auth::guard('teacher')->user();
        $data = $request->validated();
        if ($request->file('image')){
            $data['image'] = $this->uploadFile($request->file('image'),'profile_images/teachers');
        }
        $user->update($data);
        return redirect()->route('teacher.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function editPassword()
    {
        $title = t('Change Password');
        return view('teacher.profile.password', compact('title' ));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::guard('teacher')->user();
        $request->validated();
        if (Hash::check($request->get('old_password'), $user->password)) {
            $data['password'] = bcrypt($request->get('password'));
            $user->update($data);
        } else {
            return $this->redirectWith(true, null, 'Current Password Invalid', 'error');
        }

        return redirect()->route('teacher.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function preUsageReport()
    {
        $title = t('Usage Report');
        $grades = Grade::query()->get();
        return view('teacher.report.pre_usage_report', compact('title', 'grades'));
    }

    public function usageReport(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $teacher = Auth::guard('teacher')->user();
        $grades = $request->get('grades', []);
        $start_date = $request->get('start_date', []);
        $end_date = $request->get('end_date', []);
        $sections = $request->get('sections', []);

        $data['total_students'] = User::query()
            ->where(function (Builder $query) use ($grades) {
                $query->whereIn('grade_id', $grades)
                    ->orWhereIn('alternate_grade_id', $grades);
            })
            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->whereIn('section', $sections)
            ->count();


        $data['top_student'] = User::query()
            ->where(function (Builder $query) use ($grades) {
                $query->whereIn('grade_id', $grades)
                    ->orWhereIn('alternate_grade_id', $grades);
            })
            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->whereIn('section', $sections)
            ->withCount(['user_test' => function ($query) {
                $query->where('status', 'Pass');
            }])
            ->orderBy('user_test_count', 'desc')
            ->first();
        $data['total_tests'] = UserTest::query()
            ->whereHas('user', function (Builder $query) use ($teacher, $grades, $sections) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })
                    ->whereIn('section', $sections);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->count();
        $data['total_pass_tests'] = UserTest::query()
            ->whereHas('user', function (Builder $query) use ($teacher, $grades, $sections) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })
                    ->whereIn('section', $sections);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('status', 'Pass')
            ->count();
        $data['total_fail_tests'] = UserTest::query()
            ->whereHas('user', function (Builder $query) use ($teacher, $grades, $sections) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })
                    ->whereIn('section', $sections);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('status', 'Fail')
            ->count();
        $data['total_assignments'] = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($teacher, $grades, $sections) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })
                    ->whereIn('section', $sections);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->count();
        $data['total_corrected_assignments'] = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($teacher, $grades, $sections) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })
                    ->whereIn('section', $sections);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('status', 'corrected')
            ->count();
        $data['total_uncorrected_assignments'] = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($teacher, $grades, $sections) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })
                    ->whereIn('section', $sections);
            })
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->whereIn('status', ['pending', 'returned'])
            ->count();


        $tracks = UserTracker::query()
            ->whereHas('user', function (Builder $query) use ($teacher, $grades, $sections) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })
                    ->whereIn('section', $sections);
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
            $total_count = User::query()
                ->where(function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade)
                        ->orWhere('alternate_grade_id', $grade);
                })
                ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                })
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date)
                ->whereIn('section', $sections)
                ->count();
            if (true) {

                $grades_data[$grade]['total_students'] = $total_count;

                $grades_data[$grade]['top_student'] = User::query()
                    ->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                    ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', $end_date)
                    ->whereIn('section', $sections)
                    ->withCount(['user_test' => function ($query) {
                        $query->where('status', 'Pass');
                    }])
                    ->orderBy('user_test_count', 'desc')
                    ->first();
                $grades_data[$grade]['total_tests'] = UserTest::query()
                    ->whereHas('user', function (Builder $query) use ($teacher, $grade, $sections) {
                        $query->where(function (Builder $query) use ($grade) {
                            $query->where('grade_id', $grade)
                                ->orWhere('alternate_grade_id', $grade);
                        })
                            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->whereIn('section', $sections);
                    })
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', $end_date)
                    ->count();
                $grades_data[$grade]['total_pass_tests'] = UserTest::query()
                    ->whereHas('user', function (Builder $query) use ($teacher, $grade, $sections) {
                        $query->where(function (Builder $query) use ($grade) {
                            $query->where('grade_id', $grade)
                                ->orWhere('alternate_grade_id', $grade);
                        })
                            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->whereIn('section', $sections);
                    })
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', $end_date)
                    ->where('status', 'Pass')
                    ->count();
                $grades_data[$grade]['total_fail_tests'] = UserTest::query()
                    ->whereHas('user', function (Builder $query) use ($teacher, $grade, $sections) {
                        $query->where(function (Builder $query) use ($grade) {
                            $query->where('grade_id', $grade)
                                ->orWhere('alternate_grade_id', $grade);
                        })
                            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->whereIn('section', $sections);
                    })
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', $end_date)
                    ->where('status', 'Fail')
                    ->count();
                $grades_data[$grade]['total_assignments'] = UserLesson::query()
                    ->whereHas('user', function (Builder $query) use ($teacher, $grade, $sections) {
                        $query->where(function (Builder $query) use ($grade) {
                            $query->where('grade_id', $grade)
                                ->orWhere('alternate_grade_id', $grade);
                        })
                            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->whereIn('section', $sections);
                    })
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', $end_date)
                    ->count();
                $grades_data[$grade]['total_corrected_assignments'] = UserLesson::query()
                    ->whereHas('user', function (Builder $query) use ($teacher, $grade, $sections) {
                        $query->where(function (Builder $query) use ($grade) {
                            $query->where('grade_id', $grade)
                                ->orWhere('alternate_grade_id', $grade);
                        })
                            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->whereIn('section', $sections);
                    })
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', $end_date)
                    ->where('status', 'corrected')
                    ->count();
                $grades_data[$grade]['total_uncorrected_assignments'] = UserLesson::query()
                    ->whereHas('user', function (Builder $query) use ($teacher, $grade, $sections) {
                        $query->where(function (Builder $query) use ($grade) {
                            $query->where('grade_id', $grade)
                                ->orWhere('alternate_grade_id', $grade);
                        })
                            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->whereIn('section', $sections);
                    })
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', $end_date)
                    ->whereIn('status', ['pending', 'returned'])
                    ->count();

                $tracks = UserTracker::query()
                    ->whereHas('user', function (Builder $query) use ($teacher, $grade, $sections) {
                        $query->where(function (Builder $query) use ($grade) {
                            $query->where('grade_id', $grade)
                                ->orWhere('alternate_grade_id', $grade);
                        })
                            ->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->whereIn('section', $sections);
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

        }

        return view('teacher.report.usage_report', compact('sections', 'grades', 'grades_data', 'data', 'teacher', 'start_date', 'end_date'));
    }
}
