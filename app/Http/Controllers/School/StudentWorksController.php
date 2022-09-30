<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLessonRequest;
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
        $school = Auth::guard('school')->user()->id;
        if (request()->ajax())
        {
            $name = $request->get('name', false);
            $grade = $request->get('grade', false);
            $lesson = $request->get('lesson_id', false);
            $level = $request->get('level_id', false);
            $teacher = $request->get('teacher_id', false);

            $rows = UserLesson::query()
                ->whereHas('user', function (Builder $query) use ($teacher, $name, $grade, $school){
                    $query->when($name, function (Builder $query) use ($name){
                        $query->where('name', 'like', '%'.$name.'%');
                    })->when($grade, function (Builder $query) use ($grade){
                        $query->where('grade', $grade);
                    })->when($teacher, function (Builder $query) use ($teacher){
                        $query->whereHas('teacher_student', function (Builder $query) use($teacher){
                            $query->where('teacher_id', $teacher);
                        });
                    })->when($school, function (Builder $query) use ($school){
                        $query->where('school_id', $school);
                    });
                })->when($lesson, function (Builder $query) use ($lesson){
                    $query->where('lesson_id', $lesson);
                })->when($level, function (Builder $query) use ($level){
                    $query->whereHas('lesson', function (Builder $query) use($level){
                        $query->where('level_id', $level->id);
                    });
                })
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('school', function ($row) {
                    return $row->user->school->name;
                })
                ->addColumn('teacher', function ($row) {
                    return optional(optional($row->user->teacher_student)->teacher)->name;
                })
                ->addColumn('user', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('lesson', function ($row) {
                    return $row->lesson->name;
                })
                ->addColumn('status', function ($row) {
                    return $row->status_name;
                })
                ->addColumn('grade', function ($row) {
                    return $row->user->grade;
                })
                ->addColumn('actions', function ($row) {
                    return $row->school_action_buttons;
                })
                ->make();
        }
        $title = t('Students works');
        $teachers = Teacher::query()->where('school_id', $school)->get();
        $levels = Level::query()->get();
        return view('school.students_works.index', compact('title', 'levels', 'teachers'));
    }

    public function show($id)
    {
        $user_lesson = UserLesson::query()->whereHas('user', function (Builder $query){
            $query->where('school_id', Auth::guard('school')->user()->id);
        })->findOrFail($id);
        $title = t('Show student work');
        return view('school.students_works.show', compact('title', 'user_lesson'));
    }

    public function update(UserLessonRequest $request, $id)
    {
        $data = $request->validated();
        $user_lesson = UserLesson::query()->whereHas('user', function (Builder $query){
            $query->where('school_id', Auth::guard('school')->user()->id);
        })->findOrFail($id);
        if ($request->hasFile('attach_writing_answer'))
        {
            $data['attach_writing_answer'] = $this->uploadImage($request->file('attach_writing_answer'), 'writing_attachments');
        }else{
            $data['attach_writing_answer'] = $user_lesson->getOriginal('attach_writing_answer');
        }

        if ($request->hasFile('reading_answer'))
        {
            $data['reading_answer'] = $this->uploadImage($request->file('reading_answer'), 'record_result');
        }else{
            $data['reading_answer'] = $user_lesson->getOriginal('reading_answer');
        }
        $user_lesson->update($data);
        if ($user_lesson->user->teacher_student)
        {
            updateTeacherStatistics($user_lesson->user->teacher_student->teacher_id);
        }

        return $this->redirectWith(false, 'school.students_works.index', 'Successfully saved');
    }

    public function destroy($id)
    {
        $user_lesson = UserLesson::query()->findOrFail($id);
        $user = $user_lesson->user;
        $user_lesson->delete();
        if ($user->teacher_student)
        {
            updateTeacherStatistics($user->teacher_student->teacher_id);
        }
        return redirect()->route('school.students_works.index')->with('message', t('Successfully Deleted'));
    }
}
