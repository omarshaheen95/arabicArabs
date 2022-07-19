<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Match;
use App\Models\MatchResult;
use App\Models\Option;
use App\Models\OptionResult;
use App\Models\QMatch;
use App\Models\Question;
use App\Models\SortResult;
use App\Models\SortWord;
use App\Models\StudentTest;
use App\Models\TrueFalse;
use App\Models\TrueFalseResult;
use App\Models\UserAssignment;
use App\Models\UserTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    public function lessonTest(Request $request, $id)
    {

        $student = Auth::user();

//        if ($student->id == 3 || $student->id == 1)
//        {
//            return redirect()->route('home')->with('message', w('The test was submitted successfully'))->with('m-class', 'success');
//        }

//        $student_term = UserTest::query()->where('user_id', $student->id)->where('lesson_id', $id)->first();
//        if ($student_term)
//        {
//            return redirect()->route('home')->with('message', w('You have obtained a test certificate for this lesson'))->with('m-class', 'success');
//        }


        $questions = Question::query()->where('lesson_id', $id)->get();

        $test = UserTest::query()->create([
            'user_id' => $student->id,
            'lesson_id' => $id,
            'corrected' => 1,
            'total' => 0,
        ]);

//        if ($student_term)
//        {
//            foreach ($questions as $question) {
//                $student_tf_result = TrueFalseResult::query()->where('question_id', $question->id)->where('user_id', $student->id)->delete();
//                $student_result = OptionResult::query()->where('question_id', $question->id)->where('user_id', $student->id)->delete();
//                $match_results = MatchResult::query()->where('user_id', $student->id)->where('question_id', $question->id)->delete();
//                $student_sort_words = SortResult::query()->where('question_id', $question->id)->where('user_id', $student->id)->delete();
//            }
//            $student_term->delete();
//        }
        foreach ($request->get('tf', []) as $key => $result)
        {
            TrueFalseResult::create([
                'user_test_id' => $test->id,
                'question_id' => $key,
                'result' => $result,
                'student_test_id' => $test->id
            ]);
        }
        foreach ($request->get('option', []) as $key => $option)
        {
            OptionResult::create([
                'user_test_id' => $test->id,
                'question_id' => $key,
                'option_id' => $option,
                'student_test_id' => $test->id
            ]);
        }
        foreach ($request->get('re', []) as $question => $options)
        {
            $matches = QMatch::query()->where('question_id', $question)->get()->pluck('id')->all();
            foreach ($options as $key => $value)
            {
                if (!is_null($value))
                {
                    MatchResult::create([
                        'user_test_id' => $test->id,
                        'question_id' => $question,
                        'match_id' => $matches[$value - 1],
                        'result_id' => $key,
                        'student_test_id' => $test->id
                    ]);
                }
            }
        }
        foreach ($request->get('sort', []) as $question => $words)
        {
            foreach ($words as $key => $value)
            {
                if (!is_null($value))
                {
                    SortResult::create([
                        'user_test_id' => $test->id,
                        'question_id' => $question,
                        'sort_word_id' => $key,
                        'student_test_id' => $test->id,
                    ]);
                }
            }
        }



        $total = 0;
        $tf_total = 0;
        $o_total = 0;
        $m_total = 0;
        $s_total = 0;

        foreach ($questions as $question)
        {
            if ($question->type == 1)
            {
                $student_result = TrueFalseResult::query()->where('question_id', $question->id)->where('user_test_id', $student->id)
                    ->first();
                $main_result = TrueFalse::query()->where('question_id', $question->id)->first();
                if(isset($student_result) && isset($main_result) && optional($student_result)->result == optional($main_result)->result){
                    $total += $question->mark;
                    $tf_total += $question->mark;
                }
            }

            if ($question->type == 2)
            {
                $student_result = OptionResult::query()->where('question_id', $question->id)->where('user_test_id', $test->id)
                    ->first();
                if($student_result)
                {
                    $main_result = Option::query()->find($student_result->option_id);
                }

                if(isset($student_result) && isset($main_result) && optional($main_result)->result == 1){
                    $total += $question->mark;
                    $o_total += $question->mark;
                }

            }

            $match_mark = 0;
            if ($question->type == 3)
            {
                $match_results = MatchResult::query()->where('user_test_id', $test->id)->where('question_id', $question->id)
                    ->get();
                foreach ($match_results as $match_result)
                {
                    $match_mark += $match_result->match_id == $match_result->result_id ? 2:0;
                }
                $total += $match_mark;
                $m_total += $match_mark;
            }

            if ($question->type == 4)
            {
                $sort_words = SortWord::query()->where('question_id', $question->id)->get()->pluck('id')->all();
                $student_sort_words = SortResult::query()->where('question_id', $question->id)->where('user_test_id', $test->id)
                   ->get();
                if (count($student_sort_words))
                {
                    $student_sort_words = $student_sort_words->pluck('sort_word_id')->all();
                    if ($student_sort_words === $sort_words)
                    {
                        $total += $question->mark;
                        $s_total += $question->mark;
                    }

                }
            }
        }

        $mark = $test->lesson->success_mark;


        $test->update([
            'total' => $total,
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
            'status' => $total >= $mark ? 'Pass':'Fail',
        ]);

//        $student_tests_query = UserTest::query()->where('total', '>=', 40)
//            ->where('total', '<=', $total)
//            ->where('lesson_id', $id);
//        $student_tests = $student_tests_query->get();
//        if ($student_tests->count() >= 0 && $total >= 40)
//        {
//            Log::alert($student_tests->count());
//            UserTest::query()->where('total', '>=', 40)
//                ->where('total', '<=', $total)
//                ->where('lesson_id', $id)->update([
//                'approved' => 0,
//            ]);
//            $student_tests = UserTest::query()->where('total', '>=', 40)
//                ->where('total', '>', $total)
//                ->where('lesson_id', $id)->get();
//            if ($student_tests->count() == 0)
//            {
//                $test->update([
//                    'approved' => 1,
//                ]);
//            }
//        }else if($total >= 40){
//            $test->update([
//                'approved' => 1,
//            ]);
//        }



        $student_tests = UserTest::query()->where('total', '>=', $mark)
            ->where('user_id',  $student->id)
            ->where('total', '<=', $total)
            ->where('lesson_id', $id)->orderByDesc('total')->get();



        if (optional($student_tests->first())->total >= $mark)
        {
            UserTest::query()->where('user_id', $student->id)
                ->where('lesson_id', $id)
                ->where('id', '<>', $student_tests->first()->id)->update([
                    'approved' => 0,
                ]);
            UserTest::query()->where('user_id', $student->id)
                ->where('lesson_id', $id)
                ->where('id',  $student_tests->first()->id)->update([
                    'approved' => 1,
                ]);
        }




        $student->user_tracker()->create([
            'lesson_id' => $id,
            'type' => 'test',
            'color' => 'danger',
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
        ]);

        if ($test->user->teacher_student)
        {
            updateTeacherStatistics($test->user->teacher_student->teacher_id);
        }

        $user_assignment = UserAssignment::query()->where('user_id', $student->id)
            ->where('lesson_id', $id)
            ->where('test_assignment', 1)
            ->where('done_test_assignment', 0)
            ->first();

        if ($user_assignment)
        {
            $user_assignment->update([
                'done_test_assignment' => 1,
            ]);

            if (($user_assignment->tasks_assignment && $user_assignment->done_tasks_assignment) || !$user_assignment->tasks_assignment){
                $user_assignment->update([
                    'completed' => 1,
                ]);
            }
        }
//        dd($total);

        return redirect()->route('lesson_test_result', $test->id)->with('message', "تم حفظ الاختبار بنجاح")->with('m-class', 'success');
    }

    public function lessonTestResult($id)
    {
        $title = "نتيجة الاختبار";
        $student = Auth::user();
        $student_test = UserTest::query()->where('user_id', $student->id)->findOrFail($id);
        $level = optional($student_test->lesson)->level;
        $lesson = $student_test->lesson;

        return view('user.lesson.lesson_test_result',compact('student_test', 'title', 'level', 'lesson'));
    }
}
