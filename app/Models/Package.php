<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Package extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'name', 'days', 'price', 'active',
    ];


    public function getActionButtonsAttribute()
    {
        $actions=[];
        if (\request()->is('manager/*')){
            $actions =  [
                ['key'=>'edit','name'=>t('Edit'),'route'=>route('manager.package.edit', $this->id),'permission'=>'edit packages'],
                ['key'=>'delete','name'=>t('Delete'),'route'=>$this->id,'permission'=>'delete packages'],
            ];
        }
        elseif (\request()->is('supervisor/*')){
            $actions =  [];
        }
        return view('general.action_menu')->with('actions',$actions);

    }

    public function scopeFilter(Builder $query,$request=null): Builder{
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('id',false), function (Builder $query) use ($value) {
            $query->where('id', $value);
        })->when($value = $request->get('row_id',[]), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);
        })->when($value = $request->get('name',false), function (Builder $query) use ($value) {
            $query->where('name','LIKE','%'.$value.'%');
        })->when($value = $request->get('days',false), function (Builder $query) use ($value) {
            $query->where('days', $value);
        })->when($value = $request->get('price',false), function (Builder $query) use ($value) {
            $query->where('price', $value);
        })->when($value = $request->get('active',false), function (Builder $query) use ($value) {
            $query->where('active', $value!=2);
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
