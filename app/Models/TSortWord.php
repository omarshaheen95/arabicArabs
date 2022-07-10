<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TSortWord extends Model
{
    use SoftDeletes;
    protected $fillable = [
        't_question_id', 'content', 'ordered',
    ];

    public function question()
    {
        $this->belongsTo(TQuestion::class);
    }}
