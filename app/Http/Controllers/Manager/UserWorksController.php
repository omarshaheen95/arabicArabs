<?php

namespace App\Http\Controllers\Manager;

use App\Exports\StudentLessonExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLessonRequest;
use App\Models\Grade;
use App\Models\School;
use App\Models\UserLesson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserWorksController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show user works')->only('index');
        $this->middleware('permission:marking user works')->only(['show','update']);
        $this->middleware('permission:delete user works')->only(['destroy']);
        $this->middleware('permission:export user works')->only('export');

    }
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserLesson::query()
                ->with(['user.school', 'user.teacher','user.grade', 'lesson'])
                ->filter($request)
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('school', function ($row) {
                    $school = optional($row->user->school)->name;
                    $teacher = optional($row->user->teacher)->name ? optional($row->user->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('School') . ' </span> : ' . '<span class="ps-1"> ' . $school . '</span></div>' .
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
                        '<div class="d-flex"><span class="fw-bold ">' . $row->user->grade->name . '</span> : ' . '</div>' .
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
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Students works');
        $schools = School::query()->get();
        $grades = Grade::query()->get();
        return view('manager.students_works.index', compact('title','grades', 'schools'));
    }

    public function show($id)
    {

        $user_lesson = UserLesson::query()->findOrFail($id);
        $title = t('Show student work');
        $grades = Grade::query()->get();

        return view('manager.students_works.show', compact('title', 'grades','user_lesson'));
    }

    public function update(UserLessonRequest $request, $id)
    {
        $data = $request->validated();
        $user_lesson = UserLesson::query()->findOrFail($id);
        if ($request->hasFile('attach_writing_answer')) {
            $data['attach_writing_answer'] = $this->uploadFile($request->file('attach_writing_answer'), 'writing_attachments');
        } else {
            $data['attach_writing_answer'] = $user_lesson->getOriginal('attach_writing_answer');
        }

        if ($request->hasFile('reading_answer')) {
            $data['reading_answer'] = $this->uploadFile($request->file('reading_answer'), 'record_result');
        } else {
            $data['reading_answer'] = $user_lesson->getOriginal('reading_answer');
        }
        $user_lesson->update($data);
        if ($user_lesson->user->teacher_student) {
            updateTeacherStatistics($user_lesson->user->teacher_student->teacher_id);
        }


        return $this->redirectWith(false, 'manager.students_works.index', 'Successfully saved');
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        $users_lesson = UserLesson::query()->with(['user.teacher'])->whereIn('id', $request->get('row_id'))->get();
        foreach ($users_lesson as $user_lesson) {
            $user = $user_lesson->user;
            $user_lesson->delete();
            if ($user->teacher) {
                updateTeacherStatistics($user->teacher->id);
            }
        }

        return $this->sendResponse(null, t('Deleted Successfully'));
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        $school = School::query()->findOrFail($request->get('school_id'));
        $file_name = $school->name . " Students Lesson Works.xlsx";
        return (new StudentLessonExport($request))->download($file_name);
    }
}
