<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator) {
        //throw new HttpResponseException(response()->json(['success'=>false,'message'=>(array)Helper::expandDotNotationKeys((array)$validator->errors()->messages())], 422));
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'message'=>$validator->errors()->all(),
            'status_code'=>422,
            'data'=> []
        ],422));
    }
}
