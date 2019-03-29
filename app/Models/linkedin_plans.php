<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="linkedin_plans",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="campaigns",
 *          description="campaigns",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="contacts",
 *          description="contacts",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="linkedIn_accounts",
 *          description="linkedIn_accounts",
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
class linkedin_plans extends Model
{
    use SoftDeletes;

    public $table = 'linkedin_plans';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'amount',
        'type',
        'term',
        'campaigns',
        'contacts',
        'linkedIn_accounts',
        'automated_msg',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'amount' => 'string',
        'type' => 'string',
        'term' => 'string',
        'campaigns' => 'string',
        'contacts' => 'string',
        'linkedIn_accounts' => 'string',
        'automated_msg' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
