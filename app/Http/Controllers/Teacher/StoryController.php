<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\StoryUserRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StoryController extends Controller
{
    public function studentsRecords(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        if (request()->ajax())
        {
            $name = $request->get('name', false);
            $grade = $request->get('grade', false);
            $story = $request->get('story', false);
            $level = $request->get('level', false);
            $rows = StoryUserRecord::query()->with(['user', 'story'])
                ->whereHas('user', function (Builder $query) use ($teacher, $name, $grade){
                    $query->when($name, function (Builder $query) use ($name){
                        $query->where('name', 'like', '%'.$name.'%');
                    })->when($grade, function (Builder $query) use ($grade){
                        $query->where('grade_id', $grade);
                    })->where('school_id', $teacher->school_id)
                        ->whereHas('teacherUser', function (Builder $query) use($teacher){
                            $query->where('teacher_id', $teacher->id);
                        });
                })
                ->when($story, function (Builder $query) use ($story){
                    $query->where('story_id', $story);
                })
                ->when($level, function (Builder $query) use ($level){
                    $query->whereHas('story', function (Builder $query) use($level){
                        $query->where('grade', $level);
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
                ->addColumn('story', function ($row) {
                    return $row->story->name;
                })
                ->addColumn('status', function ($row) {
                    return $row->status_name;
                })
                ->addColumn('grade', function ($row) {
                    return $row->user->grade_id;
                })
                ->addColumn('level', function ($row) {
                    return $row->story->grade;
                })
                ->addColumn('actions', function ($row) {
                    return $row->teacher_action_buttons;
                })
                ->make();
        }
        $title = "تسجيلات القصص للطلاب";
        $grades = Grade::query()->get();
        return view('teacher.student_story.index', compact('title', 'grades'));
    }
    public function showStudentsRecords($id)
    {
        $title = "عرض تسجيل طالب";
        $teacher = Auth::guard('teacher')->user();
        $user_record = StoryUserRecord::query()
            ->whereHas('user', function (Builder $query) use ($teacher){
                $query->where('school_id', $teacher->school_id)
                    ->whereHas('teacherUser', function (Builder $query) use($teacher){
                        $query->where('teacher_id', $teacher->id);
                    });
            })->findOrFail($id);

        return view('teacher.student_story.show', compact('user_record', 'title'));
    }

    public function updateStudentsRecords(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required',
            'mark' => 'required',
        ]);
        $teacher = Auth::guard('teacher')->user();
        $user_record = StoryUserRecord::query()
            ->whereHas('user', function (Builder $query) use ($teacher){
                $query->where('school_id', $teacher->school_id)
                    ->whereHas('teacherUser', function (Builder $query) use($teacher){
                        $query->where('teacher_id', $teacher->id);
                    });
            })->findOrFail($id);
        $data['approved'] = $request->get('approved', 0);
        $user_record->update($data);
        return $this->redirectWith(false, 'teacher.students_record.index', 'Successfully Corrected');
    }

    public function deleteStudentsRecords($id)
    {
        $teacher = Auth::guard('teacher')->user();
        $user_record = StoryUserRecord::query()
            ->whereHas('user', function (Builder $query) use ($teacher){
                $query->where('school_id', $teacher->school_id)
                    ->whereHas('teacherUser', function (Builder $query) use($teacher){
                        $query->where('teacher_id', $teacher->id);
                    });
            })->findOrFail($id);
        $user_record->delete();
        return $this->redirectWith(true, null, 'Successfully Deleted');
    }
}
