<?php

namespace App\Http\Requests\Manager;

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
        $rules = [];
        $rules["name"] = 'required';
        $rules["school_id"] = 'required|exists:schools,id';
        $rules["teachers"] = 'nullable|array';
        if (Route::currentRouteName() == 'manager.supervisor.edit' || Route::currentRouteName() == 'manager.supervisor.update')
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
