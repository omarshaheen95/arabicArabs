<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Option extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait,LogsActivityTrait;
    protected $fillable = [
        'question_id', 'content', 'result',
    ];
    protected static $recordEvents = ['updated'];
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('option')
            ->singleFile();
    }
}
