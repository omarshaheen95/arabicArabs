<?php

namespace App\Http\Controllers\Supervisor;

use App\Exports\StudentStoryRecordExport;
use App\Exports\StudentStoryTestExport;
use App\Exports\StudentTestExport;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\StoryUserRecord;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentTestController extends Controller
{
    public function lessonsIndex(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserTest::with(['user', 'lesson.grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row) {
                    $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Section') . ':</span>' . $row->user->section . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-success pe-1">' . t('Submitted At') . ':</span>' . $row->created_at->format('Y-m-d H:i') . '</div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('lesson', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->lesson->grade->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Lesson') . ':</span>' . $row->lesson->name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('result', function ($row) {
                    $status = $row->status == 'Pass' ? '<span class="badge badge-primary">' . $row->status . '</span>' : '<span class="badge badge-danger">' . $row->status . '</span>';
                    $html = '<div class="d-flex flex-column justify-content-center">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $row->total_per . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $status . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    //add created_at to the action buttons
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show students lessons tests');
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query) {
            $query->where('supervisor_id', Auth::guard('supervisor')->id());
        })->get();
        $grades = Grade::query()->get();
        return view('supervisor.students_tests.lessons', compact('title', 'teachers', 'grades'));
    }

    public function lessonsCertificate(Request $request, $id)
    {
        $title = 'Student test result';
        $student_test = UserTest::query()->with(['lesson.grade'])->find($id);
        if ($student_test->status != 'Pass')
            return redirect()->route('manager.home')->with('message', 'test dose not has certificates')->with('m-class', 'error');

        return view('user.new_certificate', compact('student_test', 'title'));
    }

    public function lessonsExportStudentsTestsExcel(Request $request)
    {
        return (new StudentTestExport($request))->download('Students tests.xlsx');
    }

    public function storiesIndex(Request $request)
    {
        if (request()->ajax()) {
            $rows = StudentStoryTest::with(['user.grade', 'story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row) {
                    $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Section') . ':</span>' . $row->user->section . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-success pe-1">' . t('Submitted At') . ':</span>' . $row->created_at->format('Y-m-d H:i') . '</div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('story', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Level') . ':</span>' . $row->story->grade_name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story') . ':</span>' . $row->story->name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('result', function ($row) {
                    $status = $row->status == 'Pass' ? '<span class="badge badge-primary">' . $row->status . '</span>' : '<span class="badge badge-danger">' . $row->status . '</span>';
                    $html = '<div class="d-flex flex-column justify-content-center">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $row->total_per . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $status . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show students stories tests');
        $teachers = Teacher::query()->whereHas('supervisor_teachers', function (Builder $query) {
            $query->where('supervisor_id', Auth::guard('supervisor')->id());
        })->get();
        $grades = Grade::query()->get();

        return view('supervisor.students_tests.stories', compact('title', 'teachers', 'grades'));
    }


    public function storiesCertificate(Request $request, $id)
    {
        $title = 'Student test result';
        $student_test = StudentStoryTest::query()->with(['story'])->find($id);
        if ($student_test->status != 'Pass')
            return redirect()->route('manager.home')->with('message', 'test dose not has certificates')->with('m-class', 'error');

        return view('user.story.new_certificate', compact('student_test', 'title'));
    }

    public function exportStoriesTestsExcel(Request $request)
    {
        return (new StudentStoryTestExport($request))->download('Students tests.xlsx');
    }

    public function storiesRecordsIndex(Request $request)
    {
        if (request()->ajax()) {
            $rows = StoryUserRecord::with(['user.grade', 'story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row) {
                    $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('School') . ':</span>' . $row->user->school->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Section') . ':</span>' . $row->user->section . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('story', function ($row) {
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $row->story->grade_name . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story') . ':</span>' . $row->story->name . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $html = '<div class="d-flex flex-column justify-content-center">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $row->status_name_class . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $row->created_at->format('Y-m-d H:i') . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->make();
        }
        $title = t('Show students stories records');
        $teachers = Teacher::query()->filter($request)->get();
        $grades = Grade::query()->get();
        return view('supervisor.students_assignments.students_records', compact('title', 'teachers', 'grades'));
    }

    public function exportStoriesRecordsExcel(Request $request)
    {
        return (new StudentStoryRecordExport($request))->download('Students stories records.xlsx');
    }
}
