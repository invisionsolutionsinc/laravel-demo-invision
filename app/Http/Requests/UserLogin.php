<?php

namespace App\Http\Requests;


class UserLogin extends BaseRequest
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
        return [
            // 'email' => 'required|email|exists:users',
            'email' => 'required|email',
            'password' => 'required',
            'device_token'=>'sometimes|required',
            'device_type'=>'sometimes|required_with:device_token',
        ];
    }
}
