<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LoginSession;
use App\Models\Package;
use App\Models\School;
use App\Models\Setting;
use App\Models\Story;
use App\Models\StoryAssignment;
use App\Models\StudentStoryTest;
use App\Models\Supervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserLesson;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Excel;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show settings')->only(['settings']);
        $this->middleware('permission:edit settings')->only(['updateSettings']);
    }
    public function home()
    {
        $title = t('Dashboard');

        //Auth::guard('manager')->user()->active && Auth::guard('manager')->user()->hasDirectPermission('show statistics')
        if (Auth::guard('manager')->user()->active && Auth::guard('manager')->user()->hasDirectPermission('show statistics')) {
            $data['students'] = User::query()->count();
            $data['lessons'] = Lesson::query()->count();
//            $data['levels'] = Level::query()->count();
            $data['stories'] = Story::query()->count();
            $data['schools'] = School::query()->count();
            $data['teachers'] = Teacher::query()->count();
            $data['supervisors'] = Supervisor::query()->count();

            //get data for chart statistics for students terms and students login
            $students_login_data = LoginSession::query()->where('model_type', User::class)->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "%H:00") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
            $StudentsLogin_data = ['categories' => $students_login_data->pluck('date'), 'data' => $students_login_data->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $students_login_data->sum('counts') . ")"];

            $lessons_tests = UserTest::query()->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "%h:00 %p") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
            $LessonsTests_data = ['categories' => $lessons_tests->pluck('date'), 'data' => $lessons_tests->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $lessons_tests->sum('counts') . ")"];

            $lessons_assignments = UserAssignment::query()->groupBy('date')->orderBy('date')
                ->whereBetween('completed_at', [now()->startOfDay(), now()->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(completed_at, "%h:00 %p") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
            $LessonsAssignments_data = ['categories' => $lessons_assignments->pluck('date'), 'data' => $lessons_assignments->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $lessons_assignments->sum('counts') . ")"];


            $stories_tests = StudentStoryTest::query()->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "%h:00 %p") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
            $StoriesTests_data = ['categories' => $stories_tests->pluck('date'), 'data' => $stories_tests->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $stories_tests->sum('counts') . ")"];

            $stories_assignments = StoryAssignment::query()->groupBy('date')->orderBy('date')
                ->whereBetween('completed_at', [now()->startOfDay(), now()->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(completed_at, "%h:00 %p") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
            $StoriesAssignments_data = ['categories' => $stories_assignments->pluck('date'), 'data' => $stories_assignments->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $stories_assignments->sum('counts') . ")"];

            return view('manager.home', compact('title', 'data', 'StudentsLogin_data', 'LessonsTests_data', 'StoriesTests_data', 'LessonsAssignments_data', 'StoriesAssignments_data'));
        } else {
            return view('manager.home', compact('title'));

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
            $items_data = UserTest::query()->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        }elseif ($model == 'StoriesTests') {
            $items_data = StudentStoryTest::query()->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        }elseif ($model == 'LessonsAssignments') {
            $items_data = UserAssignment::query()->groupBy('date')->orderBy('date')
                ->whereBetween('completed_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(completed_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        } elseif ($model == 'StoriesAssignments') {
            $items_data = StoryAssignment::query()->groupBy('date')->orderBy('date')
                ->whereBetween('completed_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(completed_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        }elseif ($model == 'StudentsLogin') {
            $items_data = LoginSession::query()->where('model_type', User::class)->groupBy('date')->orderBy('date')
                ->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
                ->get(array(
                    DB::raw('DATE_FORMAT(created_at, "' . $format . '") as date'),
                    DB::raw('COUNT(*) as counts')
                ));
        }
        $collect_data = ['categories' => $items_data->pluck('date'), 'data' => $items_data->pluck('counts'), 'total' => "(" . t('Total') . ' : ' . $items_data->sum('counts') . ")"];
        return $this->sendResponse($collect_data, 'Successfully');
    }


    /**
     * Show the general settings for the manager.
     */
    public function settings()
    {
        $title = t('Show Settings');
        $settings = Setting::query()->get();
        return view('manager.setting.general', compact('settings', 'title'));
    }

    /**
     * Update settings based on the given request data.
     */
    public function updateSettings(Request $request, Factory $cache)
    {
        $settings_data = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($settings_data['settings'] as $key => $val) {
            $setting = Setting::query()->where('key', $key)->first();
            if ($setting) {
                if ($setting->type == 'file' && $request->hasFile('settings.'.$key)) {
                    $up_file = uploadFile($request->file('settings.'.$key), 'settings');
                    $file_path = $up_file['path'];
                    $setting->update([
                        'value' => $file_path,
                    ]);
                } else {
                    $setting->update([
                        'value' => $val,
                    ]);
                }
            }
        }
        $message = t('settings updated successfully');

        \Cache::forget('settings');

        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'recipients' => 'required',
            'title' => 'required',
            'content' => 'required',
            'user_id' => 'required_if:recipients,4',
        ]);

        if($request->get('recipients') == 1){

            send_push_to_topic('users',['title' => $request->get('title'), 'body' => $request->get('content')]);
        }elseif($request->get('recipients') == 2){

            send_push_to_topic('patients',['title' => $request->get('title'), 'body' => $request->get('content')]);
        }elseif($request->get('recipients') == 3){

            send_push_to_topic('doctors',['title' => $request->get('title'), 'body' => $request->get('content')]);
        }elseif($request->get('recipients') == 4){
            $user = User::query()->find($request->get('user_id'));
            if(!$user){
                return redirect()->back()->withErrors(['message'=> t('User ID Dos\'t Exists')])->withInput();
            }

            send_push_to_topic('user_'.$user->id,['title' => $request->get('title'), 'body' => $request->get('content')]);
        }

        return redirect()->back()->with('message', t('Notification Successfully Send'))->with('m-class', 'success');
    }

    public function lang($local)
    {
        session(['lang' => $local]);
        if(Auth::guard('manager')->check()){
            $user = Auth::guard('manager')->user();
            $user->update([
                'local' => $local,
            ]);
        }
        app()->setLocale($local);
        return back();
    }

    public function updateTeacherStatistics(Request $request)
    {
        $teachers = Teacher::query()->get();
        foreach ($teachers as $teacher)
        {
            $user_lessons = UserLesson::query()->whereHas('user', function (Builder $query) use ($teacher){
                $query->whereHas('teacherUser', function (Builder $query) use ($teacher){
                    $query->where('teacher_id', $teacher->id);
                });
            })->get();

            $user_tests = UserTest::query()->whereHas('user', function (Builder $query) use ($teacher){
                $query->whereHas('teacherUser', function (Builder $query) use ($teacher){
                    $query->where('teacher_id', $teacher->id);
                });
            })->get();

            $teacher->update([
               'pending_tasks' => $user_lessons->where('status', 'pending')->count(),
               'corrected_tasks' => $user_lessons->where('status', 'corrected')->count(),
               'returned_tasks' => $user_lessons->where('status', 'returned')->count(),
               'passed_tests' => $user_tests->where('status', 'Pass')->count(),
               'failed_tests' => $user_tests->where('status', 'Fail')->count(),
            ]);
        }

        return true;
    }
}
