<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\StudentTestExport;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Question;
use App\Models\UserAssignment;
use App\Models\UserTest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentTestController extends Controller
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

            $rows = UserTest::query()->with(['lesson', 'user'])->whereHas('user', function (Builder $query) use ($teacher, $username){
                $query->whereHas('teacherUser', function (Builder $query) use($teacher){
                    $query->where('teacher_id', $teacher->id);
                });
                $query->when($username, function (Builder $query) use ($username){
                    $query->where('name', 'like', '%'.$username.'%');
                });
            })->when($grade, function (Builder $query) use ($grade){
                $query->whereHas('lesson', function (Builder $query) use ($grade){
                    $query->whereHas('level', function (Builder $query) use ($grade){
                        $query->where('grade', $grade);
                    });
                });
            })->when($lesson_id, function (Builder $query) use ($lesson_id){
                $query->where('lesson_id', $lesson_id);
            })->when($start_at, function (Builder $query) use ($start_at){
                $query->where('created_at', '<=', $start_at);
            })->when($end_at, function (Builder $query) use ($end_at){
                $query->where('created_at', '>=', $end_at);
            })->when($status, function (Builder $query) use ($status) {
                $query->where('status', $status);
            })->latest();

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
                    return $row->lesson->grade_id;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('total', function ($row) {
                    return $row->total;
                })
                ->addColumn('actions', function ($row) {
                    return $row->teacher_action_buttons;
                })

                ->make();
        }
        $title = "اختبارات الطلاب";
        $grades = Grade::query()->get();
        return view('teacher.student_test.index', compact('title', 'grades'));
    }

    public function exportStudentsTestsExcel(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        return (new StudentTestExport($teacher->school_id, $teacher->id))
            ->download('Students tests.xlsx');
    }

    public function show($id)
    {
        $title = "عرض اختبار طالب";
        $teacher = Auth::guard('teacher')->user();
        $user_test = UserTest::query()->with(['lesson', 'user'])->whereHas('user', function (Builder $query) use ($teacher) {
            $query->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            });
        })->findOrFail($id);
//        dd($user_test);

        return view('teacher.student_test.show',compact('title', 'user_test'));
    }

    public function preview($id)
    {
        $teacher = Auth::guard('teacher')->user();
        $student_test = UserTest::query()->with(['lesson', 'user'])->whereHas('user', function (Builder $query) use ($teacher) {
            $query->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            });
        })->findOrFail($id);
        $grade = $student_test->lesson->grade_id;
        $questions = Question::query()->where('lesson_id', $student_test->lesson_id)->get();

        return view('teacher.student_test.student_test_result', compact('student_test', 'grade', 'questions'));
    }
    public function correct(Request $request, $id)
    {
        $request->validate([
            'mark' => 'required|max:100|min:0',
        ]);
        $teacher = Auth::guard('teacher')->user();
        $user_test = UserTest::query()->with(['lesson', 'user'])->whereHas('user', function (Builder $query) use ($teacher) {
            $query->whereHas('teacherUser', function (Builder $query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            });
        })->whereHas('lesson', function (Builder $query) {
            $query->whereIn('lesson_type', ['writing', 'speaking']);
        })->findOrFail($id);

        $record = null;
        if(isset($_FILES['record1']) && $_FILES['record1']['type'] != 'text/plain' && $_FILES['record1']['error'] <= 0){
            $new_name = uniqid().'.'.'wav';
            $destination = public_path('uploads/teachers_records_result');
            move_uploaded_file($_FILES['record1']['tmp_name'], $destination .'/'. $new_name);
            $record = 'uploads'.DIRECTORY_SEPARATOR.'teachers_records_result'.DIRECTORY_SEPARATOR.$new_name;
        }
        $success_mark = $user_test->lesson->success_mark;
        $mark = $request->get('mark');
        $user_test->update([
            'approved' => 1,
            'corrected' => 1,
            'total' => $mark,
            'status' => $mark >= $success_mark ? 'Pass':'Fail',
            'feedback_message' => $request->get('teacher_message', null),
            'feedback_record' => $record,
        ]);

        $student_tests = UserTest::query()
            ->where('user_id',  $user_test->user_id)
            ->where('lesson_id', $user_test->lesson_id)
            ->orderByDesc('total')->get();



        if (optional($student_tests->first())->total >= $mark)
        {
            UserTest::query()
                ->where('user_id', $user_test->user_id)
                ->where('lesson_id', $user_test->lesson_id)
                ->where('id', '<>', $student_tests->first()->id)->update([
                    'approved' => 0,
                ]);

            UserTest::query()
                ->where('user_id', $user_test->user_id)
                ->where('lesson_id', $user_test->lesson_id)
                ->where('id',  $student_tests->first()->id)->update([
                    'approved' => 1,
                ]);
        }

        $user_test->user->user_tracker()->create([
            'lesson_id' => $user_test->lesson_id,
            'type' => 'test',
            'color' => 'danger',
            'start_at' => $user_test->start_at,
            'end_at' => $user_test->end_at,
        ]);

        if ($user_test->user->teacherUser)
        {
            updateTeacherStatistics($user_test->user->teacherUser->teacher_id);
        }

        $user_assignment = UserAssignment::query()
            ->where('user_id', $user_test->user_id)
            ->where('lesson_id', $user_test->lesson_id)
            ->where('test_assignment', 1)
            ->where('done_test_assignment', 0)
            ->first();

        if ($user_assignment)
        {
            $user_assignment->update([
                'done_test_assignment' => 1,
            ]);

            if (($user_assignment->tasks_assignment && $user_assignment->done_tasks_assignment) || !$user_assignment->tasks_assignment){
                $user_assignment->update([
                    'completed' => 1,
                ]);
            }
        }

        return $this->redirectWith(false, 'teacher.students_tests.index', 'تم اعتماد التصحيح بنجاح');

    }
}
