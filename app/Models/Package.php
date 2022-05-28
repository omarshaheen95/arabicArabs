<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;

class Package extends Model
{
    use SoftDeletes, InteractsWithMedia;
    protected $fillable = [
        'name', 'days', 'active',
    ];
}
