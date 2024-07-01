<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class StoryQuestion extends Model
{
    use SoftDeletes, Pathable,LogsActivityTrait;

    //type : '1:true&false, 2:choose, 3:match, 4:sortWords'
    protected $fillable = [
        'story_id', 'content', 'attachment', 'type', 'mark'
    ];
    protected static $recordEvents = ['updated'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    protected $pathAttribute = [
        'attachment'
    ];

    public function trueFalse()
    {
        return $this->hasOne(StoryTrueFalse::class);
    }

    public function matches()
    {
        return $this->hasMany(StoryMatch::class);
    }

    public function sort_words()
    {
        return $this->hasMany(StorySortWord::class);
    }

    public function options()
    {
        return $this->hasMany(StoryOption::class);
    }
    public function true_false_results()
    {
        return $this->hasMany(StoryTrueFalseResult::class, 'story_question_id');
    }

    public function option_results()
    {
        return $this->hasMany(StoryOptionResult::class, 'story_question_id');
    }

    public function match_results()
    {
        return $this->hasMany(StoryMatchResult::class, 'story_question_id');
    }

    public function sort_results()
    {
        return $this->hasMany(StorySortResult::class, 'story_question_id');
    }
    public function getTypeNameAttribute()
    {
        switch ($this->type) {
            case 1:
                return t('True False');
            case 2:
                return t('Choose Answer');
            case 3:
                return t('Match');
            case 4:
                return t('Sort Words');
            default:
                return '';
        }
    }

    public function getTypeEngNameAttribute()
    {
        switch ($this->type) {
            case 1:
                return 'صح أو خطأ';
            case 2:
                return 'اختيار من متعدد';
            case 3:
                return 'توصيل';
            case 4:
                return 'ترتيب';
            default:
                return '';
        }
    }

    public function studentAnswer($test_id, $student_id = null)
    {
        if (is_null($student_id)) {
            $student_id = Auth::guard('web')->user()->id;
        }
        $data = [];
        switch ($this->type) {
            case 1:
                if ($this->trueFalse->result == 1) {
                    $data['question_answer'] = 'صح';
                } else {
                    $data['question_answer'] = 'خطأ';
                }
                $result = StoryTrueFalseResult::query()

                    ->where('student_story_test_id', $test_id)
                    ->where('story_question_id', $this->id)->first();

                if (!is_null($result)) {
                    if ($result->result == 1) {
                        $data['student_answer'] = 'صح';
                    } else {
                        $data['student_answer'] = 'خطأ';
                    }

                    if ($result->result == $this->trueFalse->result) {
                        $data['class'] = 'text-success';
                    } else {
                        $data['class'] = 'text-danger';
                    }
                } else {
                    $data['class'] = 'text-danger';
                }

                return $data;
            case 2:
                //Question Answer
                $question_result = $this->options()->where('result', 1)->first();
                if ($question_result) {
                    $data['question_answer'] = $question_result->content;
                } else {
                    $data['question_answer'] = '';
                }

                //Student Answer
                $result = StoryOptionResult::query()

                    ->where('student_story_test_id', $test_id)
                    ->where('story_question_id', $this->id)->first();

                if (!is_null($result)) {
                    $data['student_answer'] = optional($result->option)->content;

                    if (!is_null($question_result) && $result->story_option_id == $question_result->id) {
                        $data['class'] = 'text-success';
                    } else {
                        $data['class'] = 'text-danger';
                    }
                } else {
                    $data['class'] = 'text-danger';
                }

                return $data;
            case 3:
                $matches = $this->matches;
                $data['question_answer'] = '';
                foreach ($matches as $match) {
                    $data['question_answer'] .= '<span >' . $match->content . ' - ' . $match->result . '</span><br />';
                }

                $student_results = StoryMatchResult::query()
                    ->where('story_question_id', $this->id)
                    ->where('student_story_test_id', $test_id)->get();
                $student_result_html = '';
                foreach ($student_results as $student_result) {
                    if ($student_result->story_match_id == $student_result->story_result_id) {
                        $student_result_html .= '<span class="text-success">' . $student_result->match->content . ' - ' . $student_result->result->result . '</span><br />';
                    } else {
                        $student_result_html .= '<span class="text-danger">' . $student_result->match->content . ' - ' . $student_result->result->result . '</span><br />';
                    }
                }
                $data['student_answer'] = $student_result_html;
                return $data;
            case 4:
                $sort_words = $this->sort_words()->get()->pluck('id')->all();
                $student_sort_words = StorySortResult::query()
                    ->where('story_question_id', $this->id)
                    ->where('student_story_test_id', $test_id)
                    ->get();
                if (count($student_sort_words)) {
                    $student_sort_words_array = $student_sort_words->pluck('story_sort_word_id')->all();
                    if ($student_sort_words_array === $sort_words) {
                        $data['student_answer'] = '';
                        $html_string = '';
                        $student_sort_words_count = count($student_sort_words) - 1;
                        foreach ($student_sort_words as $key => $student_sort_word) {
                            if ($key == $student_sort_words_count) {
                                $html_string .= $student_sort_word->sort_word->content;
                            } else {
                                $html_string .= $student_sort_word->sort_word->content . ' - ';
                            }
                        }
                        $data['student_answer'] = '<p class="text-success">' . $html_string . '</p>';
                    } else {
                        $data['student_answer'] = '';
                        $html_string = '';
                        $student_sort_words_count = count($student_sort_words) - 1;
                        foreach ($student_sort_words as $key => $student_sort_word) {
                            if ($key == $student_sort_words_count) {
                                $html_string .= $student_sort_word->sort_word->content;
                            } else {
                                $html_string .= $student_sort_word->sort_word->content . ' - ';
                            }
                        }
                        $data['student_answer'] = '<p class="text-danger">' . $html_string . '</p>';
                    }

                } else {
                    $data['student_answer'] = '';
                    $html_string = '';
                    $data['student_answer'] = '<p class="text-danger">' . $html_string . '</p>';
                }

                $data['question_answer'] = '';
                $html_string = '';
                $sort_words_count = count($this->sort_words) - 1;
                foreach ($this->sort_words as $key=> $sort_word) {

                    if ($key == $sort_words_count) {
                        $html_string .= $sort_word->content;
                    } else {
                        $html_string .= $sort_word->content . ' - ';
                    }
                }

                $data['question_answer'] = $html_string;

                return $data;
            default :
                return $data;

        }
    }

}
