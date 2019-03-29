<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'affiliate_id',
        'name',
        'fname',
        'lname',
        'email',
        'phone',
        'photo',
        'password',
        'activated',
        'status',
        'link_disabled',
        'special_user',
        'samy_bot',
        'samy_affiliate',
        'samy_linkedin',
        'activation_hash',
        'current_revenue',
        'remember_token',
        'paypal_email',
        'samy_linkedin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
