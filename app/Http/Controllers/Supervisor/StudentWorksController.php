<?php

namespace App\Http\Controllers\Supervisor;

use App\Exports\StudentLessonExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLessonRequest;
use App\Models\Grade;
use App\Models\Level;
use App\Models\School;
use App\Models\Teacher;
use App\Models\UserLesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentWorksController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserLesson::query()
                ->with(['user.grade', 'user.teacher', 'lesson'])
                ->filter($request)
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('school', function ($row) {
                    $teacher = optional($row->user->teacher)->name ? optional($row->user->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Teacher') . ' </span> : ' . '<span class="ps-1"> ' . $teacher . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('student', function ($row) {
                    $section = !is_null($row->user->section) ? $row->user->section : '<span class="text-danger">-</span>';
                    $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . $row->user->grade->name . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . t('Section') . '</span> : ' . $section . '</div></div>';
                    return $student;
                })
                ->addColumn('lesson', function ($row){
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Lesson') . ' </span> : ' . '<span class="ps-1"> ' . $row->lesson->name . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Status') . ' </span> : ' . '<span class="ps-1"> ' . $row->status_name_class . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Submitted At') . ' </span> : ' . '<span class="ps-1"> ' . $row->created_at->format('Y-m-d H:i') . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->make();
        }
        $title = t('Students works');
        $teachers = Teacher::query()->filter($request)->get();
        $grades = Grade::query()->get();
        return view('supervisor.students_works.index', compact('title', 'teachers','grades'));
    }

    public function studentLessonExport(Request $request)
    {
        $school = Auth::guard('supervisor')->user()->school;
        $file_name = $school->name . " Students Lesson Works.xlsx";
        return (new StudentLessonExport($request))->download($file_name);
    }
}
