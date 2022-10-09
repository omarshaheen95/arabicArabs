<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Imports\ImportUserExcel;
use App\Imports\TeacherImport;
use App\Models\Client;
use App\Models\Item;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Order;
use App\Models\Package;
use App\Models\School;
use App\Models\Setting;
use App\Models\StudentTest;
use App\Models\Supervisor;
use App\Models\Supplier;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Excel;

class SettingController extends Controller
{
    public function home()
    {
        $title = "الرئيسة";
        $supervisors = Supervisor::query()->count();
        $students = User::query()->count();
        $lessons = Lesson::query()->count();
        $tests = 0;//UserTest::query()->count();
        $schools = School::query()->count();
        $teachers = Teacher::query()->count();

        $users_date = User::query()->groupBy('date')->orderBy('date', 'DESC')->whereMonth('created_at', now())
            ->whereYear('created_at', now())
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as counts')
            ));
        $tests_date = [];
        $tests_date = UserTest::query()->groupBy('date')->orderBy('date', 'DESC')->whereMonth('created_at', now())
            ->whereYear('created_at', now())
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as counts')
            ));




        return view('manager.home', compact('title', 'supervisors','students', 'tests', 'lessons', 'teachers', 'schools', 'users_date', 'tests_date'));
    }

    public function settings()
    {
        $title = t('Show Settings');
        $setting = Setting::query()->firstOrNew([
            'id' => 1
        ]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name:$local"] = 'required';
        }
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.setting.general', compact('setting', 'title', 'validator'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->all();
        $setting = Setting::query()->findOrNew('1');
        if ($request->hasFile('logo'))
        {
            $data['logo'] = $this->uploadImage($request->file('logo'), 'logos');
        }
        if ($request->hasFile('logo_min'))
        {
            $data['logo_min'] = $this->uploadImage($request->file('logo_min'), 'logos');
        }
        $setting->update($data);
        Artisan::call('cache:clear');
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

    public function view_profile()
    {
        $title = t('Show Profile');
        return view('manager.setting.profile', compact('title'));
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('manager')->user();
        $this->validationRules['password'] = 'nullable';
        $this->validationRules['email'] = 'required|email:rfc,dns|unique:managers,email,'.$user->id;
        $request->validate($this->validationRules);

        $data = $request->all();
        if($request->has(['password', 'password_confirmation']) && !empty($request->get('password'))){
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $data['password'] = bcrypt($data['password']);
        }else{
            $data['password'] = $user->password;
        }
        $user->update($data);
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function importUserExcelView(Request $request)
    {
        $title = t('Users Import');
        $schools = School::query()->get();
        $packages = Package::query()->get();
        return view('manager.setting.import_users',compact('title', 'schools', 'packages'));
    }

    public function importUserExcel(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'package_id' => 'required|exists:packages,id',
            'import_file' => 'required',
            'active_to' => 'required',
            'last_of_email' => 'required',
            'default_mobile' => 'required',
            'country_code' => 'required',
            'short_country' => 'required',
        ]);
        $importedFile = new ImportUserExcel($request);
        Excel::import($importedFile, $request->file('import_file'));
        $assessmentsStudents = $importedFile->getDuplicateEmails();
        return $assessmentsStudents;
        return $this->redirectWith(true, null, 'تم استيراد الطلاب بنجاح');
    }

    public function importTeachersExcelView(Request $request)
    {
        $title = t('Teachers Import');
        $schools = School::query()->get();
        return view('manager.setting.import_teachers',compact('title', 'schools'));
    }

    public function importTeachersExcel(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);
        Excel::import(new TeacherImport($request), $request->file('import_file'));
        return $this->redirectWith(true, null, 'تم استيراد المعلمين بنجاح');
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

    public function getTeacherBySchool(Request $request,$id)
    {
        $rows = Teacher::query()->where('school_id', $id)->get();
        $selected = $request->get('selected', 0);
        if ($selected)
        {
            $html = '<option selected disabled value="">'.t('Select teacher').'</option>';
        }else{
            $html = '<option selected value="">'.t('Select teacher').'</option>';
        }

        foreach ($rows as $row) {
            $html .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }
        return response()->json(['html'=>$html]);
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
