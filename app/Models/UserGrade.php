<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGrade extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'grade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
