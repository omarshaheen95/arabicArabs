<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\UserTest;
use App\Models\Teacher;
use App\Models\User;
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
}
