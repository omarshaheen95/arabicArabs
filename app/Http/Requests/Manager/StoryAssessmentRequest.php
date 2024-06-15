<?php

namespace App\Http\Requests\Manager\Lesson;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class StoryAssessmentRequest extends FormRequest
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
        $rules = [
            'type' => 'required',
            't_f_question' => 'required_if:type,1|array',
            't_f_question.*' => 'required_if:type,1',
            't_f' => 'required_if:type,1|array',
            't_f.*' => 'required_if:type,1|in:0,1',

            'c_question' => 'required_if:type,2|array',
            'c_question.*' => 'required_if:type,2',
            'c_q_a' => 'required_if:type,2|array',
            'c_q_a.*' => 'required_if:type,2',
            'c_q_option' => 'required_if:type,2|array',
            'c_q_option.*' => 'required_if:type,2|array',
            'c_q_option.*.*' => 'required_if:type,2',

            'm_question' => 'required_if:type,3|array',
            'm_question.*' => 'required_if:type,3',
            'm_q_option' => 'required_if:type,3|array',
            'm_q_option.*' => 'required_if:type,3',
            'm_q_answer' => 'required_if:type,3|array',
            'm_q_answer.*' => 'required_if:type,3',

            's_question' => 'required_if:type,4|array',
            's_question.*' => 'required_if:type,4',

            'old_s_q_option' => 'nullable|required_if:type,4|array',
            'old_s_q_option.*' => 'nullable|required_if:type,4|array',
            'old_s_q_option.*.*' => 'nullable|required_if:type,4',
        ];
        if (Route::currentRouteName() == 'manager.story.storeAssessment') {
            $rules['s_q_option'] = 'required_if:type,4|array';
            $rules['s_q_option.*'] = 'required_if:type,4|array';
            $rules['s_q_option.*.*'] = 'required_if:type,4';
        }else{
            $rules['s_q_option'] = 'nullable|array';
            $rules['s_q_option.*'] = 'nullable|array';
            $rules['s_q_option.*.*'] = 'nullable';
        }
        return $rules;
    }
}
