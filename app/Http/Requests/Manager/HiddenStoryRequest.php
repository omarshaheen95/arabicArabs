<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class HiddenStoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'story_id.*' => ['required', 'exists:stories,id'],
            'grade' => ['required', 'numeric', 'in:15,1,2,3,4,5,6,7,8,9'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
