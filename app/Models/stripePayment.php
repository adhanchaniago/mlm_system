<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class stripePayment
 * @package App\Models
 * @version December 27, 2018, 10:48 am UTC
 *
 * @property string payment_id
 * @property string user_id
 * @property string card_number
 * @property string amount
 * @property string date
 * @property string name
 * @property string email
 * @property string phone
 * @property string address
 */
class stripePayment extends Model
{
    use SoftDeletes;

    public $table = 'stripe_payments';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'payment_id',
        'user_id',
        'card_number',
        'amount',
        'date',
        'name',
        'email',
        'phone',
        'address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'payment_id' => 'string',
        'user_id' => 'string',
        'card_number' => 'string',
        'amount' => 'string',
        'date' => 'string',
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
