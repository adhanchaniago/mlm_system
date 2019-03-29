<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class levels
 * @package App\Models
 * @version December 27, 2018, 10:34 am UTC
 *
 * @property string company_id
 * @property string share_to_team_revenue
 */
class levels extends Model
{
    use SoftDeletes;

    public $table = 'levels';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'share_to_team_revenue'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'string',
        'share_to_team_revenue' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
