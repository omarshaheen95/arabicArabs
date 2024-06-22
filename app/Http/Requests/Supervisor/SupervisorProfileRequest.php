<?php

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SupervisorProfileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'image' => 'nullable',
            'name' => 'required',
        ];
        $user = Auth::guard('supervisor')->user()->id;
        $rules['email'] = "required|email|unique:supervisors,email,$user,id,deleted_at,NULL";
        return $rules;
    }
}
