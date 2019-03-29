<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class companies
 * @package App\Models
 * @version December 27, 2018, 10:28 am UTC
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
 * @property string status
 * @property string apikey
 */
class companies extends Model
{
    use SoftDeletes;

    public $table = 'companies';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'address',
        'email',
        'phno',
        'bill_address',
        'card_stripe',
        'logo',
        'planid',
        'domain_name',
        'folder',
        'activated',
        'valid',
        'status',
        'apikey'
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
        'bill_address' => 'string',
        'card_stripe' => 'string',
        'logo' => 'string',
        'planid' => 'string',
        'domain_name' => 'string',
        'folder' => 'string',
        'activated' => 'string',
        'valid' => 'string',
        'status' => 'string',
        'apikey' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
