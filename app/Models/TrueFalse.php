<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrueFalse extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'question_id', 'result'
    ];
    protected static $recordEvents = ['updated'];
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
