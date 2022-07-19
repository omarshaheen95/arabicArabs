<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrueFalseResult extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'question_id', 'result', 'user_test_id'
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
