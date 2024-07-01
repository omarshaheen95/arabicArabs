<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpeakingResult extends Model
{
    use SoftDeletes, Pathable,LogsActivityTrait;
    protected $fillable = [
        'user_test_id', 'question_id', 'attachment'
    ];
    protected static $recordEvents = ['updated'];
    protected $pathAttribute = [
        'attachment'
    ];

    public function user_test()
    {
        return $this->belongsTo(UserTest::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
