<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class ImportStudentFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'package_id' => 'required|exists:packages,id',
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
