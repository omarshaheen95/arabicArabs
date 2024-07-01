<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes,LogsActivityTrait;
    //type 'text', 'bool'
    protected $fillable = [
        'name', 'key', 'value', 'type',
    ];
}
