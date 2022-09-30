<?php

namespace App\Http\Controllers\School;

use App\Exports\StudentInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\UserRequest;
use App\Models\Lesson;
use App\Models\MatchResult;
use App\Models\OptionResult;
use App\Models\Payment;
use App\Models\School;
use App\Models\SortResult;
use App\Models\UserTest;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\TrueFalseResult;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTracker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::guard('school')->user();
        if (request()->ajax())
        {
            $name = $request->get('name', false);
            $grade = $request->get('grade', false);
            $teacher = $request->get('teacher', false);
            $section = $request->get('section', false);
            $rows = User::query()->when($name, function (Builder $query) use ($name){
                $query->where('name', 'like', '%'.$name.'%');
            })->when($grade, function (Builder $query) use ($grade){
                $query->where('grade', $grade);
            })->when($teacher, function (Builder $query) use ($teacher){
                $query->whereHas('teacher_student', function (Builder $query) use($teacher){
                    $query->where('teacher_id', $teacher);
                });
            })->when($section, function (Builder $query) use ($section){
                $query->where('section', $section);
            })->where('school_id', $school->id)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row){
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString():'';
                })
                ->addColumn('teacher', function ($row) {
                    return optional(optional($row->teacher_student)->teacher)->name ?? t('Unsigned');
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'unpaid':optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('grade', function ($row) {
                    return $row->grade ? t("Grade") ." $row->grade":'';
                })
                ->addColumn('actions', function ($row) {
                    return $row->school_action_buttons;
                })
                ->make();
        }
        $title = t('Show Users');
        $teachers = Teacher::query()->where('school_id', $school->id)->get();
        return view('school.user.index', compact('title', 'teachers'));
    }

    public function edit($id)
    {
        $title = t('Edit User');
        $school = Auth::guard('school')->user();
        $user = User::query()->where('school_id', $school->id)->findOrFail($id);
        $teachers = Teacher::query()->where('school_id', $school->id)->get();
        return view('school.user.edit', compact('user', 'teachers', 'title'));
    }

    public function update(UserRequest $request, $id)
    {
        $school = Auth::guard('school')->user();
        $user = User::query()->where('school_id', $school->id)->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image'))
        {
            $data['image'] = $this->uploadImage($request->file('image'), 'users');
        }
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)):$user->password;
        $user->update($data);
        $teacher_id = $request->get('teacher_id', false);
        if ($teacher_id)
        {
            $user->teacher_student()->updateOrCreate([
                'teacher_id' => $teacher_id,
            ],[
                'teacher_id' => $teacher_id,
            ]);
        }else{
            $user->teacher_student()->delete();
        }
        return $this->redirectWith(false, 'school.student.index', 'Successfully Updated');
    }

    public function destroy($id)
    {
        $school = Auth::guard('school')->user();
        $user = User::query()->where('school_id', $school->id)->findOrFail($id);
        TrueFalseResult::query()->where('user_id', $user->id)->forceDelete();
        SortResult::query()->where('user_id', $user->id)->forceDelete();
        MatchResult::query()->where('user_id', $user->id)->forceDelete();
        OptionResult::query()->where('user_id', $user->id)->forceDelete();
        Payment::query()->where('user_id', $user->id)->forceDelete();
        $user->forceDelete();

        return $this->redirectWith(true, null, 'Successfully Deleted');
    }


    public function exportStudentsExcel(Request $request)
    {
        $school = Auth::guard('school')->user();
        $file_name = "$school->name Students Information.xlsx";
        return (new StudentInformation($school->id))
            ->download($file_name);
    }

    public function review(Request $request,$id)
    {
        $title = t('Student review');
        $school = Auth::guard('school')->user();
        $user = User::query()->where('school_id', $school->id)->findOrFail($id);
        if ($user->teacher_student && $user->teacher_student->teacher)
        {
            $teacher = $user->teacher_student->teacher;
        }else{
            $teacher = false;
        }
        if ($request->has('end_date'))
        {
            $request->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after:start_date',
            ],[
                'end_date.after' => t('The end date must be greater than the start date'),
            ]);
        }
        $start_date = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $grade = $request->get('grade',$user->grade);
        $tests = UserTest::query()->where('user_id', $id)->count();
        $passed_tests = UserTest::query()->where('user_id', $id)->where('total', '>=', 40)->count();
        if ($user->user_grades()->count())
        {
            $grades = $user->user_grades()->pluck('grade')->unique()->values()->all();
        }else{
            $grades[] = $user->grade;
        }
        $tracks = UserTracker::query()->where('user_id', $user->id)->whereHas('lesson', function (Builder $query) use ($grade){
            $query->whereHas('level', function (Builder $query) use($grade){
                $query->where('grade', $grade);
            });
        })->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->latest()->get();

        $data['total'] = $tracks->count();
        if ($data['total']){
            $data['learn'] = $tracks->where('type', 'learn')->count();
            $data['practise'] = $tracks->where('type', 'practise')->count();
            $data['test'] = $tracks->where('type', 'test')->count();
            $data['play'] = $tracks->where('type', 'play')->count();
            $data['learn_avg'] = ($data['learn'] / $data['total']) * 100;
            $data['practise_avg'] = ($data['practise'] / $data['total']) * 100;
            $data['test_avg'] = ($data['test'] / $data['total']) * 100;
            $data['play_avg'] = ($data['play'] / $data['total']) * 100;
        }else{
            $data['learn'] = 0;
            $data['practise'] = 0;
            $data['test'] = 0;
            $data['play'] = 0;
            $data['learn_avg'] = 0;
            $data['practise_avg'] = 0;
            $data['test_avg'] = 0;
            $data['play_avg'] = 0;
        }




        return view('school.user.review', compact('user', 'teacher',
            'tests', 'passed_tests', 'grades', 'start_date', 'end_date', 'tracks', 'title', 'data'));

    }

    public function report(Request $request,$id)
    {
        $title = t('Student report');
        $school = Auth::guard('school')->user();
        $student = User::query()->where('school_id', $school->id)->findOrFail($id);
        if ($student->teacher_student && $student->teacher_student->teacher)
        {
            $teacher = $student->teacher_student->teacher;
        }else{
            $teacher = false;
        }

        $start_date = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $grade = $request->get('grade',$student->grade);

        $student_tests = UserTracker::query()->where('user_id', $student->id)
//            ->whereDate('created_at', '>=', $start_date)
//            ->whereDate('created_at', '<=', $end_date)
            ->pluck('lesson_id')->unique()->values()->all();
        $user_games = 0;
        $user_tests = 0;
        $user_learning = 0;
        $user_training = 0;
        $user_tracker = 0;
        $lessons_info = [];
        foreach ($student_tests as $lesson)
        {
            $lesson_info = [];
            $user_games = UserTracker::query()->where('type', 'play')->where('user_id', $student->id)
                ->where('lesson_id', $lesson)->count();
            $user_tests = UserTracker::query()->where('type', 'test')->where('user_id', $student->id)
                ->where('lesson_id', $lesson)->count();
            $user_learning = UserTracker::query()->where('type', 'learn')->where('user_id', $student->id)
                ->where('lesson_id', $lesson)->count();
            $user_training = UserTracker::query()->where('type', 'practise')->where('user_id', $student->id)
                ->where('lesson_id', $lesson)->count();

            $user_tracker = UserTracker::query()->where('user_id', $student->id)->where('lesson_id', $lesson)->count();
            if ($user_tracker)
            {
                $lesson_info['games'] = round(($user_games / $user_tracker) * 100, 1);
                $lesson_info['tests'] = round(($user_tests / $user_tracker) * 100, 1);
                $lesson_info['trainings'] = round(($user_training / $user_tracker) * 100, 1);
                $lesson_info['learnings'] = round(($user_learning / $user_tracker) * 100, 1);
                $lesson_info['tracker'] = $user_tracker;
            }else{
                $lesson_info['games'] = 0;
                $lesson_info['tests'] = 0;
                $lesson_info['trainings'] = 0;
                $lesson_info['learnings'] = 0;
                $lesson_info['tracker'] = 0;
            }

            $user_test = UserTest::query()->where('user_id', $student->id)->where('lesson_id', $lesson)->latest('total')->first();
            $lesson_info['user_test'] = $user_test;
            if (isset($user_test) && !is_null($user_test->start_at) && !is_null($user_test->end_at))
            {
                $time1 = new \DateTime($user_test->start_at);
                $time2 = new \DateTime($user_test->end_at);
                $interval = $time1->diff($time2);

                $lesson_info['time_consumed'] = $interval->format('%i minute(s)');

            }else{
                $lesson_info['time_consumed'] = '-';
            }

            $user_lesson = UserLesson::query()->where('user_id', $student->id)->where('lesson_id', $lesson)->where('status', 'corrected')->first();
            $lesson_info['user_lesson'] = $user_lesson;

            $lesson_info['lesson'] = Lesson::query()->find($lesson);

            array_push($lessons_info,$lesson_info);
        }

        $lessons_info = array_chunk($lessons_info, 2);
        return view('school.user.report', compact('student', 'teacher', 'lessons_info'));

    }

    public function cards(Request $request)
    {
        $name = $request->get('name', false);
        $grade = $request->get('grade', false);
        $teacher_id = $request->get('teacher_id', false);
        $section = $request->get('section', false);
        $school_id = Auth::guard('school')->user()->id;
        $students = User::query()->when($name, function (Builder $query) use ($name){
            $query->where('name', 'like', '%'.$name.'%');
        })->when($grade, function (Builder $query) use ($grade){
            $query->where('grade', $grade);
        })->when($school_id, function (Builder $query) use ($school_id){
            $query->where('school_id', $school_id);
        })->when($teacher_id, function (Builder $query) use ($teacher_id){
            $query->whereHas('teacher_student', function (Builder $query) use ($teacher_id){
                $query->where('teacher_id', $teacher_id);
            });
        })->when($section, function (Builder $query) use ($section){
            $query->where('section', $section);
        })->orderBy('grade')->get();

        $students = $students->chunk(6);
        $school = School::query()->find($school_id);
        return view('general.user.cards', compact('students', 'school'));
    }



}
