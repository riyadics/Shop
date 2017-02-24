<?php

namespace Antvel\Components\Customer\Requests;

use Antvel\Http\Request;

class ProfileRequest extends Request
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
            'email' => 'required|email|unique:users,email,',
            'nickname' => 'required|max:255|unique:users,nickname,',
            'old_password'  => 'required_with:password,password_confirmation',
            'password'  => 'required_with:old_password,password_confirmation|confirmed|different:old_password',
            'password_confirmation' => 'required_with:old_password,password|different:old_password|same:password',
          ];
    }
}
