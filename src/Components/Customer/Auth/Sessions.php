<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer\Auth;

use Illuminate\Http\Request;
use Illuminate\Container\Container;

class Sessions
{
	/**
	 * The laravel auth component.
	 *
	 * @var Illuminate\Contracts\Auth\Factory
	 */
	protected $auth = null;

	/**
	 * The laravel session component.
	 *
	 * @var Illuminate\Session\Store
	 */
	protected $session = null;

	/**
	 * The Laravel validator component.
	 *
	 * @var Illuminate\Validation\Factory
	 */
	protected $validator = null;

	/**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Failure message.
     *
     * @var string
     */
    protected $failedMessage = 'The credentials do not match our records';

    /**
     * Creates a new instance.
     *
     * @return void
     */
	public function __construct()
    {
    	$this->auth = Container::getInstance()->make('auth');
    	$this->session = Container::getInstance()->make('session');
     	$this->validator = Container::getInstance()->make('validator');
    }

    /**
     * Sets the path destination after user is logged in.
     *
     * @param  string $path
     * @return self
     */
    public function withRedirectTo(string $path) : self
    {
    	$this->redirectTo = $path;

    	return $this;
    }

    /**
     * Sets a failure message.
     *
     * @param  string $message
     * @return self
     */
    public function withFailedMessage(string $message) : self
    {
    	$this->failedMessage = $message;

    	return $this;
    }

    /**
     * Authenticates an user.
     *
     * @param  Illuminate\Http\Request $request
     * @return void
     */
    public function authenticate(Request $request)
    {
    	$this->request = $request;

        //If user said he was not registered, we flash his email address
        //in order for it to be available in the registration view.
		if ($this->request->input('newuser')) {
			$this->session->flash('email', $this->request->input('email'));
			return redirect('/register');
		}

		$this->validator->make(
    		$this->request->all(), $this->rules()
    	)->validate();

		return $this->attempt();
    }

    /**
     * Attempts to log a user in the application.
     *
     * @param  Illuminate\Http\Request $request
     * @return void
     */
    protected function attempt()
    {
    	$attempt = $this->auth->attempt(
    		$this->credentials(), $this->request->has('remember')
    	);

        if ($attempt) {
            return redirect($this->redirectTo);
        }

        return redirect('/login')
            ->withInput($this->request->only('email', 'remember'))
            ->withErrors([
                'email' => $this->failedMessage,
            ]);
    }

    /**
     * The login form validation rules.
     *
     * @return array
     */
    protected function rules() : array
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        if (! env('APP_DEBUG')) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }

        return $rules;
    }

    /**
     * Returns the user credentials.
     *
     * @return array
     */
    protected function credentials() : array
    {
        return [
            'email' => $this->request->email,
            'password' => $this->request->password
        ];
    }
}