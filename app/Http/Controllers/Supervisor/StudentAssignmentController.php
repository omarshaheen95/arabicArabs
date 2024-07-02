<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Supervisor;

use App\Exports\StudentAssignmentExport;
use App\Exports\StudentStoryAssignmentExport;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\StoryAssignment;
use App\Models\Teacher;
use App\Models\UserAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentAssignmentController extends Controller
{
    public function indexLessonAssignment(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserAssignment::query()->has('user')->with(['user.grade', 'user.teacher', 'lesson.grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {
                    $teacher = optional($row->user->teacher)->name ? optional($row->user->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                   $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Teacher') . ':</span>' . $teacher . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name .'<span class="fw-bold text-primary pe-1 ms-2">' . t('Section') . ':</span>' . $row->user->section . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('lesson', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Lesson') . ':</span>' . $row->lesson->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->lesson->grade->name . '</div>' .
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
        $teachers = Teacher::query()->filter($request)->get();
        $grades = Grade::query()->get();
        return view('supervisor.students_assignments.lesson_assignments', compact('title', 'teachers','grades'));
    }

    public function exportLessonAssignment(Request $request)
    {
        return (new StudentAssignmentExport($request))->download('Students assignments.xlsx');
    }

    public function indexStoryAssignment(Request $request)
    {
        if (request()->ajax()) {
            $rows = StoryAssignment::query()->has('user')->with(['user.grade', 'user.teacher', 'story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {

                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name .'<span class="fw-bold text-primary pe-1 ms-2">' . t('Section') . ':</span>' . $row->user->section . '</div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('story', function ($row) {
                    $teacher = optional($row->user->teacher)->name ? optional($row->user->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story') . ':</span>' . $row->story->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->story->grade_name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Teacher') . ':</span>' . $teacher . '</div>' .
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
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Deadline') . ':</span>' .$deadline.'</div>' .
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
        $teachers = Teacher::query()->filter($request)->get();
        $grades = Grade::query()->get();
        return view('supervisor.students_assignments.story_assignments', compact('title', 'teachers','grades'));
    }

    public function exportStoryAssignment(Request $request)
    {
        return (new StudentStoryAssignmentExport($request))->download('Students Stories assignments.xlsx');
    }

}
