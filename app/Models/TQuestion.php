<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class TQuestion extends Model  implements HasMedia
{
    use SoftDeletes, HasMediaTrait,LogsActivityTrait;
    //type : 1:true&false, 2:choose, 3:match, 4:sortWords, 5:writing, 6:speaking
    protected $fillable = [
        'lesson_id', 'content', 'type', 'mark',
    ];
    protected static $recordEvents = ['updated'];

    public function getTypeNameAttribute()
    {
        switch ($this->type)
        {
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

    public function getActionButtonsAttribute()
    {
        $actions =  [
            ['key'=>'edit','name'=>t('Edit'),'route'=>route('manager.lesson.training.edit', ['id'=>$this->lesson_id,'question_id'=>$this->id]),'permission'=>'edit lesson training'],
            ['key'=>'delete','name'=>t('Delete'),'route'=>$this->id,'permission'=>'delete lesson training'],
        ];
        return view('general.action_menu')->with('actions',$actions);
    }

    public function scopeFilter(Builder $query,$request=null): Builder{
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('id',false), function (Builder $query) use ($value) {
            return $query->where('id', $value);
        })->when($value = $request->get('row_id',[]), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);
        })->when($value = $request->get('lesson_id',false), function (Builder $query) use ($value) {
            return $query->where('lesson_id', $value);
        })->when($value = $request->get('type',false), function (Builder $query) use ($value) {
            return $query->where('type', $value);
        })->when($value = $request->get('content',false), function (Builder $query) use ($value) {
            return $query->where('content', 'like', '%' . $value . '%');
        });
    }

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
