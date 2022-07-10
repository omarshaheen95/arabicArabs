<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrueFalse extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'question_id', 'result'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
