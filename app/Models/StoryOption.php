<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryOption extends Model
{
    use SoftDeletes, Pathable,LogsActivityTrait;
    protected $fillable = [
        'story_question_id', 'content', 'image', 'result',
    ];
    protected static $recordEvents = ['updated'];
    protected $pathAttribute = [
        'image'
    ];

    public function question()
    {
        return $this->belongsTo(StoryQuestion::class);
    }

    public function OptionStudent($question, $student)
    {
        $id = OptionResult::query()->where('student_id',$student)->where('question_id',$question)->first();

        if(!$id){
            return 0;
        }else{
            return $id->option_id;
        }
    }
}
