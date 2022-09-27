<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class TeacherRequest extends FormRequest
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
        $rules["name"] = 'required';
        if (Route::currentRouteName() == 'school.teacher.edit' || Route::currentRouteName() == 'school.teacher.update')
        {
            $id = $this->route('teacher');
            $rules["email"] = ['required', 'email', "unique:teachers,email,$id,id,deleted_at,NULL"];
            $rules["password"] = 'nullable|min:6';
        }else{

            $rules["email"] = 'required|email|unique:teachers,email,{$id},id,deleted_at,NULL';
            $rules["password"] = 'required|min:6';
        }
        $rules["mobile"] = 'required';
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => t('Name required'),
            'email.required' => t('Email required'),
            'email.email' => t('Email is wrong'),
            'email.unique' => t('Email is already exists'),
            'password.required' => t('Password required'),
            'password.min' => t('Password must be 6 chart at least'),
            'mobile.required' => t('Mobile required'),
        ]; // TODO: Change the autogenerated stub
    }
}
