<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'name', 'grade_number', 'reading', 'writing', 'listening', 'speaking', 'grammar', 'dictation', 'rhetoric', 'active', 'ordered',
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->orderBy('ordered');
        });
    }


        public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function getGradeNameAttribute()
    {
        switch($this->grade_number)
        {
            case 0:
                return "الصف التمهيدي = Year 1";
            case 1:
                return "الأول = Year 2";
            case 2:
                return "الثاني = Year 3";
            case 3:
                return "الثالث = Year 4";
            case 4:
                return "الرابع = Year 5";
            case 5:
                return "الخامس = Year 6";
            case 6:
                return "السادس = Year 7";
            case 7:
                return "السابع = Year 8";
            case 8:
                return "الثامن = Year 9";
            case 9:
                return "التاسع = Year 10";
            case 10:
                return "العاشر = Year 11";
            case 11:
                return "الحادي عشر = Year 12";
            case 12:
                return "الثاني عشر = Year 13";
            default:
                return '';
        }
    }

    public function question_count($skill, $type)
    {
        if (in_array($skill, ['reading', 'writing', 'listening', 'speaking']))
        {
            return $this->{$type};
        }else{
            if ($this->grade <= 3 )
            {

            }else{

            }
        }
    }
}
