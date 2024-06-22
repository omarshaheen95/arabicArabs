<?php

namespace App\Http\Controllers\School;

use App\Classes\GeneralFunctions;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\SchoolPasswordRequest;
use App\Http\Requests\School\SchoolProfileRequest;
use App\Models\Grade;
use App\Models\LoginSession;
use App\Models\StoryAssignment;
use App\Models\StudentStoryTest;
use App\Models\UserTest;
use App\Models\Supervisor;
use App\Models\UserAssignment;
use App\Models\UserLesson;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserTracker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class SettingController extends Controller
{
    public function home(Request $request)
    {
        $title = t('Dashboard');

       if (Auth::guard('school')->user()->active){
           $students = User::query()->filter($request)->count();
           $tests = UserTest::query()->filter($request)->count();
           $teachers = Teacher::query()->filter($request)->count();
           $supervisors = Supervisor::query()->where('school_id', Auth::guard('school')->id())->count();

           //get data for chart statistics for students terms and students login
           $students_login_data = LoginSession::query()
               ->whereHasMorph('model', User::class, function (Builder $query) {
                   $query->where('school_id', Auth::guard('school')->id());
               })
               ->where('model_type', User::class)->groupBy('date')->orderBy('date')
               ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
               ->get(array(
                   DB::raw('DATE_FORMAT(created_at, "%H:00") as date'),
                   DB::raw('COUNT(*) as counts')
               ));
           $StudentsLogin_data = ['categories' => $students_login_data->pluck('date'), 'data' => $students_login_data->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $students_login_data->sum('counts') . ")"];

           $lessons_tests = UserTest::query()->whereRelation('user', 'school_id', Auth::guard('school')->id())->groupBy('date')->orderBy('date')
               ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
               ->get(array(
                   DB::raw('DATE_FORMAT(created_at, "%h:00 %p") as date'),
                   DB::raw('COUNT(*) as counts')
               ));
           $LessonsTests_data = ['categories' => $lessons_tests->pluck('date'), 'data' => $lessons_tests->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $lessons_tests->sum('counts') . ")"];

           $lessons_assignments = UserAssignment::query()->whereRelation('user', 'school_id', Auth::guard('school')->id())->groupBy('date')->orderBy('date')
               ->whereBetween('completed_at', [now()->startOfDay(), now()->endOfDay()])
               ->get(array(
                   DB::raw('DATE_FORMAT(completed_at, "%h:00 %p") as date'),
                   DB::raw('COUNT(*) as counts')
               ));
           $LessonsAssignments_data = ['categories' => $lessons_assignments->pluck('date'), 'data' => $lessons_assignments->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $lessons_assignments->sum('counts') . ")"];


           $stories_tests = StudentStoryTest::query()->whereRelation('user', 'school_id', Auth::guard('school')->id())->groupBy('date')->orderBy('date')
               ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
               ->get(array(
                   DB::raw('DATE_FORMAT(created_at, "%h:00 %p") as date'),
                   DB::raw('COUNT(*) as counts')
               ));
           $StoriesTests_data = ['categories' => $stories_tests->pluck('date'), 'data' => $stories_tests->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $stories_tests->sum('counts') . ")"];

           $stories_assignments = StoryAssignment::query()->whereRelation('user', 'school_id', Auth::guard('school')->id())->groupBy('date')->orderBy('date')
               ->whereBetween('completed_at', [now()->startOfDay(), now()->endOfDay()])
               ->get(array(
                   DB::raw('DATE_FORMAT(completed_at, "%h:00 %p") as date'),
                   DB::raw('COUNT(*) as counts')
               ));
           $StoriesAssignments_data = ['categories' => $stories_assignments->pluck('date'), 'data' => $stories_assignments->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $stories_assignments->sum('counts') . ")"];

           return view('school.home', compact('title', 'students', 'supervisors', 'tests', 'teachers', 'StudentsLogin_data', 'LessonsTests_data', 'StoriesTests_data', 'LessonsAssignments_data', 'StoriesAssignments_data'));

       }else{
           return view('school.home', compact('title'));
       }
    }

    public function chartStatisticsData(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'model' => 'required',
        ]);
        if (Carbon::parse($request->start_date)->diffInMonths(Carbon::parse($request->end_date)) > 1) {
            $format = "%Y-%m";
        } elseif (Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) > 1) {
            $format = "%Y-%m-%d";
        } else {
            $format = "%h:00 %p";
        }
        $model = $request->get('model');
        $items_data = collect();
        if ($model == 'LessonsTests') {
            $items_data = UserTest::query()->whereRelation('user', 'school_id', Auth::guard('school')->id())->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        } elseif ($model == 'StoriesTests') {
            $items_data = StudentStoryTest::query()->whereRelation('user', 'school_id', Auth::guard('school')->id())->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        } elseif ($model == 'LessonsAssignments') {
            $items_data = UserAssignment::query()->whereRelation('user', 'school_id', Auth::guard('school')->id())->groupBy('date')->orderBy('date')
                ->whereBetween('completed_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(completed_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        } elseif ($model == 'StoriesAssignments') {
            $items_data = StoryAssignment::query()->whereRelation('user', 'school_id', Auth::guard('school')->id())->groupBy('date')->orderBy('date')
                ->whereBetween('completed_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(completed_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        }  elseif ($model == 'StudentsLogin') {
            $items_data = LoginSession::query()
                //where has user relation and user school_id equal to school id
                ->whereHasMorph('model', User::class, function (Builder $query) {
                    $query->where('school_id', Auth::guard('school')->id());
                })
                ->where('model_type', User::class)->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        }
        $collect_data = ['categories' => $items_data->pluck('date'), 'data' => $items_data->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $items_data->sum('counts') . ")"];
        return $this->sendResponse($collect_data, 'Successfully');
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

    public function editProfile()
    {
        $title = t('Edit Profile');
        $school = Auth::guard('school')->user();
        return view('school.profile.profile', compact('title','school'));
    }

    public function updateProfile(SchoolProfileRequest $request)
    {
        $data =  $request->validated();
        if (request()->hasFile('logo')) {
            $data['logo']  = $this->uploadFile(request()->file('logo'), 'profile_images/schools');
        }
        Auth::guard('school')->user()->update($data);
        return redirect()->back()->with('message', 'Successfully Updated');
    }

    public function editPassword()
    {
        $title = t('Edit Password');
        return view('school.profile.password', compact('title'));
    }

    public function updatePassword(SchoolPasswordRequest $request)
    {
        $data = $request->validated();
        $manager = Auth::guard('manager')->user();
        if (Hash::check($request->get('old_password'), $manager->password)) {
            $data['password'] = bcrypt($request->get('password'));
            $manager->update($data);
            return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
        } else {
            return redirect()->back()->withErrors([t('Current Password Invalid')])->with('message', t('Current Password Invalid'))->with('m-class', 'error');
        }
    }



    public function preUsageReport()
    {
        $title = t('Usage Report');
        $grades = Grade::query()->get();
        return view('general.reports.usage_report.pre_usage_report', compact('title', 'grades'));
    }

    public function usageReport(Request $request)
    {
        $general = new GeneralFunctions();
        return $general->usageReport($request);
    }
}
