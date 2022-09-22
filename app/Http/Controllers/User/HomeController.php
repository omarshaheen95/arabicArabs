<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Story;
use App\Models\StoryAssignment;
use App\Models\StoryQuestion;
use App\Models\StoryUserRecord;
use App\Models\TQuestion;
use App\Models\UserAssignment;
use App\Models\UserRecord;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function home()
    {
        $title = "الصفحة الرئيسة";
        return view('user.home', compact('title'));
    }

    public function levels()
    {
//        Log::critical(date('Y-m-d'));
//        Log::critical(Carbon::createFromFormat('Y-m-d', '2021-07-01'));
        $title = 'المهارات والدروس';
        $grade = Grade::query()->where('id', Auth::user()->grade_id)->first();

        $alternate_grade = Grade::query()->where('id', Auth::user()->alternate_grade_id)->first();

        return view('user.levels', compact('title', 'grade', 'alternate_grade'));
    }

    public function lessons($id, $type)
    {
        $user = Auth::guard('web')->user();
        $grade = Grade::query()->findOrFail($id);
        if ($user->grade_id != $grade->id && $user->alternate_grade_id != $grade->id  && $user->id != 1) {
            return redirect()->route('home')->with('message', 'الدروس غير متاحة')->with('m-class', 'error');
        }
        $lessons = Lesson::query()->where('lesson_type', $type)->where('grade_id', $grade->id)->get();
        return view('user.lessons', compact('grade', 'lessons'));
    }

    public function lesson($id, $key)
    {
        $lesson = Lesson::query()->with(['grade'])->findOrFail($id);
        $user = Auth::guard('web')->user();
        if ($user->grade_id != $lesson->grade_id && $user->alternate_grade_id != $lesson->grade_id && $user->id != 1) {
            return redirect()->route('home')->with('message', 'الدرس غير متاح')->with('m-class', 'error');
        }
        switch ($key) {
            case 'learn':
                return view('user.lesson.learn', compact('lesson'));
            case 'training':
                $tf_questions = TQuestion::query()->where('type', 1)->where('lesson_id', $lesson->id)->get();
                $c_questions = TQuestion::query()->where('type', 2)->where('lesson_id', $lesson->id)->get();
                $m_questions = TQuestion::query()->where('type', 3)->where('lesson_id', $lesson->id)->get();
                $s_questions = TQuestion::query()->where('type', 4)->where('lesson_id', $lesson->id)->get();
                return view('user.lesson.training', compact('lesson', 'tf_questions', 'c_questions', 'm_questions', 's_questions'));
            case 'test':
                $questions = Question::query()->where('lesson_id', $id)->get();
                if ($lesson->lesson_type == 'writing')
                {
                    return view('user.lesson.writing_test', compact('questions', 'lesson'));

                }
                if ($lesson->lesson_type == 'speaking')
                {
                    return view('user.lesson.speaking_test', compact('questions', 'lesson'));

                }
                return view('user.lesson.test', compact('questions', 'lesson'));
            default:
                return redirect()->route('home');
        }
    }

    public function certificates()
    {
        $title = 'نتائج الاختبارات  - Tests results';
        $student_tests = UserTest::query()
            ->where('user_id', Auth::user()->id)
            ->where('approved', 1)
            ->latest()->paginate(10);

        return view('user.certificates', compact('student_tests', 'title'));
    }

    public function certificate($id)
    {
        $title = 'Student test result';
        $student = Auth::user();
        $student_test = UserTest::query()->where('user_id', $student->id)->find($id);
        if (!$student_test)
            return redirect()->route('home')->with('message', 'test not found')->with('m-class', 'error');
        return view('user.certificate', compact('student_test', 'title'));
    }

    public function certificateAnswers($id)
    {
        $title = 'إجابات اختبار الطالب';
        $student = Auth::user();
        $student_test = UserTest::query()->where('user_id', $student->id)->find($id);
        if (!$student_test)
            return redirect()->route('home')->with('message', 'test not found')->with('m-class', 'error');

        $questions = Question::query()->where('lesson_id', $student_test->lesson_id)->get();

        return view('user.certificate_result', compact('student_test', 'title', 'questions'));
    }

    public function assignments()
    {
        $title = 'واجبات الدروس المسندة';
        $student_assignments = UserAssignment::query()
            ->where('user_id', Auth::user()->id)
            ->latest()->paginate(10);

        return view('user.assignments', compact('student_assignments', 'title'));
    }

    public function storiesLevels()
    {
        $title = "مستويات القصص";
        $levels = [
            1, 2, 3, 4, 5, 6, 7, 8, 9
        ];

        return view('user.stories_levels', compact('title', 'levels', 'levels'));
    }

    public function stories($level)
    {
        $title = "قائمة القصص";
        $stories = Story::query()->where('grade', $level)->where('active', 1)->get();

        return view('user.stories_list', compact('title', 'stories', 'level'));
    }

    public function story($id, $key)
    {
        $story = Story::query()->findOrFail($id);
        $user = Auth::guard('web')->user();
        switch ($key) {
            case 'watch':
                return view('user.story.learn', compact('story'));
            case 'read':
                $user_story = StoryUserRecord::query()->where('user_id', $user->id)->where('story_id', $story->id)->first();
                $users_story = StoryUserRecord::query()
                    ->where('user_id','<>', $user->id)
                    ->where('story_id', $story->id)->latest()
                    ->where('status', 'corrected')
                    ->where('approved', 1)
                    ->limit(10)
                    ->get();
                return view('user.story.training', compact('story', 'user_story', 'users_story'));
            case 'test':
                $questions = StoryQuestion::query()->where('story_id', $id)->get();
                return view('user.story.test', compact('questions', 'story'));
            default:
                return redirect()->route('home');
        }
    }

    public function recordStory(Request $request, $id)
    {
        $story = Story::query()->findOrFail($id);
        $user = Auth::guard('web')->user();
        $user_record = StoryUserRecord::query()->where('user_id', $user->id)->where('story_id', $id)->first();
        if ($user_record) {
            if ($user_record->status == 'pending' || $user_record->status == 'returned') {
                if ($request->hasFile('record')) {
                    $new_name = uniqid() . '.' . 'wav';
                    $destination = public_path('uploads/record_result');
                    move_uploaded_file($_FILES['record']['tmp_name'], $destination . '/' . $new_name);
                    $record = 'uploads' . DIRECTORY_SEPARATOR . 'record_story' . DIRECTORY_SEPARATOR . $new_name;
                    $user_record->update([
                        'record' => $record,
                        'status' => 'pending',
                    ]);
                    return $this->sendResponse($record, 'Record Saved Successfully');
                }
            }else{
                return $this->sendResponse(null, 'Your record cannot accept new updates');
            }
        } else {
            if ($request->hasFile('record')) {
                $destination = public_path('uploads/record_result');
                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);

                $new_name = uniqid() . '.' . 'wav';

                move_uploaded_file($_FILES['record']['tmp_name'], $destination . '/' . $new_name);
                $record = 'uploads' . DIRECTORY_SEPARATOR . 'record_story' . DIRECTORY_SEPARATOR . $new_name;
                StoryUserRecord::query()->create([
                    'user_id' => $user->id,
                    'story_id' => $id,
                    'record' => $record,
                ]);
                return $this->sendResponse($record, 'تم تسجيل القصة بنجاح');
            }
        }

        return $this->sendResponse([], 'تم تسجيل القصة بنجاح');
    }

    public function storiesAssignments()
    {
        $title = 'تعيينات القصص المسندة';
        $student_assignments = StoryAssignment::query()
            ->where('user_id', Auth::user()->id)
            ->latest()->paginate(10);

        return view('user.story_assignments', compact('student_assignments', 'title'));
    }

}
