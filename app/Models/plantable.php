<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class plantable
 * @package App\Models
 * @version December 10, 2018, 6:14 am UTC
 *
 * @property string name
 * @property string amount
 * @property string term
 * @property string sharing_amount
 * @property string image
 */
class plantable extends Model
{
    use SoftDeletes;

    public $table = 'plantables';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'amount',
        'term',
        'sharing_amount',
        'type',
        'levels',
        'affiliates',
        'commission',
        'image',
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
        'image' => 'string',
        'type' => 'string',
        'levels' => 'string',
        'affiliates' => 'string',
        'commission' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'amount' => 'required',
        'term' => 'required',
        'type' => 'required',
        'levels' => 'required',
        'affiliates' => 'required',
        'commission' => 'required',
    ];

    
}
