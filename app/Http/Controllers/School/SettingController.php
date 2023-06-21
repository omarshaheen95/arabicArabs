<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\Teacher;
use App\Models\User;
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
        $title = t('Dashboard');
        $school = Auth::guard('school')->user();
        $students = User::query()->where('school_id', $school->id)->count();
        $tests = UserTest::query()->whereHas('user', function (Builder $query) use($school){
            $query->where('school_id', $school->id);
        })->count();
        $teachers = Teacher::query()->where('school_id', $school->id)->count();

        return view('school.home', compact('title', 'students', 'tests', 'teachers'));
    }

    public function lang($local)
    {
        session(['lang' => $local]);
        if(Auth::guard('school')->check()){
            $user = Auth::guard('school')->user();
            $user->update([
                'local' => $local,
            ]);
        }
        app()->setLocale($local);
        return back();
    }

    public function view_profile()
    {
        $title = t('Show Profile');
        $this->validationRules = [
            'name' => 'required',
            'password' => 'nullable',
            'email' => 'required|email|unique:schools,email',
        ];
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('school.profile.profile', compact('title', 'validator'));
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('school')->user();
        $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'website' => 'required',
            'email' => 'required|email:rfc,dns|unique:schools,email,'. $user->id,
        ]);
        $data = $request->all();
        $user->update($data);
        return redirect()->route('school.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function view_password()
    {
        $title = t('Change Password');
        $this->validationRules = [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ];
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('school.profile.password', compact('title', 'validator'));
    }

    public function password(Request $request)
    {
        $user = Auth::guard('school')->user();
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
        if(Hash::check($request->get('current_password'), $user->password)) {
            $data['password'] = bcrypt($request->get('password'));
            $user->update($data);
        }else{
            return $this->redirectWith(true, null, 'Current Password Invalid', 'error');
        }

        return redirect()->route('school.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function getLevelsByGrade(Request $request,$id)
    {
        $levels = Level::query()->where('grade', $id)->get();
        $selected = $request->get('selected', 0);
        if ($selected)
        {
            $html = '<option selected disabled value="">'.t('Select Level').'</option>';
        }else{
            $html = '<option selected value="">'.t('Select Level').'</option>';
        }

        foreach ($levels as $level) {
            $html .= '<option value="'.$level->id.'">'.$level->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function getLessonsByLevel(Request $request,$id)
    {
        $lessons = Lesson::query()->where('level_id', $id)->get();
        $selected = $request->get('selected', 0);
        if ($selected)
        {
            $html = '<option selected disabled value="">'.t('Select Lesson').'</option>';
        }else{
            $html = '<option selected value="">'.t('Select Lesson').'</option>';
        }

        foreach ($lessons as $lesson) {
            $html .= '<option value="'.$lesson->id.'">'.$lesson->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function preUsageReport()
    {
        $title = t('Usage Report');
        $grades = Grade::query()->get();
        return view('school.report.pre_usage_report', compact('title', 'grades'));
    }

    public function usageReport(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $school = Auth::guard('school')->user();
        $grades = $request->get('grades', []);
        $start_date = $request->get('start_date', []);
        $end_date = $request->get('end_date', []);

        $data['total_students'] = User::query()
            ->where(function (Builder $query) use ($grades) {
                $query->whereIn('grade_id', $grades)
                    ->orWhereIn('alternate_grade_id', $grades);
            })
            ->where('school_id', $school->id)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->count();

        $data['total_teachers'] = Teacher::query()
            ->where('school_id', $school->id)
            ->whereDate('last_login', '>=', $start_date)
//            ->whereDate('last_login', '<=', $end_date)
            ->count();

        $data['top_teacher'] = Teacher::query()
            ->where('school_id', $school->id)
            ->whereDate('last_login', '>=', $start_date)
//            ->whereDate('last_login', '<=', $end_date)
            ->orderBy('passed_tests', 'desc')
            ->first();
        $data['top_student'] = User::query()
            ->where(function (Builder $query) use ($grades) {
                $query->whereIn('grade_id', $grades)
                    ->orWhereIn('alternate_grade_id', $grades);
            })
            ->where('school_id', $school->id)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
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
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->count();
        $data['total_pass_tests'] = UserTest::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
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
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
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
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->count();
        $data['total_corrected_assignments'] = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
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
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->whereIn('status', ['pending', 'returned'])
            ->count();

        $teachers = Teacher::query()
            ->where('school_id', $school->id)
            ->whereDate('last_login', '>=', $start_date)
            ->whereDate('last_login', '<=', $end_date)
            ->get();

        $tracks = UserTracker::query()
            ->whereHas('user', function (Builder $query) use ($school, $grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                })
                    ->where('school_id', $school->id);
            })
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
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
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->count();

            $grades_data[$grade]['total_teachers'] = Teacher::query()
                ->where('school_id', $school->id)
                ->whereDate('last_login', '>=', $start_date)
//                ->whereDate('last_login', '<=', $end_date)
                ->whereHas('students', function (Builder $query) use ($grade) {
                    $query->whereHas('user', function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade);
                    });
                })
                ->count();

            $grades_data[$grade]['top_teacher'] = Teacher::query()
                ->where('school_id', $school->id)
                ->whereDate('last_login', '>=', $start_date)
//                ->whereDate('last_login', '<=', $end_date)
                ->whereHas('students', function (Builder $query) use ($grade) {
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
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
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
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->count();
            $grades_data[$grade]['total_pass_tests'] = UserTest::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
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
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
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
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->count();
            $grades_data[$grade]['total_corrected_assignments'] = UserLesson::query()
                ->whereHas('user', function (Builder $query) use ($school, $grade) {
                    $query->where(function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade)
                            ->orWhere('alternate_grade_id', $grade);
                    })
                        ->where('school_id', $school->id);
                })
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
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
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
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
                ->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->latest()->get();

            if ($grades_data[$grade]['total_practice'] = $tracks->count()) {
                $grades_data[$grade]['learn'] = $tracks->where('type', 'learn')->count();
                $grades_data[$grade]['practise'] = $tracks->where('type', 'practise')->count();
                $grades_data[$grade]['test'] = $tracks->where('type', 'test')->count();
//                $grades_data[$grade]['play'] = $tracks->where('type', 'play')->count();
                $grades_data[$grade]['learn_avg'] = ($grades_data[$grade]['learn'] / $grades_data[$grade]['total_practice']) * 100;
                $grades_data[$grade]['practise_avg'] = ($grades_data[$grade]['practise'] / $grades_data[$grade]['total_practice']) * 100;
                $grades_data[$grade]['test_avg'] = ($grades_data[$grade]['test'] / $grades_data[$grade]['total_practice']) * 100;
//                $grades_data[$grade]['play_avg'] = ($grades_data[$grade]['play'] / $grades_data[$grade]['total_practice']) * 100;
            } else {
                $grades_data[$grade]['total_practice'] = 0;
                $grades_data[$grade]['learn'] = 0;
                $grades_data[$grade]['practise'] = 0;
                $grades_data[$grade]['test'] = 0;
//                $grades_data[$grade]['play'] = 0;
                $grades_data[$grade]['learn_avg'] = 0;
                $grades_data[$grade]['practise_avg'] = 0;
                $grades_data[$grade]['test_avg'] = 0;
//                $grades_data[$grade]['play_avg'] = 0;
            }
        }

        return view('school.report.usage_report', compact('grades', 'grades_data', 'data', 'school', 'teachers', 'start_date', 'end_date'));
    }
}
