<?php

namespace App\Http\Controllers\Manager;

use App\Exports\StudentInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\UserRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\MatchResult;
use App\Models\OptionResult;
use App\Models\Package;
use App\Models\Payment;
use App\Models\School;
use App\Models\SortResult;
use App\Models\StudentTest;
use App\Models\Teacher;
use App\Models\TrueFalseResult;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\UserTracker;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = User::query()
                ->with(['school', 'package', 'grade'])
                ->latest()
                ->search($request);

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('school', function ($row) {
                    return optional($row->school)->name;
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'غير مدفوع' : Carbon::parse($row->active_to)->format('Y-m-d');
                })
                ->addColumn('package', function ($row) {
                    return optional($row->package)->name;
                })
                ->addColumn('grade', function ($row) {
                    return optional($row->grade)->name;
                })
                ->addColumn('actions', function ($row) {
                    $edit_url = route('manager.user.edit', $row->id);
                    $show_url = route('manager.user.show', $row->id);
                    $login_url = route('manager.user.login', $row->id);
                    return view('manager.setting.btn_actions', compact('row', 'edit_url', 'show_url', 'login_url'));
                })
                ->make();
        }
        $title = "عرض المستخدمين";
        $schools = School::query()->get();
        $packages = Package::query()->get();
        $grades = Grade::query()->get();
        return view('manager.user.index', compact('title', 'schools', 'packages', 'grades'));
    }

    public function duplicateIndex(Request $request)
    {
        if (request()->ajax()) {
            $rows = User::query()->with(['school', 'package'])
                ->search($request)
                ->whereIn('name', function ($q) {
                    $q->select('name')
                        ->from('users')
                        ->whereNull('deleted_at')
                        ->groupBy('name')
                        ->havingRaw('COUNT(*) > 1');
                })->orderBy('name')
                ->latest('id');

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('school', function ($row) {
                    return optional($row->school)->name;
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'غير مدفوع' : optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('package', function ($row) {
                    return optional($row->package)->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = "عرض تكرار المستخدمين";
        $schools = School::query()->get();
        $packages = Package::query()->get();
        return view('manager.user.duplicate_index', compact('title', 'schools', 'packages'));
    }

    public function create()
    {
        $title = "إضافة مستخدم";
        $schools = School::query()->get();
        $grades = Grade::query()->get();
        $years_learning = [0,1,2,3,4,5,6,7,8,9,10,11,12];
        $packages = Package::query()->get();
        return view('manager.user.edit', compact('schools', 'grades', 'title', 'packages', 'years_learning'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'users');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = bcrypt($request->get('password', 123456));
        $data['manager_id'] = Auth::guard('manager')->user()->id;
        $data['active_from'] = now();
        $data['year_id'] = Year::query()->latest()->first()->id;
        $user = User::query()->create($data);
        $teacher_id = $request->get('teacher_id', false);
        if ($teacher_id) {
            $user->teacherUser()->updateOrCreate([
                'teacher_id' => $teacher_id,
            ], [
                'teacher_id' => $teacher_id,
            ]);
        }
        return $this->redirectWith(false, 'manager.user.index', self::ADDMESSAGE);
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $title = "تعديل المستخدم";
        $user = User::query()->findOrFail($id);
        $schools = School::query()->get();
        $grades = Grade::query()->get();
        $years_learning = [0,1,2,3,4,5,6,7,8,9,10,11,12];
        $packages = Package::query()->get();
        $teachers = Teacher::query()->where('school_id', $user->school_id)->get();
        return view('manager.user.edit', compact('user', 'schools', 'grades', 'title', 'packages', 'teachers', 'years_learning'));
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'users');
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $user->password;
        $user->update($data);
        $teacher_id = $request->get('teacher_id', false);
//        dd($teacher_id);
        if ($teacher_id) {
            $user->teacherUser()->forceDelete();
            $user->teacherUser()->updateOrCreate([
                'teacher_id' => $teacher_id,
            ], [
                'teacher_id' => $teacher_id,
            ]);
        } else {
            $user->teacherUser()->delete();
        }
        return $this->redirectWith(false, 'manager.user.index', self::EDITMESSAGE);
    }

    public function destroy($id)
    {
        $user = User::query()->findOrFail($id);
//        TrueFalseResult::query()->where('user_id', $user->id)->forceDelete();
//        SortResult::query()->where('user_id', $user->id)->forceDelete();
//        MatchResult::query()->where('user_id', $user->id)->forceDelete();
//        OptionResult::query()->where('user_id', $user->id)->forceDelete();
//        Payment::query()->where('user_id', $user->id)->forceDelete();
        $user->forceDelete();


        return $this->redirectWith(true, null, self::DELETEMESSAGE);
    }

    public function destroyDuplicate($id)
    {
        $user = User::query()->findOrFail($id);
//        TrueFalseResult::query()->where('user_id', $user->id)->forceDelete();
//        SortResult::query()->where('user_id', $user->id)->forceDelete();
//        MatchResult::query()->where('user_id', $user->id)->forceDelete();
//        OptionResult::query()->where('user_id', $user->id)->forceDelete();
//        Payment::query()->where('user_id', $user->id)->forceDelete();
        $user->forceDelete();

        if (request()->ajax()) {
            return $this->sendResponse(null, self::DELETEMESSAGE);
        } else {
            return $this->redirectWith(true, null, self::DELETEMESSAGE);
        }


    }

    public function exportStudentsExcel(Request $request)
    {
        $file_name = "Students Information.xlsx";
        if ($request->get('school_id', false)) {
            $school = School::query()->findOrFail($request->get('school_id'));
            $file_name = $school->name . " Students Information.xlsx";
        }
        return (new StudentInformation($request, $request->get('school_id', false)))
            ->download($file_name);
    }

    public function correctTest()
    {
        $users_tests = UserTest::query()->select(['user_id', 'lesson_id', DB::raw('COUNT( * ) AS c')])->groupBy(['user_id', 'lesson_id'])->having('c', '>', 1)->get();


//        foreach ($users_tests as $user_tests)
//        {
//            $tests = UserTest::query()->where('user_id', $user_tests->user_id)->where('lesson_id', $user_tests->lesson_id)
//                ->where('corrected', 1)->where('total', '>=', '40')->orderByDesc('total')->get();
//
//            if(optional($tests->first())->total >= 40)
//            {
//                UserTest::query()->where('user_id', $user_tests->user_id)->where('lesson_id', $user_tests->lesson_id)
//                    ->where('id', '<>', $tests->first()->id)->update([
//                        'approved' => 0,
//                    ]);
//            }
//
//        }

        dd($users_tests);
    }

    public function userGrades()
    {
        return 'done';
        //->whereDoesntHave('user_grades')
        $users = User::query()->where('school_id', 1971)->get();
        foreach ($users as $user) {
//            $user->user_grades()->create(['grade' => $user->grade]);
            $user->update([
                'grade_id' => $user->grade_id - 1,
            ]);
        }
        return 'done';
    }

    public function review(Request $request, $id)
    {
        $title = "مراجعة سجل الطالب";
        $user = User::query()->findOrFail($id);
        if ($user->teacherUser && $user->teacherUser->teacher) {
            $teacher = $user->teacherUser->teacher;
        } else {
            $teacher = false;
        }
        if ($request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after:start_date',
            ], [
                'end_date.after' => t('The end date must be greater than the start date'),
            ]);
        }
        $start_date = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $grade = $request->get('grade', $user->grade_id);
        $tests = UserTest::query()->where('user_id', $id)->count();
        $passed_tests = UserTest::query()->where('user_id', $id)->where('total', '>=', 40)->count();
        if ($user->user_grades()->count()) {
            $grades = $user->user_grades()->pluck('grade_id')->unique()->values()->all();
        } else {
            $grades[] = $user->grade;
        }
        $tracks = UserTracker::query()->where('user_id', $user->id)->whereHas('lesson', function (Builder $query) use ($grade) {
//            $query->whereHas('level', function (Builder $query) use ($grade) {
                $query->where('grade_id', $grade);
//            });
        })->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->latest()->get();

        $data['total'] = $tracks->count();
        if ($data['total']) {
            $data['learn'] = $tracks->where('type', 'learn')->count();
            $data['practise'] = $tracks->where('type', 'practise')->count();
            $data['test'] = $tracks->where('type', 'test')->count();
            $data['play'] = $tracks->where('type', 'play')->count();
            $data['learn_avg'] = ($data['learn'] / $data['total']) * 100;
            $data['practise_avg'] = ($data['practise'] / $data['total']) * 100;
            $data['test_avg'] = ($data['test'] / $data['total']) * 100;
            $data['play_avg'] = ($data['play'] / $data['total']) * 100;
        } else {
            $data['learn'] = 0;
            $data['practise'] = 0;
            $data['test'] = 0;
            $data['play'] = 0;
            $data['learn_avg'] = 0;
            $data['practise_avg'] = 0;
            $data['test_avg'] = 0;
            $data['play_avg'] = 0;
        }


        return view('manager.user.review', compact('user', 'teacher',
            'tests', 'passed_tests', 'grades', 'start_date', 'end_date', 'tracks', 'title', 'data'));

    }

    public function report(Request $request, $id)
    {
        $title = "تقرير الطالب";
        $student = User::query()->findOrFail($id);
        if ($student->teacherUser && $student->teacherUser->teacher) {
            $teacher = $student->teacherUser->teacher;
        } else {
            $teacher = null;
        }

        $start_date = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $grade = $request->get('grade', $student->grade);

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
        foreach ($student_tests as $lesson) {
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
            if ($user_tracker) {
                $lesson_info['games'] = round(($user_games / $user_tracker) * 100, 1);
                $lesson_info['tests'] = round(($user_tests / $user_tracker) * 100, 1);
                $lesson_info['trainings'] = round(($user_training / $user_tracker) * 100, 1);
                $lesson_info['learnings'] = round(($user_learning / $user_tracker) * 100, 1);
                $lesson_info['tracker'] = $user_tracker;
            } else {
                $lesson_info['games'] = 0;
                $lesson_info['tests'] = 0;
                $lesson_info['trainings'] = 0;
                $lesson_info['learnings'] = 0;
                $lesson_info['tracker'] = 0;
            }

            $user_test = UserTest::query()->where('user_id', $student->id)->where('lesson_id', $lesson)->latest('total')->first();
            $lesson_info['user_test'] = $user_test;
            if (isset($user_test) && !is_null($user_test->start_at) && !is_null($user_test->end_at)) {
                $time1 = new \DateTime($user_test->start_at);
                $time2 = new \DateTime($user_test->end_at);
                $interval = $time1->diff($time2);

                $lesson_info['time_consumed'] = $interval->format('%i minute(s)');

            } else {
                $lesson_info['time_consumed'] = '-';
            }

            $user_lesson = UserLesson::query()->where('user_id', $student->id)->where('lesson_id', $lesson)->where('status', 'corrected')->first();
            $lesson_info['user_lesson'] = $user_lesson;

            $lesson_info['lesson'] = Lesson::query()->find($lesson);

            array_push($lessons_info, $lesson_info);
        }

        $lessons_info = array_chunk($lessons_info, 2);
        return view('manager.user.report', compact('student', 'teacher', 'lessons_info'));

    }

    public function updateUsers()
    {
        $users = User::query()->where('email', 'like', '% %')->orWhere('mobile', 'like', '% %')->get();
        foreach ($users as $user) {
            $user->update([
                'email' => str_replace(' ', '', $user->email),
                'mobile' => str_replace(' ', '', $user->mobile),
            ]);
        }
        return $users;
    }

    public function cards(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);



        $students = User::query()->with(['school', 'teacherUser'])->search($request)
            ->orderBy('grade_id')->get();

        $students = $students->chunk(6);
        $school = School::query()->find($request->get('school_id'));
        return view('general.user.cards', compact('students', 'school'));
    }
    public function cardsQR(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);



        $students = User::query()->with(['school', 'teacherUser'])->search($request)
            ->orderBy('grade_id')->get();

        $students = $students->chunk(8);
        $school = School::query()->find($request->get('school_id'));
        return view('general.user.cards_qr', compact('students', 'school'));
    }

    public function updateStudentSchoolExpireDate()
    {
        $students = User::query()
            ->whereIn('school_id', [54])
//            ->whereIn('grade', [7,8])
//            ->whereDate('active_to', '<', '2022-03-30')
            ->update([
//                'alternate_grade' => 1,
                'active_to' => Carbon::createFromFormat('Y-m-d', '2022-09-10'),
            ]);
//        Log::info('users count: '. count($students));
//        foreach ($students as $student)
//        {
////            $email = str_replace(' ', '', $student->email);
////            $email = strtolower(str_replace('albasmaschool.ae', '@albasmaschool.ae', $student->email));
//            $student->update([
//                'email' => $email,
//            ]);
//        }
//            ->update([
//            'active_to' => Carbon::createFromFormat('Y-m-d', '2022-03-30'),
//        ]);
//        foreach ($students as $student)
//        {
//            $student->update([
//                'grade' => $student->grade + 1,
//            ]);
//            foreach ($student->user_grades as $user_grade){
//                $user_grade->update([
//                    'grade' => $user_grade->grade + 1,
//                ]);
//            }
//        }
        return $students;
    }

    public function updateMarks(Request $request)
    {
        $request->validate([
            'from' => 'required',
            'to' => 'required',
        ]);

        $students = User::query()->where('id', '>=', $request->get('from', false))
            ->where('id', '<', $request->get('to', false))->get();

//        dd($students);

        foreach ($students as $student) {
            $student_tests_lessons = UserTest::query()->where('user_id', $student->id)->pluck('lesson_id')->unique()->values()->all();
            foreach ($student_tests_lessons as $student_tests_lesson) {
                $lesson = Lesson::query()->find($student_tests_lesson);
                if ($lesson) {
                    $mark = $lesson->level->level_mark;
                    $student_tests = UserTest::query()->where('total', '>=', $mark)
                        ->where('user_id', $student->id)
                        ->where('lesson_id', $student_tests_lesson)->orderByDesc('total')->get();

                    UserTest::query()->where('user_id', $student->id)
                        ->where('lesson_id', $student_tests_lesson)
                        ->where('total', '<', $mark)->update([
                            'status' => 'Fail',
                        ]);
                    UserTest::query()->where('user_id', $student->id)
                        ->where('lesson_id', $student_tests_lesson)
                        ->where('total', '>=', $mark)->update([
                            'status' => 'Pass',
                        ]);

                    if (count($student_tests)) {
                        UserTest::query()->where('user_id', $student->id)
                            ->where('lesson_id', $student_tests_lesson)
                            ->where('id', '<>', $student_tests->first()->id)->update([
                                'approved' => 0,
                            ]);
                        UserTest::query()->where('user_id', $student->id)
                            ->where('lesson_id', $student_tests_lesson)
                            ->where('id', $student_tests->first()->id)->update([
                                'approved' => 1,
                            ]);
                    }
                }

            }
        }
        return true;
    }

    public function deleteSchoolStudents($school_id)
    {
        $users = User::query()->whereDate('created_at', now())->where('school_id', $school_id)->forceDelete();
//        $users_ids = $users->pluck('id');
//        dd($users_ids);
//        dd($users_ids);
//        TrueFalseResult::query()->whereIn('user_id', $users_ids)->forceDelete();
//        SortResult::query()->whereIn('user_id', $users_ids)->forceDelete();
//        MatchResult::query()->whereIn('user_id', $users_ids)->forceDelete();
//        OptionResult::query()->whereIn('user_id', $users_ids)->forceDelete();
//        Payment::query()->whereIn('user_id', $users_ids)->forceDelete();
//        $users->forceDelete();

        return true;

    }

    public function userLogin($id)
    {
        $user = User::query()->findOrFail($id);
        Auth::guard('web')->loginUsingId($id);
        return redirect()->route('home');
    }
}
