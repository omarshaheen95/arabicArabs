<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LessonRequest extends FormRequest
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
        if (in_array(Route::currentRouteName(), ['manager.news.store', 'manager.news.create'])) {
            return [
                'name' => 'required',
                'grade_id' => 'required',
                'lesson_type' => 'required',
                'section_type' => 'nullable',//'required_if:lesson_type,reading,listening',
                'ordered' => 'required',
                'color' => 'nullable',
                'image' => 'required|image',

            ];
        } else {
            return [
                'name' => 'required',
                'grade_id' => 'required',
                'lesson_type' => 'required',
                'section_type' => 'nullable',//'required_if:lesson_type,reading,listening',
                'ordered' => 'required',
                'color' => 'nullable',
                'image' => 'nullable|image',

            ];
        }
    }
}
