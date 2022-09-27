<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UserStoryAssignmentRequest extends FormRequest
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
            'assignment_grade' => 'required',
            'assignment_story' => 'required',
            'section' => 'nullable',
            'assignment_students' => 'required_if:section,nullable',
        ];
    }

    public function messages()
    {
        return [
            'assignment_grade.required' => ''.t("assignment grade required").'',
            'assignment_story.required' => ''.t("assignment story required").'',
            'assignment_students.required' => ''.t("assignment students required").'',
        ]; // TODO: Change the autogenerated stub
    }

}
