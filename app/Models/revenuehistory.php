<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class revenuehistory
 * @package App\Models
 * @version December 10, 2018, 6:08 am UTC
 *
 * @property string company_id
 * @property string month
 * @property string year
 * @property string amount
 */
class revenuehistory extends Model
{
    use SoftDeletes;

    public $table = 'revenuehistories';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
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
        'company_id' => 'string',
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
