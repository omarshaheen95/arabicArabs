<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class UserTrackerStory extends Model
{
    use SoftDeletes;
    //Type 'watching', 'reading', 'test'
    protected $fillable = [
        'user_id', 'story_id', 'type', 'color', 'start_at', 'end_at'
    ];

    protected $appends = ['total_time','type_text'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function getTotalTimeAttribute()
    {
        if (!is_null($this->start_at) && !is_null($this->end_at))
        {
            return Carbon::parse($this->start_at)->diffInMinutes($this->end_at);
        }else{
            return '';
        }
    }

    public function getTypeTextAttribute()
    {
        switch ($this->type)
        {
            case 'watching':
                $content = t('start watch',[
                    'story' => $this->story->name,
                    'level' => $this->story->grade_name,
                ]);
                return $content;
            case 'reading':
                $content = t('start reading',[
                    'story' => $this->story->name,
                    'level' => $this->story->grade_name,
                ]);
                return $content;
            case 'test':
                $content = t('start test of',[
                    'story' => $this->story->name,
                    'level' => $this->story->grade_name,
                ]);
                if (!is_null($this->start_at) && !is_null($this->end_at))
                {
                    $start = Carbon::parse($this->start_at);
                    $end = Carbon::parse($this->end_at);
                    $diff = $start->diffInMinutes($end, false);
                    $content .= ' <span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill">'.$diff.' '.t('min').'</span>';
                }
                return $content;
            default:
                return $this->type;
        }
    }

    public function scopeFilter(Builder $query, $request =null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query
            ->when($value = $request->get('story_id', false), function (Builder $query) use ($value) {
                $query->where('story_id', $value);
            })->when($value = $request->get('grade', false), function (Builder $query) use ($value) {
                    $query->whereHas('story', function (Builder $query) use ($value) {
                        $query->where('grade', $value);
                });
            })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            })->when($value = $request->get('type', false), function (Builder $query) use ($value) {
                $query->where('type', $value);
            })->when($value = $request->get('start_date', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '>=', $value);
            })->when($value = $request->get('end_date', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '<=', $value);
            });
    }

}
