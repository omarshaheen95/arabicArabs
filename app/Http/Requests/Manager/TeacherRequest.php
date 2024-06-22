<?php

namespace App\Http\Requests\Manager;

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
        if (Route::currentRouteName() == 'manager.teacher.edit' || Route::currentRouteName() == 'manager.teacher.update')
        {
            $id = $this->route('teacher');
            $rules["email"] = ['required', 'email', "unique:teachers,email,$id,id,deleted_at,NULL"];
            $rules["password"] = 'nullable|min:6';
        }else{

            $rules["email"] = 'required|email|unique:teachers,email,{$id},id,deleted_at,NULL';
            $rules["password"] = 'required|min:6';
        }
        $rules["active_to"] = 'required|date_format:Y-m-d';
        $rules["mobile"] = 'required';
        $rules["school_id"] = 'required|exists:schools,id';
        return $rules;
    }
}
