<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportStudentFile extends Model
{
    use SoftDeletes;
    //Status : New,Uploading,Completed,Failures,Errors
    protected $fillable = [
        'school_id', 'original_file_name', 'file_name', 'created_rows_count', 'updated_rows_count', 'failed_rows_count', 'file_path',
        'status', 'delete_with_user', 'with_abt_id', 'error', 'failures'
    ];

    protected $casts = [
        'error' => 'array', 'failures' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function getActionButtonsAttribute()
    {
        $button = '';
        if ($this->status == 'Failures' || $this->status == 'Errors') {
            $button .= '<a href="' . route('manager.import_users_files.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
        }
        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button> ';
        return $button;
    }
}
