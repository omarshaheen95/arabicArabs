<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class TQuestion extends Model  implements HasMedia
{
    use SoftDeletes, HasMediaTrait;
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
        return $this->hasOne(TTrueFalse::class);
    }

    public function matches()
    {
        return $this->hasMany(TMatch::class);
    }

    public function sortWords()
    {
        return $this->hasMany(TSortWord::class);
    }

    public function options()
    {
        return $this->hasMany(TOption::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('t_audioQuestion')
            ->singleFile();
        $this
            ->addMediaCollection('t_imageQuestion')
            ->singleFile();
    }
}
