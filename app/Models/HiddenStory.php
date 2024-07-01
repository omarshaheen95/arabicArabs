<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class HiddenStory extends Model
{
    use SoftDeletes;
    protected $fillable = ['story_id', 'school_id'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function getActionButtonsAttribute()
    {
        $actions = [
            ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id,'permission'=>'delete hidden stories'],
        ];
        return view('general.action_menu')->with('actions', $actions);
    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query->when($value = $request->get('id'), function (Builder $query) use ($value) {
            $query->where('id', $value);
        })->when($value = $request->get('story_id'), function (Builder $query) use ($value) {
            $query->where('story_id', $value);
        })->when($value = $request->get('grade'), function (Builder $query) use ($value) {
            $query->whereHas('story', function (Builder $query) use ($value) {
                $query->where('grade', $value);
            });
        })->when($value = $request->get('school_id'), function (Builder $query) use ($value) {
            $query->where('school_id', $value);
        })->when($value = $request->get('start_date', false), function (Builder $query) use ($value) {
            $query->whereDate('created_at', '>=', Carbon::parse($value)->startOfDay());
        })->when($value = $request->get('end_date', false), function (Builder $query) use ($value) {
            $query->whereDate('created_at', '<=', Carbon::parse($value)->endOfDay());
        })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);
        });
    }
}
