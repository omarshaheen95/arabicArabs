<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Question;
use App\Models\TQuestion;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function levels($grade)
    {
        $title = t('Levels list');
        $levels = Level::query()->where('grade', $grade)->get();
        return view('teacher.curriculum.levels', compact('title', 'levels', 'grade'));
    }

    public function lessons($grade, $level)
    {
        $title = t('Lessons list');
        $level = Level::query()->findOrFail($level);
        $lessons = Lesson::query()->where('level_id', $level->id)->get();
        return view('teacher.curriculum.lessons', compact('level', 'grade', 'title', 'lessons'));
    }

    public function lesson($id, $type)
    {
        $lesson = Lesson::query()->findOrFail($id);
        $level = $lesson->level;
        switch ($type)
        {
            case 'learn':
                return view('teacher.curriculum.lesson.learn', compact('lesson', 'level'));
            case 'training':
                $tf_questions = TQuestion::query()->where('type', 1)->where('lesson_id', $lesson->id)->get();
                $c_questions = TQuestion::query()->where('type', 2)->where('lesson_id', $lesson->id)->get();
                $m_questions = TQuestion::query()->where('type', 3)->where('lesson_id', $lesson->id)->get();
                $s_questions = TQuestion::query()->where('type', 4)->where('lesson_id', $lesson->id)->get();
                return view('teacher.curriculum.lesson.training', compact('lesson', 'tf_questions', 'c_questions', 'm_questions', 's_questions', 'level'));
            case 'test':
                $questions = Question::query()->where('lesson_id', $id)->get();
                return view('teacher.curriculum.lesson.test', compact('questions', 'lesson', 'level'));
            case 'play':
                $game = Game::query()->where('id', $lesson->game_order)->first();
                return view('teacher.curriculum.lesson.play', compact('game', 'level', 'lesson'));
            default:
                return redirect()->route('teacher.home');
        }
    }

}
