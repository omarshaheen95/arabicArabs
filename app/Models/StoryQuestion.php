<?php

namespace App\Models;

use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryQuestion extends Model
{
    use SoftDeletes, Pathable;

    //type : '1:true&false, 2:choose, 3:match, 4:sortWords'
    protected $fillable = [
        'story_id', 'content', 'attachment', 'type', 'mark'
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    protected $pathAttribute = [
        'attachment'
    ];

    public function trueFalse()
    {
        return $this->hasOne(StoryTrueFalse::class);
    }

    public function matches()
    {
        return $this->hasMany(StoryMatch::class);
    }

    public function sort_words()
    {
        return $this->hasMany(StorySortWord::class);
    }

    public function options()
    {
        return $this->hasMany(StoryOption::class);
    }
    public function getTypeNameAttribute()
    {
        switch ($this->type) {
            case 1:
                return t('True False');
            case 2:
                return t('Choose Answer');
            case 3:
                return t('Match');
            case 4:
                return t('Sort Words');
            default:
                return '';
        }
    }
    public function getTypeEngNameAttribute()
    {
        switch ($this->type) {
            case 1:
                return 'True False';
            case 2:
                return 'Choose Answer';
            case 3:
                return 'Match';
            case 4:
                return 'Re-arrange Words';
            default:
                return '';
        }
    }
}
