<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Manager;

use App\Exports\HiddenLessonExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\HiddenLessonRequest;
use App\Models\Grade;
use App\Models\HiddenLesson;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HiddenLessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show hidden lessons')->only('index');
        $this->middleware('permission:add hidden lessons')->only(['create','store']);
        $this->middleware('permission:delete hidden lessons')->only('destroy');
        $this->middleware('permission:export hidden lessons')->only('export');

    }
    public function index(Request $request)
    {
        if (request()->ajax()) {

            $rows = HiddenLesson::query()->with(['lesson.grade', 'school'])->filter($request)
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('grade', function ($row){
                    return $row->lesson->grade->_name;
                })
                ->addColumn('lesson', function ($row){
                    return $row->lesson->name;
                })
                ->addColumn('school', function ($row){
                    return $row->school->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Lessons');
        $grades =Grade::query()->get();
        $schools = School::query()->get();
        return view('manager.hidden_lesson.index', compact('title', 'grades', 'schools'));
    }

    public function create()
    {
        $title = t('Add Lesson');
        $grades =Grade::query()->get();
        $schools = School::query()->get();
        return view('manager.hidden_lesson.create', compact('title', 'grades', 'schools'));
    }

    public function store(HiddenLessonRequest $request)
    {
        $data = $request->validated();
        $data['lesson_id'] = array_unique($data['lesson_id']);
        foreach ($data['lesson_id'] as $lesson_id){
            HiddenLesson::query()->firstOrCreate([
                'school_id' => $data['school_id'],
                'lesson_id' => $lesson_id,
            ],[
                'school_id' => $data['school_id'],
                'lesson_id' => $lesson_id,
            ]);
        }
        return redirect()->route('manager.hidden_lesson.index')->with('success', t('Added Successfully'));
    }

    public function show(HiddenLesson $hiddenLesson)
    {
    }

    public function edit(HiddenLesson $hiddenLesson)
    {
    }

    public function update(HiddenLessonRequest $request, HiddenLesson $hiddenLesson)
    {
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        HiddenLesson::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Deleted Successfully'));
    }

    public function export(Request $request)
    {
        return (new HiddenLessonExport($request))->download('Hidden Lessons.xlsx');
    }
}
