<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class company
 * @package App\Models
 * @version December 10, 2018, 5:44 am UTC
 *
 * @property string name
 * @property string address
 * @property string email
 * @property string phno
 * @property string bill_address
 * @property string card_stripe
 * @property string logo
 * @property string planid
 * @property string domain_name
 * @property string folder
 * @property string activated
 * @property string valid
 * @property string apikey
 */
class company extends Model
{
    use SoftDeletes;

    public $table = 'companies';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'address',
        'email',
        'phno',
        'address2',
        'logo',
        'domain_name',
        'stripe_id',
        'apikey',
        'city',
        'state',
        'zip',
        'country',
        'fname',
        'lname',
        'actual_domain',
        'affiliate_disabled',
        'bot_disabled',
        'link_disabled',
        'cookie_duration',
        'bot_transaction_id',
        'affiliate_disabled_reason',
        'invitee',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'address' => 'string',
        'email' => 'string',
        'phno' => 'string',
        'address2' => 'string',
        'logo' => 'string',
        'domain_name' => 'string',
        'stripe_id' => 'string',
        'apikey' => 'string',
        'city' => 'string',
        'state' => 'string',
        'zip' => 'string',
        'country' => 'string',
        'fname' => 'string',
        'lname' => 'string',
        'actual_domain' => 'string',
        'affiliate_disabled' => 'string',
        'bot_disabled' => 'string',
        'link_disabled' => 'string',
        'cookie_duration' => 'string',
        'bot_transaction_id' => 'string',
        'invitee' => 'string',
        'affiliate_disabled_reason' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    
}
