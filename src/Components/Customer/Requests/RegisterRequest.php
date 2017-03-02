<?php

namespace Antvel\Components\Customer\Requests;

use Antvel\Http\Request;

class RegisterRequest extends Request
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
    public function rules() : array
    {
        return [
            'password' => 'required|min:6',
            'last_name' => 'required|max:20|min:3',
            'first_name' => 'required|max:20|min:3',
            'email' => 'required|email|max:255|unique:users',
          ];
    }
}
