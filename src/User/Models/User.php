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
        'first_name', 'last_name',
        'facebook', 'mobile_phone', 'work_phone', 'description',
        'pic_url', 'language', 'website', 'twitter',
        'nickname', 'email', 'password', 'role',
        'disabled_at', 'confirmation_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        return in_array($this->attributes['role'], $role);
    }

    public function isAdmin()
    {
        return $this->attributes['role'] == 'admin';
    }
}
