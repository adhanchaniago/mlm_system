<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class payouthistories
 * @package App\Models
 * @version December 27, 2018, 10:36 am UTC
 *
 * @property string affiliate_id
 * @property string month
 * @property string year
 * @property string amount
 */
class payouthistories extends Model
{
    use SoftDeletes;

    public $table = 'payouthistories';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'affiliate_id',
        'month',
        'year',
        'amount'
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
        'amount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
