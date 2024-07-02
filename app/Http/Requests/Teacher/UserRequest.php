<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UserRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'name' => 'required',
            'email' => "required|email|unique:users,email,$id,id,deleted_at,NULL,archived,0",
            'password' => 'nullable|min:6',
            'image' => 'nullable|image',
            'section' => 'nullable',
            'mobile' => ['nullable'],
            'nationality' => 'nullable',
            'id_number' => 'nullable',
        ];
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
            'phone.required' => t('Mobile required'),
            'mobile.required' => t('Mobile required'),
            'mobile.phone' => t('Mobile invalid'),
            'country_code.required' => t('country code required'),
            'short_country.required' => t('short country required'),
            'image.image' => t('Image invalid'),
        ]; // TODO: Change the autogenerated stub
    }
}
