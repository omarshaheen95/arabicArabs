<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class SupervisorRequest extends FormRequest
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
            'name'=>'required',
            'teachers'=>'nullable|array',
            'image'=>'nullable'
        ];

        if (Route::currentRouteName() == 'school.supervisor.edit' || Route::currentRouteName() == 'school.supervisor.update')
        {
            $id = $this->route('supervisor');
            $rules["email"] = ['required', 'email', "unique:supervisors,email,$id,id,deleted_at,NULL"];
            $rules["password"] = 'nullable|min:6';
        }else{
            $rules["email"] = 'required|email|unique:supervisors,email,{$id},id,deleted_at,NULL';
            $rules["password"] = 'required|min:6';
        }
        return $rules;
    }
}
