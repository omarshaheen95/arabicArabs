<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SortResult extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'question_id', 'sort_word_id', 'user_test_id'
    ];

    public function user_test()
    {
        return $this->belongsTo(UserTest::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function sort_word()
    {
        return $this->belongsTo(SortWord::class);
    }
}
