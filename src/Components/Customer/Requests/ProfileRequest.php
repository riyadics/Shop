<?php

namespace Antvel\Components\Customer\Requests;

use Antvel\Http\Request;
use Illuminate\Validation\Rule;
use Antvel\Components\Customer\CustomersRepository;

class ProfileRequest extends Request
{
    /**
     * The customer repository.
     *
     * @var CustomersRepository
     */
    protected $customer = null;

    /**
     * Creates a new instance.
     *
     * @param CustomersRepository $customer
     * @return void
     */
    public function __construct(CustomersRepository $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->customer->isLoggedIn();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->customer->id),
            ],
            'nickname' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($this->customer->nickname, 'nickname'),
            ],
            'old_password'  => 'required_with:password,password_confirmation',
            'password'  => 'required_with:old_password,password_confirmation|confirmed|different:old_password',
            'password_confirmation' => 'required_with:old_password,password|different:old_password|same:password',
          ];
    }
}
