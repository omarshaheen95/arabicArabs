<?php

namespace App\Http\Controllers\Supervisor;

use App\Classes\GeneralFunctions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\SupervisorPasswordRequest;
use App\Http\Requests\Supervisor\SupervisorProfileRequest;
use App\Models\Grade;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserTracker;
use App\Models\Year;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use App\Traits\UpdatesPassword;

class SettingController extends Controller
{
    use UpdatesPassword;

    public function home()
    {
        $title = t('Dashboard');
        $supervisor = Auth::guard('supervisor')->user();
        $students = User::query()->whereHas('teacher', function (Builder $query) use($supervisor){
            $query->whereHas('supervisor_teachers', function (Builder $query) use($supervisor){
                $query->where('supervisor_id', $supervisor->id);
            });
        })->count();
        $tests = UserTest::query()->whereHas('user', function (Builder $query) use($supervisor){
            $query->whereHas('teacher', function (Builder $query) use($supervisor){
                $query->whereHas('supervisor_teachers', function (Builder $query) use($supervisor){
                    $query->where('supervisor_id', $supervisor->id);
                });
            });
        })->count();
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query) use($supervisor){
            $query->where('supervisor_id', $supervisor->id);
        })->count();

        return view('supervisor.home', compact('title', 'students', 'tests', 'teachers'));
    }


    public function editProfile()
    {
        $title = t('Show Profile');
        $supervisor = Auth::guard('supervisor')->user();
        $this->validationRules = [
            'image' => 'nullable',
            'name' => 'required',
            'email' => 'required|email|unique:supervisors,email,'. $supervisor->id,
        ];
        return view('supervisor.profile.profile', compact('title','supervisor'));
    }

    public function updateProfile(SupervisorProfileRequest $request)
    {
        $user = Auth::guard('supervisor')->user();
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'profile_images/supervisors')['path'];
        }
        $user->update($data);
        return redirect()->route('supervisor.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function editPassword()
    {
        $title = t('Change Password');
        $reason = $this->passwordChangeReason('supervisor');
        $forced = $reason !== null;
        return view('supervisor.profile.password', compact('title', 'forced', 'reason'));
    }

    public function updatePassword(SupervisorPasswordRequest $request)
    {
        return $this->applyPasswordUpdate($request, 'supervisor');
    }

    public function preUsageReport()
    {
        $title = t('Usage Report');
        $grades = Grade::query()->get();
        $years = Year::query()->get();
        try {
            $date_range = checkDateRangeForCurrentYear(now());
        } catch (\Exception $e) {
            $date_range = [];
        }
        return view('general.reports.usage_report.pre_usage_report', compact('title', 'grades', 'years', 'date_range'));
    }

    public function usageReport(Request $request)
    {
        $general = new GeneralFunctions();
        return $general->usageReport($request);
    }


    public function teacherPreUsageReport()
    {
        $title = t('Usage Report');
        $grades = Grade::query()->get();
        $years = Year::query()->get();
        try {
            $date_range = checkDateRangeForCurrentYear(now());
        } catch (\Exception $e) {
            $date_range = [];
        }
        $teachers = Teacher::query()->filter()->get();
        $url = route('supervisor.report.teacher_pre_usage_report');
        return view('general.reports.usage_report.pre_usage_report', compact('title','teachers','url', 'grades', 'years', 'date_range'));
    }

    public function teacherUsageReport(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $teacher = Teacher::query()->findOrFail($request->get('teacher_id'));
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
