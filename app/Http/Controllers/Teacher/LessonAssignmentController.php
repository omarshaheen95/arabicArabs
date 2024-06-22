<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Teacher;

use App\Exports\StudentAssignmentExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\LessonAssignmentRequest;
use App\Models\Grade;
use App\Models\School;
use App\Models\User;
use App\Models\UserAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LessonAssignmentController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserAssignment::query()->has('user')->with(['user.grade','lesson.grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {
                    $teacher = optional($row->user->teacher)->name ? optional($row->user->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name .'<span class="fw-bold text-primary pe-1 ms-2">' . t('Section') . ':</span>' . $row->user->section . '</div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('lesson', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Lesson') . ':</span>' . $row->lesson->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->lesson->grade->grade_name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $tasks_status = $row->done_tasks_assignment ? '<span class="badge badge-primary">' . t('Completed') . '</span>' : '<span class="badge badge-danger">' . t('Uncompleted') . '</span>';
                    $test_status = $row->done_test_assignment ? '<span class="badge badge-primary">' . t('Completed') . '</span>' : '<span class="badge badge-danger">' . t('Uncompleted') . '</span>';
                    $status = $row->completed ? '<span class="badge badge-primary">' . t('Completed') . '</span>' : '<span class="badge badge-danger">' . t('Uncompleted') . '</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Tasks') . ':</span>' . $tasks_status . '</div>' .
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Test') . ':</span>' . $test_status . '</span>' . '</div>' .
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Status') . ':</span>' . $status . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('dates', function ($row) {
                    $deadline = $row->deadline?Carbon::parse($row->deadline)->format('Y-m-d'):'-';

                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Assigned in') . ':</span>' . $row->created_at->format('Y-m-d H:i') . '</div>' .
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Deadline') . ':</span>' . $deadline .'</div>' .
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Submit Status') . ':</span>' . $row->submit_status . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Lessons Assignments');
        $grades = Grade::query()->get();

        return view('teacher.lessons_assignments.index', compact('title','grades'));
    }

    public function create()
    {
        $title = t('Add New Assignment');
        $schools = School::query()->where('active', 1)->get();
        $grades = Grade::query()->get();
        return view('teacher.lessons_assignments.create', compact('title','grades', 'schools'));
    }

    public function store(LessonAssignmentRequest $request)
    {
        $data = $request->validated();
        $students_array = $data['students'];
        $lessons = $data['lesson_id'];
        $tasks_assignment = $request->get('tasks_assignment', 0);
        $test_assignment = $request->get('test_assignment', 0);

        if (!$test_assignment && !$tasks_assignment)
        {
            return $this->redirectWith(true, null, 'must select tasks assignment or test assignment at least', 'error');
        }
        $students =  User::query()->with(['user_assignments'])
            ->when(count($students_array), function (Builder $query) use ($students_array){
                $query->whereIn('id', $students_array);
            })->filter($request)->get();

        foreach ($students as $student)
        {
           foreach ($lessons as $lesson)
           {
               if ($request->get('exclude_students', 1))
               {
                   $pre_assignment = $student->user_assignments->where('lesson_id', $lesson)->first();
                   if (!$pre_assignment)
                   {
                       $student->user_assignments()->create([
                           'lesson_id' => $lesson,
                           'deadline' => $request->get('deadline', null),
                           'tasks_assignment' => $tasks_assignment,
                           'test_assignment' => $test_assignment,
                       ]);
                   }
               }else{
                   $student->user_assignments()->create([
                       'lesson_id' => $lesson,
                       'deadline' => $request->get('deadline', null),
                       'tasks_assignment' => $tasks_assignment,
                       'test_assignment' => $test_assignment,
                   ]);
               }
           }

        }
        return redirect()->route('teacher.lesson_assignment.index')->with('message',t('Successfully Added'));

    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        UserAssignment::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Deleted Successfully'));
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentAssignmentExport($request))->download('Students assignments.xlsx');
    }

}
