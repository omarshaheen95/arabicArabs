<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAssignment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'lesson_id', 'tasks_assignment', 'test_assignment', 'done_tasks_assignment', 'done_test_assignment', 'completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
