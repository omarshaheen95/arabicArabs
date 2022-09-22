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

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function getGradeNameAttribute()
    {
        switch($this->grade_number)
        {
            case 1:
                return "الأول";
            case 2:
                return "الثاني";
            case 3:
                return "الثالث";
            case 4:
                return "الرابع";
            case 5:
                return "الخامس";
            case 6:
                return "السادس";
            case 7:
                return "السابع";
            case 8:
                return "الثامن";
            case 9:
                return "التاسع";
            case 10:
                return "العاشر";
            case 11:
                return "الحادي عشر";
            case 12:
                return "الثاني عشر";
            default:
                return '';
        }
    }
}
