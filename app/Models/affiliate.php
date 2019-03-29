<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="affilate",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="company_id",
 *          description="company_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="photo",
 *          description="photo",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="activated",
 *          description="activated",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="invitee",
 *          description="invitee",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="paypal_email",
 *          description="paypal_email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="rankid",
 *          description="rankid",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="current_revenue",
 *          description="current_revenue",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="past_revid",
 *          description="past_revid",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="level_p1_affiliateid",
 *          description="level_p1_affiliateid",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="level_m1_affiliateid",
 *          description="level_m1_affiliateid",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class affiliate extends Model
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

        'level_m1_affiliateid',

        'fname',
        'lname',
        'address',
        'address2',
        'city',
        'zip',
        'country',
        'state',
        'acc_info',
        'user_id',
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

        'level_m1_affiliateid' => 'string',

        'fname' => 'string',
        'lname' => 'string',
        'address' => 'string',
        'address2' => 'string',
        'city' => 'string',
        'zip' => 'string',
        'country' => 'string',
        'state' => 'string',
        'acc_info' => 'string',
        'user_id' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    
}
