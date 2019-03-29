<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class affilates
 * @package App\Models
 * @version December 27, 2018, 10:18 am UTC
 *
 * @property string company_id
 * @property string photo
 * @property string activated
 * @property string name
 * @property string email
 * @property string phone
 * @property string invitee
 * @property string paypal_email
 * @property string rankid
 * @property string current_revenue
 * @property string past_revid
 * @property string level_p1_affiliateid
 * @property string level_m1_affiliateid
 */
class affilates extends Model
{
    use SoftDeletes;

    public $table = 'affilates';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'photo',
        'activated',
        'name',
        'email',
        'phone',
        'invitee',
        'paypal_email',
        'rankid',
        'current_revenue',
        'past_revid',
        'level_p1_affiliateid',
        'level_m1_affiliateid'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'string',
        'photo' => 'string',
        'activated' => 'string',
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'invitee' => 'string',
        'paypal_email' => 'string',
        'rankid' => 'string',
        'current_revenue' => 'string',
        'past_revid' => 'string',
        'level_p1_affiliateid' => 'string',
        'level_m1_affiliateid' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
