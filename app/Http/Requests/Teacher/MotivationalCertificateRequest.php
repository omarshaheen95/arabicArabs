<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class MotivationalCertificateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'section' => 'nullable|array',
            'students' => 'required|array',
            //exists in users table or value is all
            'students.*' => 'required|exists:users,id',
            //skip validation if student.0 is all
            'students.0' => 'required_if:students,all,0',
            'model_type' => 'required',
            'grade' => 'required_if:model_type,story',
            'lesson_id' => 'required_if:model_type,lesson|array',
            'lesson_id.*' => 'required_if:model_type,lesson|exists:lessons,id',
            'story_id' => 'required_if:model_type,story|array',
            'story_id.*' => 'required_if:model_type,story|exists:stories,id',
            'grade_id' => 'required', //The same grade for lesson and student
            'granted_in' => 'required|date',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
