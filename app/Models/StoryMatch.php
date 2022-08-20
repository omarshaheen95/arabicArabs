<?php

namespace App\Models;

use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryMatch extends Model
{
    use SoftDeletes, Pathable;
    protected $fillable = [
        'story_question_id', 'content', 'image', 'result'
    ];

    protected $pathAttribute = [
        'image'
    ];

    public function question()
    {
        return $this->belongsTo(StoryQuestion::class);
    }
}
