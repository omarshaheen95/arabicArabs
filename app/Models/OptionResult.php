<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionResult extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'question_id', 'option_id', 'user_test_id'
    ];

    public function user_test()
    {
        return $this->belongsTo(UserTest::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
