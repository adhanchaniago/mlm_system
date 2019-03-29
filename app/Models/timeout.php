<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class timeout
 * @package App\Models
 * @version December 27, 2018, 10:50 am UTC
 *
 * @property string days
 */
class timeout extends Model
{
    use SoftDeletes;

    public $table = 'timeouts';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'days'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'days' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
