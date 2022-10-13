<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\LessonRequest;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\TMatch;
use App\Models\TOption;
use App\Models\TQuestion;
use App\Models\TSortWord;
use App\Models\TTrueFalse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = Lesson::query()->with(['grade'])->search($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('content_btn', function ($row) {
                    return $row->content_btn;
                })
                ->addColumn('actions', function ($row) {
                    $edit_url = route('manager.lesson.edit', $row->id);
                    return view('manager.setting.btn_actions', compact('row', 'edit_url'));
                })->make();
        }
        $title = 'عرض الدروس';
        $grades = Grade::query()->get();
        return view('manager.lesson.index', compact('title', 'grades'));
    }

    public function create()
    {
        $title = "إضافة درس";
        $grades = Grade::query()->get();
        return view('manager.lesson.edit', compact('title', 'grades'));
    }

    public function store(LessonRequest $request)
    {
        if (in_array($request->get('lesson_type'), ['reading', 'listening']) && $request->get('grade_id') != 13) {
            $request->validate([
                'section_type' => 'required_if:lesson_type,reading,listening',
            ]);
        }
        $data = $request->validated();

        $data['active'] = $request->get('active', false);
        $lesson = Lesson::query()->create($data);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $lesson
                ->addMediaFromRequest('image')
                ->toMediaCollection('imageLessons');
        }

        return $this->redirectWith(false, 'manager.lesson.index', 'تم الإضافة بنجاح');
    }

    public function edit($id)
    {
        $title = 'تعديل درس';
        $lesson = Lesson::query()->with(['media'])->findOrFail($id);
        $grades = Grade::query()->get();
        return view('manager.lesson.edit', compact('lesson', 'grades', 'title'));
    }

    public function update(LessonRequest $request, $id)
    {
        if (in_array($request->get('lesson_type'), ['reading', 'listening']) && $request->get('grade_id') != 13) {
            $request->validate([
                'section_type' => 'required_if:lesson_type,reading,listening',
            ]);
        }
        $lesson = Lesson::query()->findOrFail($id);
        $data = $request->validated();
        $data['active'] = $request->get('active', false);
        $lesson->update($data);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $lesson
                ->addMediaFromRequest('image')
                ->toMediaCollection('imageLessons');
        }
        return $this->redirectWith(false, 'manager.lesson.index', 'تم التعديل بنجاح');

    }

    public function destroy($id)
    {
        $lesson = Lesson::query()->findOrFail($id);
        $lesson->delete();
        return $this->redirectWith(false, 'manager.lesson.index', 'تم الحذف بنجاح');
    }

    //Learn

    public function lessonLearn($id)
    {
        $lesson = Lesson::query()->with(['media'])->findOrFail($id);
        $title = 'التعلم والتدريب';
        return view('manager.lesson.learn', compact('lesson', 'title'));
    }

    public function updateLessonLearn(Request $request, $id)
    {
        $lesson = Lesson::query()->findOrFail($id);
        $lesson->update([
            'content' => $request->get('content', false),
        ]);
        if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            $lesson
                ->addMediaFromRequest('audio')
                ->toMediaCollection('audioLessons');
        }

        return redirect()->route('manager.lesson.learn', $lesson->id)->with('message', 'تم الإضافة بنجاح');
    }

    public function deleteLessonAudio($id)
    {
        $lesson = Lesson::query()->findOrFail($id);
        $lesson->clearMediaCollection('audioLessons');
        return redirect()->route('manager.lesson.learn', $lesson->id)->with('message', 'تم الحذف بنجاح');
    }

    public function uploadImageLesson(Request $request)
    {
        if ($request->hasFile('imageFile')) {
            $image = asset($this->uploadImage($request->file('imageFile'), 'lesson_images'));
            return response()->json(["link" => $image]);
        } else {
            return false;
        }
    }

    public function lessonReview($id, $step)
    {
        $lesson = Lesson::query()->findOrFail($id);
        $user = User::query()->first();
        if (!$user) {
            return redirect()->route('manager.home')->with('m-class', 'error')->with('message', 'لا يوجد أي حساب مستخدم للمتابعة');
        }
        Auth::guard('web')->loginUsingId($id);
        return redirect()->route('lesson', [$id, $step]);
    }


}
