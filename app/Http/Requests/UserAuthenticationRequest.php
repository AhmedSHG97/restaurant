<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;


class UserAuthenticationRequest extends FormRequest
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
        if ($this->path() == "api/login") {
            return [
                "username" => "required|min:3|max:191|exists:users,username",
                "password" => [
                    'required',
                    'min:6',
                    "max:191",
                ],
            ];
        }
        if ($this->path() == "api/register") {
            return [
                "email" => "required|email|unique:users,email|min:8|max:191",
                "password" => [
                    'required',
                    'min:6',
                    "max:191",
                    'confirmed'
                ],
                "username" => "required|min:4|max:191|alpha_dash|unique:users,username",
                "phone_number" => "required|string|min:10|max:20",
                "date_of_birth" => "required|date_format:Y-m-d|before:today"
            ];
        }
        if ($this->path() == "api/verify") {
            return [
                "verification_code" => "required|string|min:4|max:4",
                "user_id" => "required|integer|min:1|exists:users,id",
            ];
        }
        if ($this->path() == "api/resendVerification") {
            return [
                "email" => "required|email|exists:users,email",
            ];
        }

        if ($this->path() == "api/reset/password") {
            return [
                "email" => "required|email|min:8|max:191|exists:users,email",
            ];
        }
        if ($this->path() == "api/change/password") {
            return [
                "otp" => "required|min:3|max:4|exists:password_resets,token",
                "password" => [
                    'required',
                    'min:6',
                    "max:191",
                    'confirmed'
                ],
            ];
        }

        if ($this->path() == "api/password/update") {
            return [
                "password" => [
                    'required',
                    'min:6',
                    "max:191",
                    'confirmed'
                ],
            ];
        }

        if ($this->path() == "api/me/info/update") {
            return [
                "id" => "required|integer|exists:users,id",
                "username" => "required|string|min:3|unique:users,username,".$this->id,
                "phone_number" => "required|string|min:10|max:20",
                "date_of_birth" => "required|date_format:Y-m-d|before:today"
            ];
        }
        if ($this->path() == "api/social/login") {
            return [
                "access_token" => "required|string",
                "provider" => "required|string",
            ];
        }
        if ($this->path() == "api/user/delete") {
            return [
                "id" => "required|integer|exists:users,id",
            ];
        }
        return [];



    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => null
                ],
                400
            )
        );
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'status' => false,
                    'message' => "Error: you are not authorized or do not have the permission",
                    'data' => null
                ],
                401
            )
        );
    }


    public function messages()
    {
        return [
            "username.alpha_dash" => "User name should not contain spaces"
        ];
    }
}
