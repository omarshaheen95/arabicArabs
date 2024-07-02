<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Teacher;

use App\Exports\StudentStoryAssignmentExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoryAssignmentRequest;
use App\Models\Grade;
use App\Models\School;
use App\Models\User;
use App\Models\StoryAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StoryAssignmentController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = StoryAssignment::query()->has('user')->with(['story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {
                     $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name .'<span class="fw-bold text-primary pe-1 ms-2">' . t('Section') . ':</span>' . $row->user->section . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('story', function ($row) {
                    $teacher = optional($row->user->teacher)->name ? optional($row->user->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story') . ':</span>' . $row->story->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->story->grade_name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $test_status = $row->done_test_assignment ? '<span class="badge badge-primary">' . t('Completed') . '</span>' : '<span class="badge badge-danger">' . t('Uncompleted') . '</span>';
                    $status = $row->completed ? '<span class="badge badge-primary">' . t('Completed') . '</span>' : '<span class="badge badge-danger">' . t('Uncompleted') . '</span>';
                    $html = '<div class="d-flex flex-column">' .
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
        $title = t('Show Stories Assignments');
        $grades = Grade::query()->get();
        return view('teacher.stories_assignments.index', compact('title','grades'));
    }

    public function create()
    {
        $title = t('Add New Assignment');
        $grades = Grade::query()->get();
        return view('teacher.stories_assignments.create', compact('title','grades'));
    }

    public function store(StoryAssignmentRequest $request)
    {
        $data = $request->validated();
        $students_array = $data['students'];
        $stories = $data['story_id'];

        $students =  User::query()->with(['user_story_assignments'])
            ->when(count($students_array), function (Builder $query) use ($students_array){
                $query->whereIn('id', $students_array);
            })->filter($request)->get();

        foreach ($students as $student)
        {
            foreach ($stories as $story)
            {
                if ($request->get('exclude_students', 1))
                {
                    $pre_assignment = $student->user_story_assignments->where('story_id', $story)->first();
                    if (!$pre_assignment)
                    {
                        $student->user_story_assignments()->create([
                            'story_id' => $story,
                            'deadline' => $request->get('deadline', null),
                            'test_assignment' => 1,
                        ]);
                    }
                }else{
                    $student->user_story_assignments()->create([
                        'story_id' => $story,
                        'deadline' => $request->get('deadline', null),
                        'test_assignment' => 1,
                    ]);
                }
            }

        }
        return redirect()->route('teacher.story_assignment.index')->with('message',t('Successfully Added'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        StoryAssignment::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Deleted Successfully'));
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentStoryAssignmentExport($request))->download('Students Stories assignments.xlsx');
    }

}
