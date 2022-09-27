<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'writing_answer' => 'required_if:status,corrected',
            'attach_writing_answer' => 'nullable|image',
            'writing_mark' => 'required_if:status,corrected|integer|min:0|max:10',
            'reading_answer' => 'nullable',
            'reading_mark' => 'required_if:status,corrected|integer|min:0|max:10',
            'status' => 'required|in:corrected,returned',
//            'teacher_message' => 'required_if:status,returned',
        ];
    }

    public function messages()
    {
        return [
            'status.in' => t('Correction status must be selected'),
            'status.required' => t('Correction status required'),
            'writing_answer.required_if' => t('writing answer required'),
            'attach_writing_answer.image' => t('attach writing answer must be image'),
            'writing_mark.required_if' => t('writing mark required'),
            'writing_mark.min' => t('writing mark must be more than or equal 0'),
            'writing_mark.max' => t('writing mark must be less than or equal 10'),
            'reading_mark.required_if' => t('reading mark required'),
            'reading_mark.min' => t('reading mark must be more than or equal 0'),
            'reading_mark.max' => t('reading mark must be less than or equal 10'),
            'teacher_message.required_if' => t('teacher message required if status returned'),
        ];
    }
}
