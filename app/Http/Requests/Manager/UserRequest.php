<?php

namespace App\Http\Requests\Manager;

use App\Models\User;
use App\Rules\UniqueMobilePhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
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
        if (Route::currentRouteName() == 'manager.user.edit' || Route::currentRouteName() == 'manager.user.update')
        {
            $id = $this->route('user');
            return [
                'name' => 'required',
                'email' => "required|email:rfc,dns|unique:users,email,$id,id,deleted_at,NULL",
                'password' => 'nullable|min:6',
                'image' => 'nullable|image',
                'school_id' => 'required|exists:schools,id',
                'teacher_id' => 'nullable|exists:teachers,id',
                'grade_id' => 'required',
                'alternate_grade_id' => 'nullable',
                'package_id' => 'required|exists:packages,id',
//                'type' => 'required|in:trial,member',
                'active_to' => 'required|date_format:Y-m-d',
                'year_learning' => 'required',
                'country_code' => 'required',
                'short_country' => 'required',
                'section' => 'nullable',
                "phone" => ['required'],
                'mobile' => ['required', 'phone:'.request()->get('short_country')],
            ];
        }else{
            return [
                'name' => 'required',
                'email' => 'required|email:rfc,dns|unique:users,email,{$id},id,deleted_at,NULL',
                'password' => 'required|min:6',
                'image' => 'nullable|image',
                'school_id' => 'required|exists:schools,id',
                'teacher_id' => 'nullable|exists:teachers,id',
                'grade_id' => 'required',
                'alternate_grade_id' => 'nullable',
                'package_id' => 'required|exists:packages,id',
//                'type' => 'required|in:trial,member',
                'active_to' => 'required|date_format:Y-m-d',
                'year_learning' => 'required',
                'country_code' => 'required',
                'short_country' => 'required',
                'section' => 'nullable',
                "phone" => ['required'],
                'mobile' => ['required', 'phone:'.request()->get('short_country')],
            ];
        }

    }
}
