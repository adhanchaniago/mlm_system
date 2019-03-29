<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ranks
 * @package App\Models
 * @version December 27, 2018, 10:42 am UTC
 *
 * @property string company_id
 * @property string name
 * @property string image
 * @property string revenue_trigger
 * @property string payout_amount
 */
class ranks extends Model
{
    use SoftDeletes;

    public $table = 'ranks';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'name',
        'image',
        'revenue_trigger',
        'payout_amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'string',
        'name' => 'string',
        'image' => 'string',
        'revenue_trigger' => 'string',
        'payout_amount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
