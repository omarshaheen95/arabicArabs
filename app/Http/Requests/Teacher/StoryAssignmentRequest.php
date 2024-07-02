<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoryAssignmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'students_grade' => ['required', ],
            'story_id.*' => ['required',  'exists:stories,id'],
            'section' => ['nullable', 'array'],
            'students' => ['required', 'array',],
            'grade' => ['required'],
            'deadline' => ['nullable', 'after:today'],
            'exclude_students' => ['required', 'in:1,2'],
            'completed_at' => ['nullable', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
