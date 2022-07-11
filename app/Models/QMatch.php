<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class QMatch extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $table = 'matches';

    protected $fillable = [
        'question_id', 'content', 'result',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('match')
            ->singleFile();
    }
}
