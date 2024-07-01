<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\Pathable;
use Astrotomic\Translatable\Translatable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Story extends Model
{
    use SoftDeletes,CascadeSoftDeletes, Pathable,LogsActivityTrait;
    protected $fillable = [
        'name', 'image', 'video', 'alternative_video', 'content', 'grade', 'active', 'ordered',
    ];

    public $pathAttribute = [
        'image', 'video', 'alternative_video'
    ];

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route('manager.story.edit', $this->id), 'permission' => 'edit stories'],
                ['key' => 'practise', 'name' => t('Assessment'), 'route' => route('manager.story.assessment', $this->id), 'permission' => 'edit story assessment'],
                ['key' => 'blank', 'name' => t('Assessment Preview'), 'route' => route('manager.story.review', $this->id), 'permission' => 'edit story assessment'],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete stories'],
            ];
        } elseif (\request()->is('school/*')) {
            $actions = [];
        } elseif (\request()->is('teacher/*')) {
            $actions = [];
        } elseif (\request()->is('supervisor/*')) {
            $actions = [];
        }
        return view('general.action_menu')->with('actions', $actions);

    }


    public function scopeFilter(Builder $query,$request=null): Builder{
        if (!$request){
            $request = \request();
        }
        return $query
            ->when($value = $request->get('id', false), function (Builder $query) use ($value) {
                $query->where('id', $value);
            })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            })->when($value = $request->get('grade', false), function (Builder $query) use ($value) {
                $query->where('grade', $value);
            })->when($value = $request->get('name', false), function (Builder $query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%');

            })->when($value = $request->get('active', false), function (Builder $query) use ($value) {
                $query->where('active', $value != 2);
            })
            ->when($value = $request->get('hidden_status', false) == 1, function (Builder $query) use ($request) {
                $query->whereDoesntHave('hidden_stories', function ($query) use ($request) {
                    $query->where('school_id', Auth::guard('school')->user()->id);
                });
            })
            ->when($value = $request->get('hidden_status', false) == 2, function (Builder $query) use ($request) {
                $query->whereHas('hidden_stories', function ($query) use ($request) {
                    $query->where('school_id', Auth::guard('school')->user()->id);
                });
            });
    }

    public function questions()
    {
        return $this->hasMany(StoryQuestion::class);
    }

    public function hiddenStories(): HasMany
    {
        return $this->hasMany(HiddenStory::class, 'story_id');
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

    public function getGradeArNameAttribute()
    {
        switch($this->grade){
            case 15:
                return 'المستوى التمهيدي';
            default:
                return  'المستوى '.$this->grade;
        }
    }

}
