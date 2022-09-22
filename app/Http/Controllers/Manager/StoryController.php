<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\StoryRequest;
use App\Models\Story;
use App\Models\StoryMatch;
use App\Models\StoryOption;
use App\Models\StoryQuestion;
use App\Models\StorySortWord;
use App\Models\StoryTrueFalse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $name = $request->get('name', false);
            $grade = $request->get('grade', false);
            $rows = Story::query()->when($name, function (Builder $query) use ($name) {
                $query->where('name', 'like','%' . $name . '%');
            })->when($grade, function (Builder $query) use ($grade) {
                    $query->where('grade', $grade);
            })->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('grade', function ($row) {
                    return 'Grade ' . $row->grade;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = "";
        return view('manager.story.index', compact('title'));
    }

    public function create()
    {
        $title = 'إضافة قصة';
        return view('manager.story.edit', compact('title',));
    }

    public function store(StoryRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'stories_image');
        }
        if ($request->hasFile('video')) {
            $data['video'] = $this->uploadImage($request->file('video'), 'stories_video');
        }
        $data['active'] = $request->get('active', 1);
        Story::query()->create($data);
        return redirect()->route('manager.story.index')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = "تعديل القصة";
        $story = Story::query()->findOrFail($id);
//        dd($story->questions()->sum('mark'));
        return view('manager.story.edit', compact('title',  'story'));
    }

    public function update(StoryRequest $request, $id)
    {
        $story = Story::query()->findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'stories_image');
        }
        if ($request->hasFile('video')) {
            $data['video'] = $this->uploadImage($request->file('video'), 'stories_video');
        }
        $data['active'] = $request->get('active', 1);

        $story->update($data);
        return redirect()->route('manager.story.index')->with('message', t('Successfully Updated'));
    }

    public function destroy($id)
    {
        $story = Story::query()->findOrFail($id);
        $story->delete();
        return redirect()->route('manager.story.index')->with('message', t('Successfully Deleted'));
    }

    public function storyAssessment($id)
    {
        $title = 'أسئلة القصة';
        $story = Story::query()->findOrFail($id);

        $t_f_questions = StoryQuestion::query()->where('story_id', $id)->where('type', 1)->get();
        $c_questions = StoryQuestion::query()->where('story_id', $id)->where('type', 2)->get();
        $m_questions = StoryQuestion::query()->where('story_id', $id)->where('type', 3)->get();
        $s_questions = StoryQuestion::query()->where('story_id', $id)->where('type', 4)->get();
        $questions = StoryQuestion::query()->where('story_id', $id)->get();
        if ($story->grade >= 1 && $story->grade <= 2)
        {
            $true_false_count = 4;
            $true_false_mark = 2.5;

            $chose_count = 3;
            $chose_mark = 4;

            $match_count = 4;
            $match_mark = 3;
            $match_option = 3;

            $sort_count = 4;
            $sort_mark = 4;
        }elseif($story->grade >= 3 && $story->grade <= 5)
        {
            $true_false_count = 5;
            $true_false_mark = 1;

            $chose_count = 9;
            $chose_mark = 3;

            $match_count = 2;
            $match_mark = 9;
            $match_option = 3;

            $sort_count = 0;
            $sort_mark = 0;
        }else{
            $true_false_count = 10;
            $true_false_mark = 2;

            $chose_count = 7;
            $chose_mark = 2;

            $match_count = 2;
            $match_mark = 8;
            $match_option = 4;

            $sort_count = 0;
            $sort_mark = 0;
        }


        return view('manager.story.assessment', compact('title', 'questions', 'story', 't_f_questions', 'c_questions', 'm_questions', 's_questions',
            'true_false_count', 'chose_count', 'match_count', 'sort_count', 'true_false_mark', 'chose_mark', 'match_mark', 'sort_mark', 'match_option'));
    }

    public function storeAssessmentStory(Request $request, $id, $step)
    {
//        dd($request->all());
        $story = Story::query()->findOrFail($id);
        $old_questions = StoryQuestion::query()->where('story_id', $story->id)->where('type', $step)->get();
//        if (count($old_questions)) {
//            return redirect()->back()->with('message', 'Question added previously')->with('m-class', 'error');
//        }

        if ($story->grade >= 1 && $story->grade <= 2)
        {
            $true_false_mark = 2.5;
            $chose_mark = 4;
            $match_mark = 3;
            $sort_mark = 4;
        }elseif($story->grade >= 3 && $story->grade <= 5)
        {
            $true_false_mark = 1;
            $chose_mark = 3;
            $match_mark = 9;
            $sort_mark = 0;
        }else{
            $true_false_mark = 2;
            $chose_mark = 2;
            $match_mark = 8;
            $sort_mark = 0;
        }


        if ($step == 1) {
            $true_false_questions = $request->get('t_f_question', []);
            $true_false_answers = $request->get('t_f', []);
            foreach ($true_false_questions as $key => $true_false_question) {
                if ($request->hasFile("t_f_q_attachment.$key")) {
                    $attachment = $this->uploadImage(request()->file('t_f_q_attachment')[$key], 'story_assessment_attachments');
                    $question = StoryQuestion::create([
                        'content' => isset($true_false_question) ? $true_false_question : 'no question',
                        'attachment' => $attachment,
                        'type' => 1,
                        'story_id' => $id,
                        'mark' => $true_false_mark
                    ]);
                } else {
                    $question = StoryQuestion::create([
                        'content' => isset($true_false_question) ? $true_false_question : 'no question',
                        'type' => 1,
                        'story_id' => $id,
                        'mark' => $true_false_mark
                    ]);
                }
                StoryTrueFalse::query()->create([
                    'result' => isset($true_false_answers[$key]) && $true_false_answers[$key] == 1 ? 1 : 0,
                    'story_question_id' => $question->id,
                ]);

            }
        }

        if ($step == 2) {
            $c_questions = $request->get('c_question', []);

            foreach ($c_questions as $key => $c_question) {
                if ($request->hasFile("c_q_attachment.$key")) {
                    $attachment = $this->uploadImage(request()->file('c_q_attachment')[$key], 'story_assessment_attachments');
                    $question = StoryQuestion::create([
                        'content' => $c_question ? $c_question : 'no question',
                        'attachment' => $attachment,
                        'type' => 2,
                        'story_id' => $id,
                        'mark' => $chose_mark
                    ]);
                } else {
                    $question = StoryQuestion::create([
                        'content' => $c_question ? $c_question : 'no question',
                        'type' => 2,
                        'story_id' => $id,
                        'mark' => $chose_mark
                    ]);
                }

                $options = $request->get('c_q_option')[$key];
                foreach ($options as $o_key => $option) {
                    StoryOption::create([
                        'content' => $option,
                        'result' => $o_key + 1 == $request->get('c_q_a')[$key] ? 1 : 0,
                        'story_question_id' => $question->id,
                    ]);
                }
            }
        }


        if ($step == 3) {
            $m_questions = $request->get('m_question', []);
            $m_q_options = $request->get('m_q_option', []);
            $m_q_answer = $request->get('m_q_answer', []);
            $m_q_image = $request->get('m_q_image', []);
            foreach ($m_questions as $key => $m_question) {
                if ($request->hasFile("m_q_attachment.$key")) {
                    $attachment = $this->uploadImage(request()->file('m_q_attachment')[$key], 'story_assessment_attachments');
                    $question = StoryQuestion::create([
                        'content' => $m_question ? $m_question : 'no question',
                        'attachment' => $attachment,
                        'type' => 3,
                        'story_id' => $id,
                        'mark' => $match_mark
                    ]);
                } else {
                    $question = StoryQuestion::create([
                        'content' => $m_question ? $m_question : 'no question',
                        'type' => 3,
                        'story_id' => $id,
                        'mark' => $match_mark
                    ]);
                }

                $m_q_options = $request->get("m_q_option", [])[$key];
                $m_q_answer = $request->get("m_q_answer", [])[$key];

                foreach ($m_q_options as $m_a_key => $m_q_option) {
                    $result = $m_q_answer[$m_a_key];
                    if($request->hasFile("m_q_image.$key.$m_a_key")){
                        $image = $this->uploadImage(request()->file('m_q_image')[$key][$m_a_key], 'assessment_match_attachments');
                    }else{
                        $image = null;
                    }
                    StoryMatch::create([
                        'story_question_id' => $question->id,
                        'content' => $m_q_option,
                        'result' => $result,
                        'image' => $image,
                    ]);
                }
            }
        }


        if ($step == 4) {
            $s_questions = $request->get('s_question', []);
            $s_q_options = $request->get('s_q_option', []);
            $counter = 1;
            foreach ($s_questions as $key => $s_question) {
                if ($request->hasFile("s_q_attachment.$key")) {
                    $attachment = $this->uploadImage(request()->file('s_q_attachment')[$key], 'story_assessment_attachments');
                    $question = StoryQuestion::create([
                        'content' => $s_question ? $s_question : 'no question',
                        'attachment' => $attachment,
                        'type' => 4,
                        'story_id' => $id,
                        'mark' => $sort_mark
                    ]);
                } else {
                    $question = StoryQuestion::create([
                        'content' => $s_question ? $s_question : 'no question',
                        'type' => 4,
                        'story_id' => $id,
                        'mark' => $sort_mark
                    ]);
                }

                $question_option = isset($s_q_options[$key]) ? $s_q_options[$key] : [];

                foreach ($question_option as $m_a_key => $option) {
                    StorySortWord::create([
                        'story_question_id' => $question->id,
                        'content' => $option,
                        'ordered' => $counter,
                    ]);
                    $counter++;
                }
                $counter = 1;
            }
        }

        return $this->redirectWith(true, 'manager.story.index', 'Assessment Updated Successfully ');
    }

    public function updateAssessmentStory(Request $request, $id, $step)
    {
        $story = Story::query()->findOrFail($id);
        $s_q_options = $request->get('s_q_option', []);
        $old_s_q_options = $request->get('old_s_q_option', []);
//        dd($old_s_q_options);

        if ($step == 1) {
            //True False Question
            $true_false_questions = $request->get('t_f_question', []);
            $true_false_answers = $request->get('t_f', []);
            foreach ($true_false_questions as $key => $true_false_question) {
                $question = StoryQuestion::query()->where('story_id', $story->id)->find($key);
                if ($question) {
                    $attachment = $request->hasFile("t_f_q_attachment.$key") ? $this->uploadImage(request()->file('t_f_q_attachment')[$key], 'story_assessment_attachments') : $question->getOriginal('attachment');
                    $question->update([
                        'content' => isset($true_false_question) ? $true_false_question : 'no question',
                        'attachment' => $attachment,
                    ]);
                }
            }
            foreach ($true_false_answers as $key => $true_false_answer) {
                $true_false = StoryTrueFalse::query()->where('story_question_id', $key)->first();
                if ($true_false) {
                    $true_false->update(['result' => $true_false_answer]);
                }
            }
        }

        if ($step == 2) {
            //Chose Option
            $c_questions = $request->get('c_question', []);
            foreach ($c_questions as $key => $c_question) {
                $question = StoryQuestion::query()->where('story_id', $story->id)->find($key);
                if ($question) {
                    $attachment = $request->hasFile("c_q_attachment.$key") ? $this->uploadImage(request()->file('c_q_attachment')[$key], 'story_assessment_attachments') : $question->getOriginal('attachment');
                    $question->update([
                        'content' => $c_question ? $c_question : 'no question',
                        'attachment' => $attachment,
                    ]);
                }
            }
            $options = $request->get('c_q_option');
            $options_results = $request->get('c_q_a');
            foreach ($options as $key => $c_options) {
                foreach ($c_options as $o_key => $option) {
                    $answer_option = StoryOption::query()->find($o_key);
                    if ($answer_option) {
                        $answer_option->update([
                            'content' => $option,
                            'result' => $o_key == $options_results[$key] ? 1 : 0,
                        ]);
                    }
                }
            }
        }

        if ($step == 3) {
            $m_questions = $request->get('m_question', []);
            $m_q_options = $request->get('m_q_option', []);
            $m_q_answer = $request->get('m_q_answer', []);
            foreach ($m_questions as $key => $m_question) {
                $question = StoryQuestion::query()->where('story_id', $story->id)->find($key);
                if ($question) {
                    $attachment = $request->hasFile("m_q_attachment.$key") ? $this->uploadImage(request()->file('m_q_attachment')[$key], 'story_assessment_attachments') : $question->getOriginal('attachment');
                    $question->update([
                        'content' => $m_question ? $m_question : 'no question',
                        'attachment' => $attachment,
                    ]);
                }
            }
            foreach ($m_q_options as $m_a_key => $m_q_option) {
                $match = StoryMatch::query()->find($m_a_key);
                $result = $m_q_answer[$m_a_key];
                if($request->hasFile("m_q_image.$m_a_key")){
                    $image = $this->uploadImage(request()->file('m_q_image')[$m_a_key], 'assessment_match_attachments');
                }else{
                    $image = $match->getOriginal('image');
                }
                if ($match) {
                    $match->update([
                        'content' => $m_q_option,
                        'result' => $result,
                        'image' => $image,
                    ]);
                }

            }
        }

        if ($step == 4) {
            $s_questions = $request->get('s_question', []);
            $s_q_options = $request->get('s_q_option', []);
            $old_s_q_options = $request->get('old_s_q_option', []);

            foreach ($s_questions as $key => $s_question) {
                $question = StoryQuestion::query()->where('story_id', $story->id)->find($key);
                if ($question) {
                    $attachment = $request->hasFile("s_q_attachment.$key") ? $this->uploadImage(request()->file('s_q_attachment')[$key], 'story_assessment_attachments') : $question->getOriginal('attachment');
                    $question->update([
                        'content' => $s_question ? $s_question : 'no question',
                        'attachment' => $attachment,
                    ]);
                    $question_option = isset($s_q_options[$key]) ? $s_q_options[$key] : [];
                    $counter = $question->sort_words()->count();
                    foreach ($question_option as $m_a_key => $option) {
                        $counter++;
                        StorySortWord::create([
                            'story_question_id' => $question->id,
                            'content' => $option,
                            'ordered' => $counter,
                        ]);
                    }
                }
            }
            foreach ($old_s_q_options as $key => $old_s_q_option) {
                foreach ($old_s_q_option as $o_key => $s_q_option) {
                    $option = StorySortWord::query()->find($o_key);
                    if ($option) {
                        $option->update(['content' => $s_q_option]);
                    }
                }
            }
        }

        return redirect()->route('manager.story.assessment', $story->id)->with('m-class', 'success')->with('message', 'Assessment Updated Successfully');
//        return $this->redirectWith(false, 'manager.story.index', 'Assessment Updated Successfully ');
    }

    public function removeAttachment($id)
    {
        $question = StoryQuestion::query()->findOrFail($id);
        $question->update([
            'attachment' => null,
        ]);
        return $this->redirectWith(true, null, 'Successfully Deleted');
    }

    public function removeSortWord($id)
    {
        $sort_word = StorySortWord::query()->findOrFail($id);
        $sort_word->delete();
        return $this->redirectWith(true, null, 'Successfully Deleted');
    }

    public function removeMatchAttachment($id)
    {
        $question = StoryMatch::query()->findOrFail($id);
        $question->update([
            'image' => null,
        ]);
        return $this->redirectWith(true, null, 'Successfully Deleted');
    }

}
