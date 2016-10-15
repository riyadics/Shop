<?php

namespace Antvel\AddressBook;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line1', 'line2', 'phone',
        'country', 'state', 'user_id',
        'name_contact', 'zipcode', 'city'
    ];

    protected $hidden = ['id'];
}
