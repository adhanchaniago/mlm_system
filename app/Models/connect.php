<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class connect
 * @package App\Models
 * @version January 14, 2019, 9:53 am UTC
 *
 * @property integer user_id
 * @property integer company_id
 */
class connect extends Model
{
    use SoftDeletes;

    public $table = 'connects';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'company_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'company_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'company_id' => 'required'
    ];

    
}
