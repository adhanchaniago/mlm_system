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
class level extends Model
{
    use SoftDeletes;


    public $table = 'levels';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'level',
        'share_to_team_revenue'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'string',
        'level' => 'string',
        'share_to_team_revenue' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'share_to_team_revenue' => 'required',
    ];

    
}
