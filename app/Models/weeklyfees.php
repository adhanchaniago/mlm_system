<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class weeklyfees
 * @package App\Models
 * @version December 10, 2018, 6:22 am UTC
 *
 * @property string company_id
 * @property string begining_date
 * @property string end_date
 * @property string amount
 */
class weeklyfees extends Model
{
    use SoftDeletes;

    public $table = 'weeklyfees';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'begining_date',
        'end_date',
        'amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'string',
        'begining_date' => 'string',
        'end_date' => 'string',
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
