<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Company\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //Profile information
        'name', 'description', 'email', 'logo', 'slogan', 'theme', 'status',

        //Contact information
        'contact_email', 'sales_email', 'support_email', 'phone_number', 'cell_phone',
        'address', 'state', 'city', 'zip_code',

        //Social information
        'website', 'twitter', 'facebook', 'facebook_app_id', 'google_plus',
        'google_maps_key_api',

        //SEO information
        'keywords',

        //CMS information
        'about_us', 'refund_policy', 'privacy_policy', 'terms_of_service'
    ];
}
