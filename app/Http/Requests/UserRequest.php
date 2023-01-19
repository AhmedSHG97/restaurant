<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Session;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(($this->path() == 'user/update' || $this->path() == 'user/permissions/update') && (!(userSession()->role == 'admin' || userSession()->id == $this->id))){
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->path() == 'user/update'){
            return [
                "id" => "required|exists:users,id|integer|min:1",
                "name" => "required|min:4|max:191",
                "email" => "required|email|min:8|max:191|unique:users,email," . $this->id,
                "password" => [
                    'nullable',
                    'min:6',
                    "max:191",
                    'confirmed'
                ],
            ];
        }
        return [];
    }
    protected function failedValidation(Validator $validator)
    {
        Session::flash('validation_message', $validator->errors()->first()); 
    }
    public function messages()
    {
        return [
            "email.required" => __('auth.email_required'),
            "email.unique" =>  __("auth.user_already_exists"),
            "email.email" => __("auth.not_valid_email"),
            "email.min" => __("auth.email_min"),
            "email.max" => __("auth.email_max"),
            "email.exists" => __("auth.user_not_exists"),
            "id.exists" =>  __("auth.user_not_exists"),
            "password.required" => __("auth.password_required"),
            "password.confirmed" => __("auth.password_confirmed"),
            "password.min" => __("auth.password_min"),
            "password.max" => __("auth.password_max"),
            "password.regex" => __("auth.password_regex"),
            "name.required" => __("auth.name_required"),
            "name.min" =>  __("auth.name_min"),
            "name.max" =>  __("auth.name_max"),
            "token.required" => __("auth.token_required"),
            "token.exists" => __("auth.token_exists"),
        ];
    }

    protected function failedAuthorization()
    {
        session::flash('is_authorized',0);
    }
}
