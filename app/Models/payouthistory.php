<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class payouthistory
 * @package App\Models
 * @version December 10, 2018, 6:10 am UTC
 *
 * @property string affiliate_id
 * @property string month
 * @property string year
 * @property string amount
 */
class payouthistory extends Model
{
    use SoftDeletes;

    public $table = 'payouthistories';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'affiliate_id',
        'amount',
        'company_id',
        'month',
        'year',
        'rankid',
        'paypal_batchid',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'affiliate_id' => 'string',
        'month' => 'string',
        'year' => 'string',
        'amount' => 'string',
        'company_id' => 'string',
        'rankid' => 'string',
        'paypal_batchid' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'affiliate_id' => 'required',
        'amount' => 'required',
        'company_id' => 'required',
    ];

    
}
