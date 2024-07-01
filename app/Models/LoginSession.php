<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class LoginSession extends Model
{
    use LogsActivityTrait;

    protected $fillable = ['model_id','model_type','data'];
    protected static $recordEvents = ['deleted'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeFilter(Builder $query, $request =null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('model_id'), function (Builder $query)use ($value) {
            $query->where('model_id',$value) ;

        })->when($value = $request->get('name'), function (Builder $query)use ($value) {
            $query->whereHasMorph('model',[Manager::class,School::class,User::class,Supervisor::class],function ($query) use ($value){
                $query->where('name','LIKE','%'.$value.'%');
            }) ;
        })->when($value = $request->get('email'), function (Builder $query)use ($value) {
            $query->whereHasMorph('model',[Manager::class,School::class,User::class,Supervisor::class],function ($query) use ($value){
                $query->where('email',$value);
            }) ;
        })->when($value = $request->get('model_type'), function (Builder $query)use ($value) {
            if ($value == 'Manager'){
                $query->where('model_type','=',Manager::class) ;
            }elseif ($value=='Student'){
                $query->where('model_type','=',User::class) ;
            }elseif ($value=='School'){
                $query->where('model_type','=',School::class) ;
            }elseif ($value=='Inspection'){
                $query->where('model_type','=',Supervisor::class) ;
            }
        })->when($value= $request->get('start_date',false),function (Builder $query) use ($value){
            $query->whereDate('created_at', '>=',$value);
        })->when($value= $request->get('end_date',false),function (Builder $query) use ($value){
            $query->whereDate('created_at', '<=',$value);
        });
    }
}
