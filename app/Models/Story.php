<?php

namespace App\Models;

use App\Traits\Pathable;
use Astrotomic\Translatable\Translatable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use SoftDeletes,CascadeSoftDeletes, Pathable;
    protected $fillable = [
        'name', 'image', 'video', 'alternative_video', 'content', 'grade', 'active', 'ordered',
    ];

    public $pathAttribute = [
        'image', 'video', 'alternative_video'
    ];

    public function questions()
    {
        return $this->hasMany(StoryQuestion::class);
    }

    public function getGradeNameAttribute()
    {
        switch($this->grade){
            case 15:
                return 'KG 2/ Year 1';
            default:
                return "Grade ".$this->grade." / Year ". ($this->grade+1);
        }
    }

    public function getActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route('manager.story.assessment', $this->id) . '" class="btn btn-danger ">Assessment</a> ';
        $button .= '<a href="' . route('manager.story.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
        return $button;
    }
}
