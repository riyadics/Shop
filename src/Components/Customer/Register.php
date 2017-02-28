<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer;

use Antvel\Antvel;
use Illuminate\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as Validator;
use Antvel\Components\Customer\Mail\Registration;

class Register
{
    /**
     * The Laravel validator component.
     *
     * @var Validator
     */
    protected $validator = null;

    /**
     * The laravel request component.
     *
     * @var Request
     */
    protected $request = null;

    /**
     * The default registration role.
     *
     * @var string
     */
    protected $role = 'person';

    /**
     * The registered user.
     *
     * @var Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user = null;

    /**
     * The user model.
     *
     * @var Illuminate\Contracts\Auth\Authenticatable
     */
    protected $model = null;

    /**
     * Creates a new instance.
     *
     * @param Mailer $mailer
     * @param Validator $validator
     * @return void
     */
    public function __construct(Mailer $mailer, Validator $validator)
    {
        $this->mailer = $mailer;
        $this->validator = $validator;
        $this->model = Antvel::userModel();
    }

    /**
     * Registers a new user in the database.
     *
     * @param  Request $request
     * @return self
     */
    public function register(Request $request): self
    {
        $this->request = $request;

        $this->validator->make(
            $this->request->all(), $this->rules()
        )->validate();

        $this->persist();

        return $this;
    }

    /**
     * Creates a new user in the database.
     *
     * @return self
     */
    protected function persist() : self
    {
        //we fetch the application user model.
        $user = Antvel::userModel();

        //we create the user with the given request.
        $this->user = $user::create([
            'password' => bcrypt($this->request->get('password')),
            'nickname' => $this->request->get('email'),
            'email' => $this->request->get('email'),
            'confirmation_token' => str_random(60),
            'role' => $this->role,
        ]);

        //we fill the given user profile information.
        $this->user->profile()->create([
            'first_name' => $this->request->get('first_name'),
            'last_name'  => $this->request->get('last_name'),
        ]);

        return $this;
    }

    /**
     * Returns the registration rules.
     *
     * @return array
     */
    protected function rules() : array
    {
        return [
            'password' => 'required|min:6',
            'last_name' => 'required|max:20|min:3',
            'first_name' => 'required|max:20|min:3',
            'email' => 'required|email|max:255|unique:users',
        ];
    }

    /**
     * Send the registration email.
     *
     * @param  array  $template
     * @return self
     */
    public function withRegistrationEmail(array $template = []) : self
    {
        $this->mailer->send(
            new Registration($this->user, $template)
        );

        return $this;
    }

    public function validateConfirmation($token, $email)
    {
        $this->user = $this->model::where([
            'confirmation_token' => $token,
            'verified' => 'no',
            'email' => $email,
        ])->first();

        abort_if(! $this->user, 404);

        return $this;
    }

    public function activateUser()
    {
        $this->user->verified = 'yes';
        $this->user->save();

        return $this->user;
    }
}