<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class ImportFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|in:User,Teacher',
            'school_id' => 'required|exists:schools,id',
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
            'process_type' => 'required_if:type,User|in:create,update,delete',
            'package_id' => 'required_if:type,User',
            'active_to' => 'required',
            'back_grade' => 'required_if:type,User',
            'last_of_email' => 'required',
            'year_id' =>'required_if:type,User|exists:years,id'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
