<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTrueFalse extends Model
{
    use SoftDeletes;
    protected $fillable = [
        't_question_id', 'result'
    ];

    public function t_question()
    {
        return $this->belongsTo(TQuestion::class);
    }
}
