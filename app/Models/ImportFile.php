<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportFile extends Model
{
    use SoftDeletes,LogsActivityTrait;
    //Status : New,Uploading,Completed,Failures,Errors
    //Process Type : create,update,delete
    //Model Type : User,Teacher
    protected $fillable = [
        'school_id', 'original_file_name', 'file_name', 'created_rows_count', 'updated_rows_count','deleted_rows_count', 'failed_rows_count',
        'file_path', 'status', 'delete_with_rows', 'error', 'failures', 'process_type', 'model_type', 'other_data'
    ];

    protected $casts = [
        'error' => 'array', 'failures' => 'array', 'other_data' => 'array'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function scopeFilter(Builder $query, Request $request)
    {
        return $query
            ->when($name = $request->get('name', false), function (Builder $query) use ($name) {
                $query->where(function (Builder $query) use ($name) {
                    $query->where(DB::raw('LOWER(original_file_name)'), 'like', '%' . $name . '%');
                });
            })->when($school_id = $request->get('school_id', false), function (Builder $query) use ($school_id) {
                $query->where('school_id', $school_id);
            })->when($status = $request->get('status', false), function (Builder $query) use ($status) {
                $query->where('status', $status);
            })->when($process_type = $request->get('process_type', false), function (Builder $query) use ($process_type) {
                $query->where('process_type', $process_type);
            })->when($model_type = $request->get('model_type', false), function (Builder $query) use ($model_type) {
                $query->where('model_type', $model_type);
            });
    }

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if($this->created_rows_count > 0)
        {
            $actions[] = ['key' => 'excel', 'name' => t('Excel'), 'route' => '#', 'permission' => 'import files', 'onclick' => "excelExport('".route('manager.import_files.export_excel', ['import_file_id' => $this->id])."')"];
        }
        if($this->created_rows_count > 0 && $this->model_type == "User")
        {
            $actions[] = ['key' => 'cards', 'name' => t('Cards'), 'route' => route('manager.import_files.users_cards', ['id' => $this->id]), 'permission' => 'import files'];
        }
        if ($this->status == 'Failures' || $this->status == 'Errors') {
            $actions [] =
                ['key' => 'show', 'name' => t('Show Errors'), 'route' => route('manager.import_files.show', [$this->id]), 'permission' => 'import files'];
        }

        $actions[] = ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete import files'];
        return view('general.action_menu')->with('actions',$actions);
    }



}
