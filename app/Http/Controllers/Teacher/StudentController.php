<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\StudentInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\UserRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\School;
use App\Models\StudentTest;
use App\Models\Teacher;
use App\Models\TeacherUser;
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
        $teacher = Auth::guard('teacher')->user();
        if (request()->ajax())
        {
            $name = $request->get('name', false);
            $grade = $request->get('grade', false);
            $section = $request->get('section', false);
            $rows = User::query()->with(['grade'])->when($name, function (Builder $query) use ($name){
                $query->where('name', 'like', '%'.$name.'%');
            })->when($grade, function (Builder $query) use ($grade){
                $query->where('grade_id', $grade);
            })->when($section, function (Builder $query) use ($section){
                $query->where('section', $section);
            })->where('school_id', $teacher->school_id)->whereDoesntHave('teacherUser')->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })

                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'غير مدفوع':optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('grade', function ($row) {
                    return $row->grade->name;
                })
                ->addColumn('check', function ($row) {
                    return $row->check;
                })
                ->make();
        }
        $title = "عرض الطلاب";
        $grades = Grade::query()->get();
        return view('teacher.user.index', compact('title', 'grades'));
    }

    public function myStudents(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        if (request()->ajax())
        {
            $name = $request->get('name', false);
            $grade = $request->get('grade', false);
            $section = $request->get('section', false);
            $rows = User::query()->with(['grade'])->when($name, function (Builder $query) use ($name){
                $query->where('name', 'like', '%'.$name.'%');
            })->when($grade, function (Builder $query) use ($grade){
                $query->where('grade_id', $grade);
            })->when($section, function (Builder $query) use ($section){
                $query->where('section', $section);
            })->where('school_id', $teacher->school_id)->whereHas('teacherUser', function (Builder $query) use($teacher){
                $query->where('teacher_id', $teacher->id);
            })->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row){
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString():'';
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'غير مدفوع':optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('grade', function ($row) {
                    return $row->grade->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->teacher_action_buttons;
                })
                ->addColumn('check', function ($row) {
                    return $row->check;
                })

                ->make();
        }
        $title = "طلابي";
        $grades = Grade::query()->get();
        return view('teacher.user.my_students', compact('title', 'grades'));
    }

    public function edit($id)
    {
        $title = t('Edit User');
        $teacher = Auth::guard('teacher')->user();
        $user = User::query()->whereHas('teacherUser', function (Builder $query) use($teacher){
            $query->where('teacher_id', $teacher->id);
        })->findOrFail($id);
        return view('teacher.user.edit', compact('user', 'title'));
    }

    public function update(UserRequest $request, $id)
    {
        $teacher = Auth::guard('teacher')->user();
        $user = User::query()->whereHas('teacherUser', function (Builder $query) use($teacher){
            $query->where('teacher_id', $teacher->id);
        })->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image'))
        {
            $data['image'] = $this->uploadImage($request->file('image'), 'users');
        }
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)):$user->password;
        $user->update($data);
        return $this->redirectWith(false, 'teacher.student.my_students', 'Successfully Updated');
    }

    public function exportStudentsExcel(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $file_name = $teacher->school->name . " Students Information.xlsx";
        return (new StudentInformation($request, $teacher->school_id))
            ->download($file_name);
    }

    public function studentAssign(Request $request)
    {
        $id = $request->get('user_id', false);
        $teacher = Auth::guard('teacher')->user();
        if($id)
        {
            if (is_array($id))
            {
                foreach ($id as $student)
                {
                    TeacherUser::query()->updateOrCreate(['user_id' => $student], [
                        'teacher_id' => $teacher->id,
                    ]);
                }
                return $this->sendResponse(null, "تم الإسناد بنجاح");
            }else{
                TeacherUser::query()->updateOrCreate(['user_id' => $id], [
                    'teacher_id' => $teacher->id,
                ]);
            }
        }

        return redirect()->route('teacher.student.index')->with('message', "تم الإسناد بنجاح");
    }

    public function deleteStudentAssign(Request $request)
    {
        $id = $request->get('user_id', false);
        $teacher = Auth::guard('teacher')->user();
        if($id)
        {
            if (is_array($id))
            {
                TeacherUser::query()->where('teacher_id', $teacher->id)->whereIn('user_id', $id)->delete();
                return $this->sendResponse(null, t('Successfully deleted'));
            }else{
                TeacherUser::query()->where('teacher_id', $teacher->id)->where('user_id', $id)->delete();
            }
        }

        return redirect()->route('teacher.student.index')->with('message', "تم الإلغاء بنجاح");
    }

    public function review(Request $request,$id)
    {
        $title = t('Student review');
        $teacher = Auth::guard('teacher')->user();
        $user = User::query()->whereHas('teacherUser', function (Builder $query) use($teacher){
            $query->where('teacher_id', $teacher->id);
        })->findOrFail($id);

        if ($user->teacherUser && $user->teacherUser->teacher)
        {
            $teacher = $user->teacherUser->teacher;
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
        $tests = StudentTest::query()->where('user_id', $id)->count();
        $passed_tests = StudentTest::query()->where('user_id', $id)->where('total', '>=', 40)->count();
        if ($user->user_grades()->count())
        {
            $grades = $user->user_grades()->pluck('grade_id')->unique()->values()->all();
        }else{
            $grades[] = $user->grade;
        }
        $tracks = UserTracker::query()->where('user_id', $user->id)->whereHas('lesson', function (Builder $query) use ($grade){
            $query->whereHas('level', function (Builder $query) use($grade){
                $query->where('grade_id', $grade);
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




        return view('teacher.user.review', compact('user', 'teacher',
            'tests', 'passed_tests', 'grades', 'start_date', 'end_date', 'tracks', 'title', 'data'));

    }

    public function report(Request $request,$id)
    {
        $title = t('Student report');
        $teacher = Auth::guard('teacher')->user();
        $student = User::query()->whereHas('teacherUser', function (Builder $query) use($teacher){
            $query->where('teacher_id', $teacher->id);
        })->findOrFail($id);
        if ($student->teacherUser && $student->teacherUser->teacher)
        {
            $teacher = $student->teacherUser->teacher;
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

            $user_test = StudentTest::query()->where('user_id', $student->id)->where('lesson_id', $lesson)->latest('total')->first();
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
        return view('teacher.user.report', compact('student', 'teacher', 'lessons_info'));

    }

    public function cards(Request $request)
    {
        $name = $request->get('name', false);
        $grade = $request->get('grade', false);
        $section = $request->get('section', false);
        $teacher = Auth::guard('teacher')->user();
        $students = User::query()->when($name, function (Builder $query) use ($name){
            $query->where('name', 'like', '%'.$name.'%');
        })->when($grade, function (Builder $query) use ($grade){
            $query->where('grade_id', $grade);
        })->when($section, function (Builder $query) use ($section){
            $query->where('section', $section);
        })->where('school_id', $teacher->school_id)->whereHas('teacherUser', function (Builder $query) use($teacher){
            $query->where('teacher_id', $teacher->id);
        })->latest()->get();



        $students = $students->chunk(6);
        $school = $teacher->school;
        return view('general.user.cards', compact('students', 'school'));
    }
    public function cardsQR(Request $request)
    {
        $name = $request->get('name', false);
        $grade = $request->get('grade', false);
        $section = $request->get('section', false);
        $teacher = Auth::guard('teacher')->user();
        $students = User::query()->when($name, function (Builder $query) use ($name){
            $query->where('name', 'like', '%'.$name.'%');
        })->when($grade, function (Builder $query) use ($grade){
            $query->where('grade_id', $grade);
        })->when($section, function (Builder $query) use ($section){
            $query->where('section', $section);
        })->where('school_id', $teacher->school_id)->whereHas('teacherUser', function (Builder $query) use($teacher){
            $query->where('teacher_id', $teacher->id);
        })->latest()->get();



        $students = $students->chunk(8);
        $school = $teacher->school;
        return view('general.user.cards_qr', compact('students', 'school'));
    }


}
