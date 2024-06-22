<?php

namespace App\Http\Requests\Manager;

use App\Rules\UniqueEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class SchoolRequest extends FormRequest
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
            'logo' => 'nullable',
            'mobile' => 'required',
            'website' => 'nullable',
        ];
        if (Route::currentRouteName() == 'manager.school.edit' || Route::currentRouteName() == 'manager.school.update')
        {
            $id = $this->route('school');
            $rules["email"] = "required|email:rfc,dns|unique:schools,email,$id,id,deleted_at,NULL";
            $rules["password"] = 'nullable|min:6';
        }else{

            $rules["email"] = 'required|email:rfc,dns|unique:schools,email,{$id},id,deleted_at,NULL';
            $rules["password"] = 'required|min:6';
        }

        return $rules;
    }

}
