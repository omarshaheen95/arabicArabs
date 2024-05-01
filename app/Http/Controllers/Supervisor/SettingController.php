<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Story;
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
        $title = 'لوحة التحكم';
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


    public function view_profile()
    {
        $title = t('Show Profile');
        $user = Auth::guard('supervisor')->user();
        $this->validationRules = [
            'name' => 'required',
            'email' => 'required|email|unique:supervisors,email,'. $user->id,
        ];
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('supervisor.profile.profile', compact('title', 'validator'));
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('supervisor')->user();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:supervisors,email,'. $user->id,
        ]);
        $data = $request->all();
        $user->update($data);
        return redirect()->route('supervisor.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function view_password()
    {
        $title = t('Change Password');
        $this->validationRules = [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ];
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('supervisor.profile.password', compact('title', 'validator'));
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

        return redirect()->route('supervisor.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function getStoriesByGrade($id)
    {
        $levels = Story::query()->where('grade', $id)->get();
        $html = '<option selected value="">'.t('Select Story').'</option>';
        foreach ($levels as $level) {
            $html .= '<option value="'.$level->id.'">'.$level->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function getLessonsByGrade(Request $request, $id)
    {
        $lesson_type = $request->get('lesson_type', null);
        $lessons = Lesson::query()->when($lesson_type, function(Builder $query) use($lesson_type){
            $query->where('lesson_type', $lesson_type);
        })->where('grade_id', $id)->get();
        $html = '<option selected value="">اختر درس</option>';
        foreach ($lessons as $lesson) {
            $html .= '<option value="'.$lesson->id.'">'.$lesson->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

}
