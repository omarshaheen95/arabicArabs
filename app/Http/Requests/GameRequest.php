<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
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
        $rules = [];
        foreach (config('translatable.locales') as $local)
        {
            $rules["name:$local"] = 'required';
        }
        $rules["level_id"] = 'required|exists:levels,id';
        $rules["lesson_id"] = 'required|exists:lessons,id';
        $rules["game"] = 'required';
        $rules["grade"] = 'required';
        return $rules;
    }
}
