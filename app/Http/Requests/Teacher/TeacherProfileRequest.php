<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TeacherProfileRequest extends FormRequest
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
            'name' => 'required',
            'mobile' => 'required',
        ];
        $user = Auth::guard('teacher')->user()->id;
        $rules['email'] = "required|email|unique:teachers,email,$user,id,deleted_at,NULL";
        return $rules;
    }



}
