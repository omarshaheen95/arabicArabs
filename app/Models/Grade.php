<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'grade_number', 'reading', 'writing', 'listening', 'speaking', 'grammar', 'active', 'ordered',
    ];
}
