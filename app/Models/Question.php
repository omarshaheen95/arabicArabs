<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Question extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    //type : 1:true&false, 2:choose, 3:match, 4:sortWords, 5:writing, 6:speaking
    protected $fillable = [
        'lesson_id', 'content', 'type', 'mark',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function trueFalse()
    {
        return $this->hasOne(TrueFalse::class);
    }

    public function matches()
    {
        return $this->hasMany(QMatch::class);
    }

    public function sortWords()
    {
        return $this->hasMany(SortWord::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('audioQuestion')
            ->singleFile();
        $this
            ->addMediaCollection('imageQuestion')
            ->singleFile();
    }
}
