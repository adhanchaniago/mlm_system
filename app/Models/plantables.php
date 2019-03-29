<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class plantables
 * @package App\Models
 * @version December 27, 2018, 10:40 am UTC
 *
 * @property string name
 * @property string amount
 * @property string term
 * @property string sharing_amount
 * @property string image
 */
class plantables extends Model
{
    use SoftDeletes;

    public $table = 'plantables';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'amount',
        'term',
        'sharing_amount',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'amount' => 'string',
        'term' => 'string',
        'sharing_amount' => 'string',
        'image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    
}
