<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\HiddenLesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {

            $name = $request->get('name', false);
            $grade = $request->get('grade', false);
            $level = $request->get('level', false);
            $rows = HiddenLesson::query()->with(['lesson', 'lesson.level'])
                ->when($name, function (Builder $query) use ($name) {
                    $query->whereHas('lesson', function (Builder $query) use ($name){
                        $query->whereTranslationLike('name', '%' . $name . '%');
                    });
                })
                ->when($grade, function (Builder $query) use ($grade) {
                    $query->whereHas('lesson', function (Builder $query) use ($grade){
//                        $query->whereHas('level', function (Builder $query) use ($grade) {
                        $query->where('grade_id', $grade);
//                    });
                    });
                })
                ->when($level, function (Builder $query) use ($level) {
                   $query->whereHas('lesson', function (Builder $query) use ($level){
                        $query->where('level_id', $level);
                   });
                })
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('lesson', function ($row) {
                    return $row->lesson->name;
                })
                ->addColumn('level', function ($row) {
                    return $row->lesson->level->name;
                })
                ->addColumn('grade', function ($row) {
                    return 'Grade ' . $row->lesson->level->grade;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Control Lessons');
        return view('school.lesson.index', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade' => 'required',
            'level_id' => 'required',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        HiddenLesson::query()->create([
            'school_id' =>  Auth::guard('school')->user()->id,
            'lesson_id' => $request->get('lesson_id'),
        ]);

        return redirect()->route('school.lesson.index')->with('message', t('Successfully Added'));
    }

    public function destroy($id)
    {
        $lesson = HiddenLesson::query()->where('school_id', Auth::guard('school')->user()->id)->findOrFail($id);
        $lesson->delete();
        return redirect()->route('school.lesson.index')->with('message', t('Successfully Deleted'));
    }
}
