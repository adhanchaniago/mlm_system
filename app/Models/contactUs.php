<?php



namespace App\Models;



use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;



/**

 * Class level

 * @package App\Models

 * @version December 10, 2018, 6:07 am UTC

 *

 * @property string company_id

 * @property string share_to_team_revenue

 */

class  contactUs extends Model

{

    use SoftDeletes;



    public $table = 'contactUs';





    protected $dates = ['deleted_at'];





    public $fillable = [

        'name',

        'email',

        'msg'

    ];



    /**

     * The attributes that should be casted to native types.

     *

     * @var array

     */

    protected $casts = [

        'name' => 'string',

        'email' => 'string',

        'msg' => 'string',

    ];



    /**

     * Validation rules

     *

     * @var array

     */

    public static $rules = [



    ];





}

