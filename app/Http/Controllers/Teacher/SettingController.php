<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Story;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use App\Models\UserTest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class SettingController extends Controller
{
    public function home()
    {
        $title = 'الرئيسية';
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

    public function view_profile()
    {
        $title = t('Show Profile');
        $this->validationRules = [
            'name' => 'required',
            'password' => 'nullable',
            'email' => 'required|email|unique:schools,email',
        ];
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('teacher.profile.profile', compact('title', 'validator'));
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('teacher')->user();
        $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'website' => 'required',
            'email' => 'required|email:rfc,dns|unique:schools,email,'. $user->id,
        ]);
        $data = $request->all();
        $user->update($data);
        return redirect()->route('teacher.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function view_password()
    {
        $title = t('Change Password');
        $this->validationRules = [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ];
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('teacher.profile.password', compact('title', 'validator'));
    }

    public function password(Request $request)
    {
        $user = Auth::guard('teacher')->user();
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

        return redirect()->route('teacher.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function getLevelsByGrade($id)
    {
        $levels = Level::query()->where('grade', $id)->get();
        $html = '<option selected value="">'.t('Select Level').'</option>';
        foreach ($levels as $level) {
            $html .= '<option value="'.$level->id.'">'.$level->name.'</option>';
        }
        return response()->json(['html'=>$html]);
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

    public function getLessonsByGrade($id)
    {
        $lessons = Lesson::query()->where('grade_id', $id)->get();
        $html = '<option selected value="">اختر درس</option>';
        foreach ($lessons as $lesson) {
            $html .= '<option value="'.$lesson->id.'">'.$lesson->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }

    public function getStudentsByGrade(Request $request, $id)
    {
        $user = Auth::guard('teacher')->user();
        $section = $request->get('section', false);
        $rows = User::query()->where('school_id', $user->school_id)
            ->whereHas('teacherUser', function (Builder $query) use($user){
            $query->where('teacher_id', $user->id);
        })->where(function (Builder $query) use ($id){
            $query->where('grade', $id)->orWhere('alternate_grade', $id);
            })
            ->when($section, function (Builder $query) use($section){
                $query->where('section', $section);
            })
            ->latest()->get();
        $html = '<option value="">'.t('All').'</option>';
        foreach ($rows as $row) {
            $html .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }
        return response()->json(['html'=>$html]);
    }
}
