<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class HiddenLessonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'lesson_id.*' => ['required', 'exists:lessons,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
