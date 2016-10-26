<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Customer\Models;

use Illuminate\Auth\Authenticatable;
use Antvel\AddressBook\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes, Notifiable;

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'work_phone', 'description', 'disabled_at',
        'nickname', 'email', 'password', 'role', 'pic_url',
        'language', 'website', 'twitter', 'facebook', 'mobile_phone'
    ];

    /**
     * Soft delete attributes.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * JSON hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The user profile.
     *
     * @return [type] [description]
     */
    public function profile()
    {
        if (in_array($this->role, ['business', 'nonprofit'])) {
            return $this->hasOne(Business::class);
        }

        return $this->hasOne(Person::class);
    }

    /**
     * The user addressBook.
     *
     * @return Antvel\AddressBook\Models\Address
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
