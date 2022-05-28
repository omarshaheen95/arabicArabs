<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;

class Lesson extends Model
{
    use SoftDeletes, InteractsWithMedia;
    //lesson type : 'reading', 'writing', 'listening', 'speaking', 'grammar'
    protected $fillable = [
        'name', 'content', 'grade', 'lesson_type', 'ordered',' success_mark', 'active',
    ];
}
