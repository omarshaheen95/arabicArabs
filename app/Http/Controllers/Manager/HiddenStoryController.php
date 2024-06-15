<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Controllers\Manager;

use App\Exports\HiddenStoryExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\HiddenStoryRequest;
use App\Models\HiddenStory;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HiddenStoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show hidden stories')->only('index');
        $this->middleware('permission:add hidden stories')->only(['create','store']);
        $this->middleware('permission:delete hidden stories')->only('destroy');
        $this->middleware('permission:export hidden stories')->only('export');

    }
    public function index(Request $request)
    {
        if (request()->ajax()) {

            $rows = HiddenStory::query()->with(['story', 'school'])->filter($request)
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('grade', function ($row){
                    return $row->story->grade_name;
                })
                ->addColumn('story', function ($row){
                    return $row->story->name;
                })
                ->addColumn('school', function ($row){
                    return $row->school->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Show Stories');
        $grades = storyGradesSys();
        $schools = School::query()->get();
        return view('manager.hidden_story.index', compact('title', 'grades', 'schools'));
    }

    public function create()
    {
        $title = t('Add Lesson');
        $grades = storyGradesSys();
        $schools = School::query()->get();
        return view('manager.hidden_story.create', compact('title', 'grades', 'schools'));
    }

    public function store(HiddenStoryRequest $request)
    {
        $data = $request->validated();
        $data['story_id'] = array_unique($data['story_id']);
        foreach ($data['story_id'] as $story){
            HiddenStory::query()->firstOrCreate([
                'school_id' => $data['school_id'],
                'story_id' => $story,
            ],[
                'school_id' => $data['school_id'],
                'story_id' => $story,
            ]);
        }
        return redirect()->route('manager.hidden_story.index')->with('success', t('Added Successfully'));
    }

    public function show(HiddenStory $hiddenStory)
    {
    }

    public function edit(HiddenStory $hiddenStory)
    {
    }

    public function update(HiddenStoryRequest $request, HiddenStory $hiddenStory)
    {
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        HiddenStory::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Deleted Successfully'));
    }

    public function export(Request $request)
    {
        return (new HiddenStoryExport($request))->download('Hidden stories.xlsx');
    }
}
