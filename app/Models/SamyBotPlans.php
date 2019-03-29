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
class SamyBotPlans extends Model
{
    use SoftDeletes;

    public $table = 'SamyBotPlans';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'amount_1',
        'amount_5',
        'amount_10',
        'amount_20',
        'plan_id_1',
        'plan_id_5',
        'plan_id_10',
        'plan_id_20',
        'term',
        'ad_feat',
        'image',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'amount_1' => 'string',
        'amount_5' => 'string',
        'amount_10' => 'string',
        'amount_20' => 'string',
        'plan_id_1' => 'string',
        'plan_id_5' => 'string',
        'plan_id_10' => 'string',
        'plan_id_20' => 'string',
        'term' => 'string',
        'ad_feat' => 'string',
        'image' => 'string',
        'status' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'amount_1' => 'required',
        'amount_5' => 'required',
        'amount_10' => 'required',
        'amount_20' => 'required',
        'term' => 'required',
        'ad_feat' => 'required',
    ];


}
