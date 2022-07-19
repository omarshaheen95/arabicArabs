<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\TQuestion;
use App\Models\UserAssignment;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function home()
    {
        $title = "الصفحة الرئيسية";
        $grade = Grade::query()->where('id', Auth::user()->grade_id)->first();

        $alternate_grade = Grade::query()->where('id', Auth::user()->alternate_grade_id)->first();

        return view('user.home', compact('title', 'grade', 'alternate_grade'));
    }

    public function lessons($id, $type)
    {
        $user = Auth::guard('web')->user();
        $grade = Grade::query()->findOrFail($id);
        if ($user->grade_id != $grade->id && $user->alternate_grade_id != $grade->id) {
            return redirect()->route('home')->with('message', 'الدروس غير متاحة')->with('m-class', 'error');
        }
        $lessons = Lesson::query()->where('lesson_type', $type)->where('grade_id', $grade->id)->get();
        return view('user.lessons', compact('grade', 'lessons'));
    }

    public function lesson($id, $key)
    {
        $lesson = Lesson::query()->with(['grade'])->findOrFail($id);
        $user = Auth::guard('web')->user();
        if ($user->grade_id != $lesson->grade_id && $user->alternate_grade_id != $lesson->grade_id) {
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
        $title = 'Student test answers';
        $student = Auth::user();
        $student_test = UserTest::query()->where('user_id', $student->id)->find($id);
        if (!$student_test)
            return redirect()->route('home')->with('message', 'test not found')->with('m-class', 'error');

        $questions = Question::query()->where('lesson_id', $student_test->lesson_id)->get();

        return view('user.certificate_result', compact('student_test', 'title', 'questions'));
    }

    public function assignments()
    {
        $title = 'الواجبات المسندة  - Assigned Homeworks';
        $student_assignments = UserAssignment::query()
            ->where('user_id', Auth::user()->id)
            ->latest()->paginate(10);

        return view('user.assignments', compact('student_assignments', 'title'));
    }

}
