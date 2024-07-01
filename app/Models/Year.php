<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Activitylog\Traits\LogsActivity;

class Year extends Model
{
    use SoftDeletes,LogsActivityTrait;

    protected $fillable = [
        'name' , 'slug','default'
    ];

    public function scopeFilter(Builder $query, Request $request)
    {
        return $query
            ->when($name = $request->get('name', false), function (Builder $query) use ($name) {
                $query->where('name', $name);
            })->when($value = $request->get('row_id',[]),function (Builder $query) use ($value){
                $query->whereIn('id', $value);
            })->when($value = $request->get('default',false),function (Builder $query) use ($value){
                $query->where('default', $value);
            });
    }

    public function getActionButtonsAttribute()
    {
        $actions =  [
            ['key'=>'edit','name'=>t('Edit'),'route'=>route('manager.year.edit', $this->id),'permission'=>'edit years'],
            ['key'=>'delete','name'=>t('Delete'),'route'=>$this->id,'permission'=>'delete years'],
        ];
        return view('general.action_menu')->with('actions',$actions);

    }
}
