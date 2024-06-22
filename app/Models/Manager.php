<?php

namespace App\Models;

use App\Notifications\ManagerResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Manager extends Authenticatable
{
    use Notifiable, SoftDeletes,HasRoles, LogsActivity;


    protected $fillable = [
        'name', 'email', 'password', 'active','lang','last_login','last_login_info'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected static $recordEvents = ['created', 'updated','deleted'];
    protected static $logAttributes = ['name', 'email', 'password', 'active'];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;
    public function scopeFilter(Builder $query,$request = null)
    {
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('id',false),function (Builder $query) use ($value){
            $query->where('id',$value);
        })->when($value= $request->get('email',false),function (Builder $query) use ($value){
            $query->where('email',$value);
        })->when($value= $request->get('name',false),function (Builder $query) use ($value){
            $query->where('name','LIKE','%'.$value.'%');
        })->when($value=$request->get('active',false) ,function (Builder $query) use ($value){
            $query->where('active', $value!=2);
        })->when($value = $request->get('row_id',[]),function (Builder $query) use ($value){
            $query->whereIn('id', $value);
        });
    }
    public function getActionButtonsAttribute()
    {
        $actions =  [
            ['key'=>'edit','name'=>t('Edit'),'route'=>route('manager.manager.edit', $this->id),'permission'=>'edit managers'],
            ['key'=>'edit_permissions','name'=>t('Edit Permissions'),'route'=>route('manager.edit-permissions', $this->id),'permission'=>'edit managers permissions'],
            ['key'=>'track_logs','name'=>t('Track Activity'),'route'=>route('manager.activity-log.index', ['causedByManager'=>$this->id]),'permission'=>'show activity logs'],
            ['key'=>'delete','name'=>t('Delete'),'route'=>$this->id,'permission'=>'delete managers'],
        ];
        return view('general.action_menu')->with('actions',$actions);

    }
    public function login_sessions()
    {
        return $this->morphMany(LoginSession::class, 'model');
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ManagerResetPassword($token));
    }
}
