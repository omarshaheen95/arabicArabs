<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\HiddenLesson;
use App\Models\HiddenStory;
use App\Models\Lesson;
use App\Models\Story;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class HiddenController extends Controller
{
    public function indexLessons(Request $request)
    {
        if (request()->ajax()) {

            $rows = HiddenLesson::with(['lesson','grade'])->filter($request)
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('lesson', function ($row) {
                    return $row->name;
                })
                ->addColumn('grade', function ($row) {
                    return $row->grade->grade_name;
                })
                ->addColumn('status', function ($row) {
                    return isset($row->hiddenLessons[0]) ?
                        '<span class="badge badge-danger">' . t('Hidden') . '</span>' : '<span class="badge badge-primary">' . t('Active') . '</span>';
                })
                ->make();
        }
        $title = t('Control Lessons');
        $grades = Grade::query()->get();
        return view('school.hidden_control.lessons', compact('title','grades'));
    }

    public function hideLessons(Request $request)
    {
        $request->validate(['lessons_ids' => 'required|array', 'lessons_ids.*' => 'exists:lessons,id']);
        $ids = HiddenLesson::query()->filter($request)->get()->pluck('lesson_id');

        foreach ($request->get('lessons_ids') as $lesson) {
            if (!in_array($lesson, $ids->toArray())) {
                HiddenLesson::query()->create([
                    'school_id' => Auth::guard('school')->user()->id,
                    'lesson_id' => $lesson,
                ]);
            }
        }

        return $this->sendResponse(null, t('Lessons Hidden Successfully'));
    }

    public function showLessons(Request $request)
    {
        $request->validate(['lessons_ids' => 'required|array', 'lessons_ids.*' => 'exists:lessons,id']);

        HiddenLesson::query()
            ->where('school_id', Auth::guard('school')->user()->id)
            ->whereIn('lesson_id', $request->get('lessons_ids'))->delete();
        return $this->sendResponse(null, t('Lessons Unhidden Successfully'));
    }

    public function indexStories(Request $request)
    {
        if (request()->ajax()) {

            $rows = HiddenStory::query()->with(['story'])->filter($request)
                ->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('story', function ($row) {
                    return $row->name;
                })
                ->addColumn('level', function ($row) {
                    return $row->grade_name;
                })
                ->addColumn('status', function ($row) {
                    return isset($row->hidden_stories[0]) ?
                        '<span class="badge badge-danger">' . t('Hidden') . '</span>' : '<span class="badge badge-primary">' . t('Active') . '</span>';
                })
                ->make();
        }
        $title = t('Control Stories');
        return view('school.hidden_control.stories', compact('title'));
    }

    public function hideStories(Request $request)
    {
        $request->validate(['stories_ids' => 'required|array', 'stories_ids.*' => 'exists:stories,id']);
        $ids = HiddenStory::query()->filter($request)->get()->pluck('lesson_id');

        foreach ($request->get('stories_ids') as $story) {
            if (!in_array($story, $ids->toArray())) {
                HiddenStory::query()->create([
                    'school_id' => Auth::guard('school')->user()->id,
                    'story_id' => $story,
                ]);
            }
        }

        return $this->sendResponse(null, t('Lessons Hidden Successfully'));
    }

    public function showStories(Request $request)
    {
        $request->validate(['stories_ids' => 'required|array', 'stories_ids.*' => 'exists:stories,id']);

        HiddenStory::query()
            ->where('school_id', Auth::guard('school')->user()->id)
            ->whereIn('story_id', $request->get('stories_ids'))->delete();
        return $this->sendResponse(null, t('Lessons Unhidden Successfully'));
    }
}
