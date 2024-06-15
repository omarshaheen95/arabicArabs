<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\TMatch;
use App\Models\TOption;
use App\Models\TQuestion;
use App\Models\TSortWord;
use App\Models\TTrueFalse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TrainingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show lesson training')->only('index');
        $this->middleware('permission:edit lesson training')
            ->only(['edit','update','deleteTQuestionAttachment','deleteTMatchImage','removeTSortWord']);
        $this->middleware('permission:preview lesson training')->only('preview');
        $this->middleware('permission:delete lesson training')->only('destroy');
    }

    public function index(Request $request, $lesson)
    {
        if (request()->ajax())
        {
            $request->merge(['lesson_id' => $lesson]);
            $rows = TQuestion::query()->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('type_name', function ($row) {
                    return $row->type_name;
                })
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $lesson = Lesson::query()->findOrFail($lesson);
        $title = t('Show Training Questions');
        return view('manager.lesson.training.index', compact('title', 'lesson'));
    }

    public function edit($id,$question_id=null)
    {
        $title = t('Training');
        $lesson = Lesson::query()->with('grade')->findOrFail($id);

        if (in_array($lesson->lesson_type, [ 'grammar', 'dictation', 'rhetoric']))
        {
            if ($lesson->grade->grade_number <= 3)
            {
                $questions = TQuestion::with(['trueFalse','matches.media','options','media'])
                    ->where('lesson_id', $lesson->id)
                    ->when($question_id,function ($query) use ($question_id){
                        $query->where('id', $question_id);
                    })
                    ->whereIn('type',[1,2,3])->get();

                $c_questions = $questions->where('type', 2);
                $t_f_questions = $questions->where('type', 1);
                $m_questions = $questions->where('type', 3);
                $total_count = TQuestion::query()->where('lesson_id', $lesson->id)->count();
                $data_count = [
                    'choose' =>   $total_count ? $c_questions->count() :6,
                    'match' =>  $total_count ? $m_questions->count() :4,
                    'true_false' =>  $total_count ? $t_f_questions->count() :5
                ];
                $compact =  compact('title', 'lesson', 'm_questions', 't_f_questions', 'c_questions', 'data_count');

                if ($question_id){
                    return view('manager.lesson.training.edit_specific_question', $compact);
                }
                return view('manager.lesson.training.new_skills_training',$compact);
            }else{
                $questions = TQuestion::with(['trueFalse','options','media'])
                    ->where('lesson_id', $lesson->id)
                    ->when($question_id,function ($query) use ($question_id){
                        $query->where('id', $question_id);
                    })
                    ->whereIn('type',[1,2])->get();
                $c_questions = $questions->where('type', 2);
                $t_f_questions = $questions->where('type', 1);
                $data_count = [
                    'choose' =>   8,
                    'true_false' => 7
                ];
                $compact = compact('title', 'lesson', 't_f_questions', 'c_questions', 'data_count');
                if ($question_id){
                    return view('manager.lesson.training.edit_specific_question', $compact);
                }
                return view('manager.lesson.training.new_skills_training', $compact);
            }
        }

        $t_questions = TQuestion::with(['trueFalse','options','matches.media','sortWords','media'])
            ->where('lesson_id', $lesson->id)
            ->when($question_id,function ($query) use ($question_id){
                $query->where('id', $question_id);
            })
            ->get();
        $t_f_questions = $t_questions->where('type', 1);
        $c_questions = $t_questions->where('type', 2);
        $m_questions = $t_questions->where('type', 3);
        $s_questions = $t_questions->where('type', 4);
        $compact = compact('title', 'lesson', 'm_questions', 't_f_questions', 'c_questions', 's_questions');

        if ($question_id){
            return view('manager.lesson.training.edit_specific_question', $compact);
        }
        return view('manager.lesson.training.training', $compact);
    }

    public function update(Request $request, $id, $type)
    {
        $lesson = Lesson::query()->findOrFail($id);
        switch ($type) {
            case 1:
                $this->trueFalseTraining($request, $lesson);
                return $this->sendResponse(null,t('Successfully Updated'));
            case 2:
                $this->optionsTraining($request, $lesson);
                return $this->sendResponse(null,t('Successfully Updated'));
            case 3:
                $this->matchTraining($request, $lesson);
                return $this->sendResponse(null,t('Successfully Updated'));
            case 4:
                $this->sortTraining($request, $lesson);
                return $this->sendResponse(null,t('Successfully Updated'));
        }
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        TQuestion::destroy($request->get('row_id'));
        return $this->sendResponse(null,t('Successfully Deleted'));
    }
    public function deleteTQuestionAttachment($id)
    {
        $question = TQuestion::query()->findOrFail($id);
        $question->clearMediaCollection('t_imageQuestion');
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function deleteTMatchImage($id)
    {
        $match = TMatch::query()->findOrFail($id);
        $match->clearMediaCollection('t_match');
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function removeTSortWord($id)
    {
        $sort_word = TSortWord::query()->findOrFail($id);
        $sort_word->delete();
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function matchTraining(Request $request, $lesson)
    {
        $m_questions = $request->get('m_question', []);
        $m_question_options = $request->get('m_q_option', []);
        $m_q_answer = $request->get('m_q_answer', []);
        $marks = $request->get("mark", []);
        foreach ($m_questions as $key => $m_question) {
            $question = TQuestion::query()->create([
                'content' => $m_question ? $m_question : 'no question',
                'type' => 3,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key] ?? 8
            ]);
            if ($request->hasFile("m_q_attachment.$key")) {
                $question->addMediaFromRequest("m_q_attachment.$key")
                    ->toMediaCollection('t_imageQuestion');
            }


            $m_q_options = isset($m_question_options[$key]) ? $m_question_options[$key] : [];
            foreach ($m_q_options as $m_a_key => $m_q_option) {
                $result = $m_q_answer[$key][$m_a_key];
//                        dd($m_q_option);
                $match = TMatch::query()->create([
                    't_question_id' => $question->id,
                    'content' => $m_q_option,
                    'result' => $result,
                ]);
                if ($request->hasFile("m_q_image.$key.$m_a_key")) {
                    $match->addMediaFromRequest("m_q_image.$key.$m_a_key")
                        ->toMediaCollection('t_match');
                }
            }
        }

        $m_questions = $request->get('old_m_question', []);
        $m_question_options = $request->get('old_m_q_option', []);
        $m_q_answer = $request->get('old_m_q_answer', []);
        foreach ($m_questions as $key => $m_question) {
            $question = TQuestion::query()->find($key);
            if ($question) {
                $question->update([
                    'content' => $m_question ? $m_question : 'no question',
                    'mark' => $marks[$key] ?? 8
                ]);
                if ($request->hasFile("old_m_q_attachment.$key")) {
                    $question->addMediaFromRequest("old_m_q_attachment.$key")
                        ->toMediaCollection('t_imageQuestion');
                }
            }
        }
        foreach ($m_question_options as $m_a_key => $m_q_option) {
            $result = $m_q_answer[$m_a_key];

            $match = TMatch::query()->find($m_a_key);
            if ($match) {
                $match->update([
                    'content' => $m_q_option,
                    'result' => $result,
                ]);
                if ($request->hasFile("old_m_q_image.$m_a_key")) {
                    $match->addMediaFromRequest("old_m_q_image.$m_a_key")
                        ->toMediaCollection('t_match');
                }
            }

        }

        return true;
    }

    public function trueFalseTraining(Request $request, $lesson)
    {
        $true_false_questions = $request->get('t_f_question', []);
        $true_false_answers = $request->get('t_f', []);
        $marks = $request->get("mark", []);
        foreach ($true_false_questions as $key => $true_false_question) {
            $question = TQuestion::query()->create([
                'content' => isset($true_false_question) ? $true_false_question : 'no question',
                'type' => 1,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key] ?? 6,
            ]);
            if ($request->hasFile("t_f_q_attachment.$key")) {
                $question->addMediaFromRequest("t_f_q_attachment.$key")
                    ->toMediaCollection('t_imageQuestion');
            }
            TTrueFalse::query()->create([
                'result' => isset($true_false_answers[$key]) && $true_false_answers[$key] == 1 ? 1 : 0,
                't_question_id' => $question->id,
            ]);

        }


        $true_false_questions = $request->get('old_t_f_question', []);
        $true_false_answers = $request->get('old_t_f', []);
        foreach ($true_false_questions as $key => $true_false_question) {
            $question = TQuestion::query()->find($key);
            if ($question)
            {
                $question->update([
                    'content' => isset($true_false_question) ? $true_false_question : 'no question',
                    'mark' => $marks[$key] ?? 6,
                ]);
                if ($request->hasFile("old_t_f_q_attachment.$key")) {
                    $question->addMediaFromRequest("old_t_f_q_attachment.$key")
                        ->toMediaCollection('t_imageQuestion');
                }
            }



        }
        foreach($true_false_answers as $key => $true_false_answer)
        {
            $trueFalse = TTrueFalse::query()->where('t_question_id', $key)->first();
            if ($trueFalse)
            {
                $trueFalse->update([
                    'result' => $true_false_answer == 1 ? 1 : 0,
                ]);
            }
        }
        return true;
    }

    public function optionsTraining(Request $request, $lesson)
    {
        $c_questions = $request->get('c_question', []);
        $marks = $request->get("mark", []);

        foreach ($c_questions as $key => $c_question) {
            $question = TQuestion::query()->create([
                'content' => $c_question ? $c_question : 'no question',
                'type' => 2,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key] ?? 7,
            ]);
            if ($request->hasFile("c_q_attachment.$key")) {
                $question->addMediaFromRequest("c_q_attachment.$key")
                    ->toMediaCollection('t_imageQuestion');
            }

            $options = $request->get('c_q_option')[$key];
            foreach ($options as $o_key => $option) {
                TOption::query()->create([
                    'content' => $option,
                    'result' => $o_key + 1 == $request->get('c_q_a')[$key] ? 1 : 0,
                    't_question_id' => $question->id,
                ]);
            }
        }

        $c_questions = $request->get('old_c_question', []);

        foreach ($c_questions as $key => $c_question) {
            $question = TQuestion::query()->find($key);
            if ($question)
            {

                $question->update([
                    'content' => $c_question ? $c_question : 'no question',
                    'mark' => $marks[$key] ?? 7,
                ]);
                if ($request->hasFile("old_c_q_attachment.$key")) {
                    $question->addMediaFromRequest("old_c_q_attachment.$key")
                        ->toMediaCollection('t_imageQuestion');
                }
            }
        }
        $options = $request->get('old_c_q_option',[]);
        $options_results = $request->get('old_c_q_a',[]);
        foreach ($options as $key => $c_options) {
            foreach ($c_options as $o_key => $option) {
                $answer_option = TOption::query()->find($o_key);
                if ($answer_option) {
                    $answer_option->update([
                        'content' => $option,
                        'result' => $o_key == $options_results[$key] ? 1 : 0,
                    ]);
                }
            }
        }

        return true;
    }

    public function sortTraining(Request $request, $lesson)
    {
        $s_questions = $request->get('s_question', []);
        $s_q_options = $request->get('s_q_option', []);
        $marks = $request->get("mark", []);
        $counter = 1;
        foreach ($s_questions as $key => $s_question) {
            $question = TQuestion::query()->create([
                'content' => $s_question ? $s_question : 'no question',
                'type' => 4,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key] ?? 8,
            ]);
            if ($request->hasFile("s_q_attachment.$key")) {
                $question->addMediaFromRequest("s_q_attachment.$key")
                    ->toMediaCollection('t_imageQuestion');
            }

            $question_option = isset($s_q_options[$key]) ? $s_q_options[$key] : [];

            foreach ($question_option as $m_a_key => $option) {
                TSortWord::query()->create([
                    't_question_id' => $question->id,
                    'content' => $option,
                    'ordered' => $counter,
                ]);
                $counter++;
            }
            $counter = 1;
        }

        $s_questions = $request->get('old_s_question', []);
        $s_q_options = $request->get('s_q_option', []);
        $old_s_q_options = $request->get('old_s_q_option', []);

        foreach ($s_questions as $key => $s_question) {
            $question = TQuestion::query()->where('lesson_id', $lesson->id)->find($key);
            if ($question) {
                $question->update([
                    'content' => $s_question ? $s_question : 'no question',
                    'mark' => $marks[$key] ?? 8,
                ]);

                if ($request->hasFile("old_s_q_attachment.$key")) {
                    $question->addMediaFromRequest("old_s_q_attachment.$key")
                        ->toMediaCollection('t_imageQuestion');
                }

                $question_option = isset($s_q_options[$key]) ? $s_q_options[$key] : [];
                $counter = $question->sortWords()->count();
                foreach ($question_option as $m_a_key => $option) {
                    $counter++;
                    TSortWord::query()->create([
                        't_question_id' => $question->id,
                        'content' => $option,
                        'ordered' => $counter,
                    ]);
                }
            }
        }
        foreach ($old_s_q_options as $key => $old_s_q_option) {
            foreach ($old_s_q_option as $o_key => $s_q_option) {
                $option = TSortWord::query()->find($o_key);
                if ($option) {
                    $option->update(['content' => $s_q_option]);
                }
            }
        }
    }
}
