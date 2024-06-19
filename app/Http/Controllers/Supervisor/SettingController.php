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
            $data['image'] = $this->uploadFile($request->file('image'), 'profile_images/supervisors');
        }
        $user->update($data);
        return redirect()->route('supervisor.home')->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function editPassword()
    {
        $title = t('Change Password');
        return view('supervisor.profile.password', compact('title'));
    }

    public function updatePassword(SupervisorPasswordRequest $request)
    {
        $data = $request->validated();
        $user = Auth::guard('supervisor')->user();
        if (Hash::check($request->get('old_password'), $user->password)) {
            $data['password'] = bcrypt($request->get('password'));
            $user->update($data);
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
