<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    //page_type	enum('web', 'mobile')
    protected $fillable = [
        'key', 'page_type', 'name', 'content'
    ];

}
