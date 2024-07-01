<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupervisorTeacher extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'supervisor_id', 'teacher_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
