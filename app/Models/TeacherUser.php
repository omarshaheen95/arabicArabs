<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class TeacherUser extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'user_id', 'teacher_id',
    ];
    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query->when($value = $request->get('teacher_id',false), function (Builder $query) use ($value){
            $query->where('teacher_id', $value);
        })->when($value = $request->get('user_id',false), function (Builder $query) use ($value){
            is_array($value)?$query->whereIn('user_id', $value):$query->where('user_id', $value);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
