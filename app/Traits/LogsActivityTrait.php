<?php
namespace App\Traits;


use Spatie\Activitylog\Traits\LogsActivity;

trait LogsActivityTrait
{
    use LogsActivity;

    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore   = ['created_at','updated_at','last_login','last_login_info'];

    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;
//    protected static $recordEvents = ['deleted'];

}
