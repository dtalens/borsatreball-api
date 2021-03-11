<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name'     => 'sometimes|required|string',
            'email'    => 'sometimes|required|string|email|unique:users',
            'password' => 'sometimes|required|string|confirmed',
            'rol'      => 'sometimes|required'
        ];
    }
}
