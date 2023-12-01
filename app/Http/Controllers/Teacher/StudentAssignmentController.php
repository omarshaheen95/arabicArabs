<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UserAssignmentRequest;
use App\Http\Requests\Teacher\UserStoryAssignmentRequest;
use App\Models\Grade;
use App\Models\StoryAssignment;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserStoryAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        if (request()->ajax())
        {
            $username = $request->get('username', false);
            $grade = $request->get('grade', false);
            $lesson_id = $request->get('lesson_id', false);
            $start_at = $request->get('start_at', false);
            $end_at = $request->get('end_at', false);
            $status = $request->get('status', false);

            $rows = UserAssignment::query()
                ->with(['user', 'lesson'])
                ->whereHas('user', function (Builder $query) use ($teacher, $username){
                $query->whereHas('teacherUser', function (Builder $query) use($teacher){
                    $query->where('teacher_id', $teacher->id);
                });
                $query->when($username, function (Builder $query) use ($username){
                    $query->where('name', 'like', '%'.$username.'%');
                });
            })
                ->when($grade, function (Builder $query) use ($grade){
                $query->whereHas('lesson', function (Builder $query) use ($grade){
                        $query->where('grade_id', $grade);
                });
            })
                ->when($lesson_id, function (Builder $query) use ($lesson_id){
                $query->where('lesson_id', $lesson_id);
            })
                ->when($start_at, function (Builder $query) use ($start_at){
                $query->whereDate('created_at', '>=', $start_at);
            })
                ->when($end_at, function (Builder $query) use ($end_at){
                $query->whereDate('created_at', '<=', $end_at);
            })
                ->when($status == 1, function (Builder $query) {
                $query->where('completed', 1);
            })
                ->when($status == 2, function (Builder $query) {
                $query->where('completed', 0);
            })
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
                ->addColumn('actions', function ($row) {
                    return $row->teacher_action_buttons;
                })
                ->addColumn('check', function ($row) {
                    return $row->check;
                })
                ->make();
        }
        $title = "متابعة واجبات الدروس";
        $grades = Grade::query()->get();
        return view('teacher.student_assignment.index', compact('title', 'grades'));
    }

    public function store(UserAssignmentRequest $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $students_array = $request->get('assignment_students', []);
        $lesson = $request->get('assignment_lesson');
        $tasks_assignment = $request->get('tasks_assignment', false);
        $test_assignment = $request->get('test_assignment', false);
        $section = $request->get('section', false);

        if (!$test_assignment && !$tasks_assignment)
        {
            return $this->redirectWith(true, null, 'must select tasks assignment or test assignment at least', 'error');
        }
        if ($section)
        {
            $students =  User::query()
                ->when($section, function (Builder $query) use ($section){
                    $query->where('section', $section);
                })
                ->when(count($students_array), function (Builder $query) use ($students_array){
                    $query->whereIn('id', $students_array);
                })
                ->where('school_id', $teacher->school_id)
                ->whereHas('teacherUser', function (Builder $query) use($teacher){
                    $query->where('teacher_id', $teacher->id);
                })->get();
            foreach ($students as $student)
            {
                if ($student != '')
                {
//                    UserAssignment::query()->firstOrCreate([
//                        'user_id' => $student->id,
//                        'lesson_id' => $lesson,
//                    ],[
//                        'user_id' => $student->id,
//                        'lesson_id' => $lesson,
//                        'test_assignment' => $test_assignment,
//                    ]);
                    UserAssignment::query()->create([
                        'user_id' => $student->id,
                        'lesson_id' => $lesson,
                        'test_assignment' => $test_assignment,
                        'deadline' => $request->get('deadline', null),
                    ]);
                }
            }
        }else{
            foreach ($students_array as $student)
            {
                if ($student != '')
                {
//                    UserAssignment::query()->firstOrCreate([
//                        'user_id' => $student,
//                        'lesson_id' => $lesson,
//                    ],[
//                        'user_id' => $student,
//                        'lesson_id' => $lesson,
//                        'test_assignment' => $test_assignment,
//                    ]);
                    UserAssignment::query()->create([
                        'user_id' => $student,
                        'lesson_id' => $lesson,
                        'test_assignment' => $test_assignment,
                        'deadline' => $request->get('deadline', null),
                    ]);
                }
            }
        }

        return $this->redirectWith(true, null, 'تم إضافة التعيين بنجاح');
    }

    public function indexStory(Request $request)
    {

        $teacher = Auth::guard('teacher')->user();
        if (request()->ajax())
        {
            $username = $request->get('username', false);
            $grade = $request->get('grade', false);
            $story_id = $request->get('story_id', false);
            $start_at = $request->get('start_at', false);
            $end_at = $request->get('end_at', false);
            $status = $request->get('status', false);

            $rows = StoryAssignment::query()
                ->with(['user', 'story'])
                ->whereHas('user', function (Builder $query) use ($teacher, $username){
                $query->whereHas('teacherUser', function (Builder $query) use($teacher){
                    $query->where('teacher_id', $teacher->id);
                });
                $query->when($username, function (Builder $query) use ($username){
                    $query->where('name', 'like', '%'.$username.'%');
                });
            })->when($grade, function (Builder $query) use ($grade){
                $query->whereHas('story', function (Builder $query) use ($grade){
                    $query->where('grade', $grade);
                });
            })->when($story_id, function (Builder $query) use ($story_id){
                $query->where('story_id', $story_id);
            })->when($start_at, function (Builder $query) use ($start_at){
                $query->where('created_at', '<=', $start_at);
            })->when($end_at, function (Builder $query) use ($end_at){
                $query->where('created_at', '>=', $end_at);
            })->when($status == 1, function (Builder $query) {
                $query->where('completed', 1);
            })->when($status == 2, function (Builder $query) {
                $query->where('completed', 0);
            })->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('user', function ($row){
                    return $row->user->name;
                })
                ->addColumn('story', function ($row) {
                    return $row->story->name;
                })
                ->addColumn('grade', function ($row) {
                    return t("Grade") ." ". $row->story->grade;
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
        return view('teacher.student_story_assignment.index', compact('title', 'grades'));
    }

    public function storeStory(UserStoryAssignmentRequest $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $students_array = $request->get('assignment_students', []);
        $story = $request->get('assignment_story');
        $test_assignment = 1;
        $section = $request->get('section', false);

//        if (!$test_assignment && !$tasks_assignment)
//        {
//            return $this->redirectWith(true, null, 'must select tasks assignment or test assignment at least', 'error');
//        }
        if ($section)
        {
            $students =  User::query()
                ->when($section, function (Builder $query) use ($section){
                    $query->where('section', $section);
                })
                ->when(count($students_array), function (Builder $query) use ($students_array){
                    $query->whereIn('id', $students_array);
                })
                ->where('school_id', $teacher->school_id)->whereHas('teacherUser', function (Builder $query) use($teacher){
                    $query->where('teacher_id', $teacher->id);
                })->get();
            foreach ($students as $student)
            {
                if ($student != '')
                {
//                    StoryAssignment::query()->firstOrCreate([
//                        'user_id' => $student->id,
//                        'story_id' => $story,
//                    ],[
//                        'user_id' => $student->id,
//                        'story_id' => $story,
//                        'test_assignment' => $test_assignment,
//                    ]);
                    StoryAssignment::query()->create([
                        'user_id' => $student->id,
                        'story_id' => $story,
                        'test_assignment' => $test_assignment,
                        'deadline' => $request->get('deadline', null),
                    ]);

                }
            }
        }else{
            foreach ($students_array as $student)
            {
                if ($student != '')
                {
//                    StoryAssignment::query()->firstOrCreate([
//                        'user_id' => $student,
//                        'story_id' => $story,
//                    ],[
//                        'user_id' => $student,
//                        'story_id' => $story,
//                        'test_assignment' => $test_assignment,
//                    ]);
                    StoryAssignment::query()->create([
                        'user_id' => $student,
                        'story_id' => $story,
                        'test_assignment' => $test_assignment,
                        'deadline' => $request->get('deadline', null),
                    ]);
                }
            }
        }

        return $this->redirectWith(true, null, 'Successfully Added');
    }

    public function deleteLessonAssignment(Request $request, $id = null)
    {
        if ($request->has('rows_id')) {
            $rows = $request->get('rows_id', []);
            UserAssignment::query()->whereIn('id', $rows)->delete();
            return $this->sendResponse(null, "تم الحذف بنجاح");
        }else{
            UserAssignment::query()->findOrFail($id)->delete();
            return redirect()->back()->with('message', "تم الحذف بنجاح");
        }
    }
}
