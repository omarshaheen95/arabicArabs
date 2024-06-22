<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class LessonAssignmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lesson_id' => ['required', 'array'],
            'lesson_id.*' => ['required',  'exists:lessons,id'],
            'section' => ['nullable', 'array'],
            'year_learning' => ['required', 'array'],
            'students' => ['required', 'array', ''],
            'grade_id' => ['required'],
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
