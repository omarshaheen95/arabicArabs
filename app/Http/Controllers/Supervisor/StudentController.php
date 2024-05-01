<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Supervisor;

use App\Exports\Development\StudentStoryTestExport;
use App\Exports\StudentInformation;
use App\Exports\Development\StudentTestExport;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Question;
use App\Models\StoryAssignment;
use App\Models\StoryQuestion;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $supervisor = Auth::guard('supervisor')->user();
        if (request()->ajax()) {
            $rows = User::query()->with(['teacher', 'grade'])
                ->latest()->search($request);

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('teacher', function ($row) {
                    return optional($row->teacher)->name ?? "غير مسند";
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'unpaid' : optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('grade', function ($row) {
                    return optional($row->grade)->name;
                })
                ->make();
        }
        $title = "قائمة الطلاب";
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query) use ($supervisor) {
            $query->where('supervisor_id', $supervisor->id);
        })->get();
        $sections = User::query()
            ->whereHas('teacher', function (Builder $query) use ($supervisor) {
                $query->whereHas('supervisor_teachers', function (Builder $query) use ($supervisor) {
                    $query->where('supervisor_id', $supervisor->id);
                });
            })->whereNotNull('section')
            ->select('section')
            ->orderBy('section')
            ->get()->pluck('section')->unique()->values();
        return view('supervisor.user.index', compact('title', 'teachers', 'sections'));
    }

    public function exportStudentsExcel(Request $request)
    {
        $supervisor = Auth::guard('supervisor')->user();
        $file_name = $supervisor->school->name . " Students Information.xlsx";
        return (new StudentInformation($request, $supervisor->school_id))
            ->download($file_name);
    }

    public function studentLessonTest(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserTest::query()->with(['lesson.grade', 'user'])->search($request)
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('user', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('lesson', function ($row) {
                    return $row->lesson->name;
                })
                ->addColumn('grade', function ($row) {
                    return $row->lesson->grade_id;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('total', function ($row) {
                    return $row->total;
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('supervisor.student.studentLessonTestShow', $row->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
                })
                ->make();
        }
        $title = "اختبارات الدروس";
        $grades = Grade::query()->get();
        $supervisor = Auth::guard('supervisor')->user();
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query) use ($supervisor) {
            $query->where('supervisor_id', $supervisor->id);
        })->get();
        return view('supervisor.student_test.index', compact('title', 'grades', 'teachers'));
    }

    public function studentLessonTestExport(Request $request)
    {
        $supervisor = Auth::guard('supervisor')->user();
        return (new StudentTestExport($supervisor->school_id))
            ->download('Students tests.xlsx');
    }

    public function studentLessonTestShow($id)
    {
        $student_test = UserTest::query()->with(['lesson', 'user'])->search(request())->findOrFail($id);
        $grade = $student_test->lesson->grade_id;
        $questions = Question::query()->where('lesson_id', $student_test->lesson_id)->get();
        return view('teacher.student_test.student_test_result', compact('student_test', 'grade', 'questions'));
    }

    public function studentStoryTest(Request $request)
    {
        if (request()->ajax()) {
            $rows = StudentStoryTest::query()->with(['story', 'user'])->search($request)
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('user', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('story', function ($row) {
                    return $row->story->name;
                })
                ->addColumn('grade', function ($row) {
                    return $row->user->grade->name;
                })
                ->addColumn('story_grade', function ($row) {
                    return $row->story->grade_ar_name;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('total', function ($row) {
                    return $row->total;
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('supervisor.student.studentStoryTestShow', $row->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
                })
                ->make();
        }
        $title = "اختبارات القصص";
        $grades = Grade::query()->get();
        $supervisor = Auth::guard('supervisor')->user();
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query) use ($supervisor) {
            $query->where('supervisor_id', $supervisor->id);
        })->get();
        return view('supervisor.student_test.stories', compact('title', 'grades', 'teachers'));
    }

    public function studentStoryTestExport(Request $request)
    {
        return (new StudentStoryTestExport($request))
            ->download('Students Stories tests.xlsx');
    }

    public function studentStoryTestShow($id)
    {
        $student_test = StudentStoryTest::query()->with(['story', 'user'])->search(request())->findOrFail($id);
        $grade = $student_test->story->grade;
        $questions = StoryQuestion::query()->where('story_id', $student_test->story_id)->get();
        return view('teacher.student_test.student_test_result', compact('student_test', 'grade', 'questions'));
    }

    public function studentLessonAssignment(Request $request)
    {
        if (request()->ajax())
        {
            $rows = UserAssignment::query()
                ->with(['user', 'lesson'])
                ->has('user')
                ->has('lesson')
                ->search($request)
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('user', function ($row){
                    return $row->user->name;
                })
                ->addColumn('lesson', function ($row) {
                    return $row->lesson->name;
                })
                ->addColumn('grade', function ($row) {
                    return$row->lesson->grade_id;
                })
                ->addColumn('done_tasks_assignment', function ($row) {
                    return !$row->tasks_assignment ? '-': ($row->done_tasks_assignment ? '<span class="text-success">مكتمل</span>':'<span class="text-red">غير مكتمل</span>');
                })
                ->addColumn('done_test_assignment', function ($row) {
                    return !$row->test_assignment ? '-': ($row->done_test_assignment ? '<span class="text-success">مكتمل</span>':'<span class="text-red">غير مكتمل</span>');
                })
                ->addColumn('completed', function ($row) {
                    return $row->completed ? '<span class="text-success">مكتمل</span>':'<span class="text-red">غير مكتمل</span>';
                })
                ->addColumn('submit_status', function ($row) {
                    return $row->submit_status;
                })
                ->make();
        }
        $title = "متابعة واجبات الدروس";
        $grades = Grade::query()->get();
        $supervisor = Auth::guard('supervisor')->user();
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query) use ($supervisor) {
            $query->where('supervisor_id', $supervisor->id);
        })->get();
        return view('supervisor.student_assignment.lesson', compact('title', 'grades', 'teachers'));
    }

    public function studentStoryAssignment(Request $request)
    {
        if (request()->ajax())
        {
            $rows = StoryAssignment::query()
                ->with(['user', 'story'])
                ->has('user')
                ->has('story')
                ->search($request)
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('user', function ($row){
                    return $row->user->name;
                })
                ->addColumn('grade', function ($row){
                    return $row->user->grade->name;
                })
                ->addColumn('story', function ($row) {
                    return $row->story->name;
                })
                ->addColumn('story_grade', function ($row) {
                    return$row->story->grade_ar_name;
                })
                ->addColumn('done_tasks_assignment', function ($row) {
                    return !$row->tasks_assignment ? '-': ($row->done_tasks_assignment ? '<span class="text-success">مكتمل</span>':'<span class="text-red">غير مكتمل</span>');
                })
                ->addColumn('done_test_assignment', function ($row) {
                    return !$row->test_assignment ? '-': ($row->done_test_assignment ? '<span class="text-success">مكتمل</span>':'<span class="text-red">غير مكتمل</span>');
                })
                ->addColumn('completed', function ($row) {
                    return $row->completed ? '<span class="text-success">مكتمل</span>':'<span class="text-red">غير مكتمل</span>';
                })
                ->addColumn('submit_status', function ($row) {
                    return $row->submit_status;
                })
                ->make();
        }
        $title = "متابعة واجبات القصص";
        $grades = Grade::query()->get();
        $supervisor = Auth::guard('supervisor')->user();
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query) use ($supervisor) {
            $query->where('supervisor_id', $supervisor->id);
        })->get();
        return view('supervisor.student_assignment.story', compact('title', 'grades', 'teachers'));
    }

}
