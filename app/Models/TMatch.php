<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TMatch extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    protected $fillable = [
    't_question_id', 'content', 'result',
];

    public function question()
{
    return $this->belongsTo(TQuestion::class);
}

    public function registerMediaCollections(): void
{
    $this
        ->addMediaCollection('t_match')
        ->singleFile();
}
}
