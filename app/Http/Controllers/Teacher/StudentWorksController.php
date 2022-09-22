<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLessonRequest;
use App\Models\Level;
use App\Models\School;
use App\Models\UserAssignment;
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
        $teacher = Auth::guard('teacher')->user();
        if (request()->ajax())
        {
            $name = $request->get('name', false);
            $grade = $request->get('grade', false);
            $lesson = $request->get('lesson', false);
            $level = $request->get('level', false);
            $rows = UserLesson::query()
                ->whereHas('user', function (Builder $query) use ($teacher, $name, $grade){
                    $query->when($name, function (Builder $query) use ($name){
                        $query->where('name', 'like', '%'.$name.'%');
                    })->when($grade, function (Builder $query) use ($grade){
                        $query->where('grade', $grade);
                    })->where('school_id', $teacher->school_id)
                        ->whereHas('teacherUser', function (Builder $query) use($teacher){
                            $query->where('teacher_id', $teacher->id);
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
                    return $row->teacher_action_buttons;
                })
                ->make();
        }
        $title = t('Students works');

        return view('teacher.students_works.index', compact('title'));
    }

    public function show($id)
    {
        $teacher = Auth::guard('teacher')->user();
        $user_lesson = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($teacher){
                $query->where('school_id', $teacher->school_id)
                    ->whereHas('teacherUser', function (Builder $query) use($teacher){
                        $query->where('teacher_id', $teacher->id);
                    });
            })->findOrFail($id);

        $title = t('Show student work');
        return view('teacher.students_works.show', compact('title', 'user_lesson'));
    }

    public function update(UserLessonRequest $request, $id)
    {
        $data = $request->validated();
        $teacher = Auth::guard('teacher')->user();
        $user_lesson = UserLesson::query()
            ->whereHas('user', function (Builder $query) use ($teacher){
                $query->where('school_id', $teacher->school_id)
                    ->whereHas('teacherUser', function (Builder $query) use($teacher){
                        $query->where('teacher_id', $teacher->id);
                    });
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

        if(isset($_FILES['record1']) && $_FILES['record1']['type'] != 'text/plain' && $_FILES['record1']['error'] <= 0){
            $new_name = uniqid().'.'.'wav';
            $destination = public_path('uploads/record_result');
            move_uploaded_file($_FILES['record1']['tmp_name'], $destination .'/'. $new_name);
            $data['teacher_audio_message'] = 'uploads'.DIRECTORY_SEPARATOR.'record_result'.DIRECTORY_SEPARATOR.$new_name;
        }

        $user_lesson->update($data);

        if ($user_lesson->user->teacherUser)
        {
            updateTeacherStatistics($user_lesson->user->teacherUser->teacher_id);
        }

        if($user_lesson->status == 'returned')
        {
            UserAssignment::query()->updateOrCreate([
                'user_id' => $user_lesson->user_id,
                'lesson_id' => $user_lesson->lesson_id,
            ],[
                'user_id' => $user_lesson->user_id,
                'lesson_id' => $user_lesson->lesson_id,
                'done_tasks_assignment' => 0,
                'tasks_assignment' => 1,
                'completed' => 0,
            ]);
            $userAssignments = UserAssignment::query()
                ->where('user_id', $user_lesson->user_id)
                ->where('completed', 0)
                ->count();
            send_push_to_pusher('user_'. $user_lesson->user_id, 'user-notification', ['title' => 'New Assigment - واجب جديد', 'body' => 'New Assigment - واجب جديد', 'unread_notifications' => $userAssignments]);
        }

        return $this->redirectWith(false, 'teacher.students_works.index', 'Successfully saved');
    }
}
