<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class rank
 * @package App\Models
 * @version December 10, 2018, 6:06 am UTC
 *
 * @property string company_id
 * @property string name
 * @property string image
 * @property string revenue_trigger
 * @property string payout_amount
 */
class rank extends Model
{
    use SoftDeletes;

    public $table = 'ranks';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'name',
        'image',
        'revenue_trigger',
        'payout_amount',
        'rank',
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
        'payout_amount' => 'string',
        'rank' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'company_id' => 'required',
        'name' => 'required',
        'revenue_trigger' => 'required',
        'payout_amount' => 'required',
    ];

    
}
