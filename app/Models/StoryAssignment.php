<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryAssignment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'story_id', 'test_assignment', 'done_test_assignment', 'completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
