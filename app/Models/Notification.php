<?php

namespace App\Models;


use App\Notifications\ContactUsNotification;
use App\Notifications\NewReservationManagerNotification;
use App\Notifications\NewReservationNotification;
use App\Notifications\AcceptReservationNotification;
use App\Notifications\CompleteReservationNotification;
use App\Notifications\CustomerCancelReservationNotification;
use App\Notifications\NewServiceNotification;
use App\Notifications\PaidReservationNotification;
use App\Notifications\RateReservationNotification;
use App\Notifications\RejectReservationNotification;
use App\Notifications\RejectServiceNotification;
use App\Notifications\SupplierCancelReservationNotification;
use App\Notifications\NewPaymentNotification;
use App\Traits\FormatsDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    public $incrementing = false;
    protected $table    = 'notifications';
    protected $fillable = ['id', 'type', 'notifiable_id', 'notifiable_type', 'data', 'read_at', 'created_at'];

    protected $appends  = ['seen'];
    protected $casts    = ['data' => 'array'];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    public function getSeenAttribute()
    {
        return $this->read_at;
    }

    public function scopeNotSeen($query)
    {
        return $query->whereNull('read_at');
    }


    public function getActionButtonsAttribute()
    {
        $button = '';
        if (Auth::guard('manager')->check())
        {
            $button .= '<a href="'.route('manager.notification.show', $this->id).'" class="btn btn-icon btn-brand "><i class="la la-eye"></i></a> ';
        }else if (Auth::guard('school')->check())
        {
            $button .= '<a href="'.route('school.notification.show', $this->id).'" class="btn btn-icon btn-brand "><i class="la la-eye"></i></a> ';
        }else{
            $button .= '<a href="'.route('teacher.notification.show', $this->id).'" class="btn btn-icon btn-brand "><i class="la la-eye"></i></a> ';
        }
        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
        return $button;
    }

    public function getTitleAttribute()
    {
        return t($this->data["title"]['message']);
//        return t($this->data["title"]['message'],  $this->data["title"]['param']);
    }

    public function getContentAttribute()
    {
        return t($this->data["details"]['message']);
//        return t($this->data["details"]['message'], $this->data["details"]['param']);
    }



}
