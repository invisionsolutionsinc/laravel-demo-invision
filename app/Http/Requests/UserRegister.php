<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegister extends BaseRequest
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
            'name' => 'required',
            'email' => 'unique:users|required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'gender' => 'required | in:Male,Female',
            'country' => 'required',
            'talent' => 'sometimes',
            'additional_talent' => 'sometimes',
            'interests' => 'sometimes',
            'volunteering_interest' => 'sometimes | in:0,1',
            'phone_number' => 'required',
        ];
    }
}
