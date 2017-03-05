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
     * The allowed references.
     * Here, we defined the allowed forms to requesting a profile update.
     *
     * @var array
     */
    protected $allowedReferral = ['profile', 'social', 'account'];

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
    public function authorize() : bool
    {
        return $this->customer->isLoggedIn() && $this->isAllowed();
    }

    /**
     * Checks whether the referral form is allowed to make the incoming request.
     *
     * @return bool
     */
    protected function isAllowed() : bool
    {
        return in_array($this->request->get('referral'), $this->allowedReferral);
    }

    /**
     * Resolves the validation rules for a given referral form.
     *
     * @return array
     */
    public function rules() : array
    {
        $referral = mb_strtolower($this->request->get('referral') ?? 'profile');

        $resolver = 'rulesFor' . ucfirst($referral);

        return $this->$resolver();
    }

    /**
     * Returns validation rules for the form profile.
     *
     * @return array
     */
    protected function rulesForProfile() : array
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
        ];
    }

    /**
     * Returns validation rules for the form social information.
     *
     * @return array
     */
    protected function rulesForSocial() : array
    {
        return [
            //
        ];
    }

    /**
     * Returns validation rules for the form account.
     *
     * @return array
     */
    protected function rulesForAccount() : array
    {
        return [
            'old_password'  => 'required_with:password,password_confirmation',
            'password'  => 'required_with:old_password,password_confirmation|confirmed|different:old_password',
            'password_confirmation' => 'required_with:old_password,password|different:old_password|same:password',
        ];
    }
}
