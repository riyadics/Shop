<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\User\Models;

use Antvel\Product\Models\Product;
use Antvel\AddressBook\Models\Address;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Antvel\User\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable, Presenters;

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
        //profile information
        'first_name', 'last_name', 'nickname', 'email', 'password', 'role',
        'pic_url', 'language', 'time_zone', 'phone_number', 'gender',
        'birthday', 'rate_val', 'rate_count', 'preferences',
        'verified', 'confirmation_token', 'disabled_at',

        //social information
        'facebook', 'twitter', 'website',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'disabled_at', 'deleted_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * An user has an address book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * An user has many products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * An user has many email change petitions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emailChangePetitions()
    {
        return $this->hasMany(EmailChangePetition::class);
    }

    /**
     * Send the password reset notification mail.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }




    // ======================================= //
    //        temporary while refactoring      //
    // ======================================= //

    public function getCartCount()
    {
        return 0;
    }

     public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->attributes['role'], $role);
        }

        return $this->attributes['role'] == $role;
    }

    public function isAdmin()
    {
        return $this->attributes['role'] == 'admin';
    }
}
