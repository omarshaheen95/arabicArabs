<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

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
        $id = $this->route('student');
        return [
            'name' => 'required',
            'email' => "required|email:rfc,dns|unique:users,email,$id,id,deleted_at,NULL",
            'password' => 'nullable|min:6',
            'image' => 'nullable|image',
            'country_code' => 'required',
            'section' => 'nullable',
            'short_country' => 'required',
            'year_id' => 'required',
            "phone" => ['required'],
            'mobile' => ['required', 'phone:'.request()->get('short_country')],
            'teacher_id' => 'nullable|exists:teachers,id',
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
            'year_learning.integer' => t('Years learning must be number'),
        ]; // TODO: Change the autogenerated stub
    }
}
