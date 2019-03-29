<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class favorite
 * @package App\Models
 * @version January 14, 2019, 9:41 am UTC
 *
 * @property integer user_id
 * @property integer company_id
 */
class favorite extends Model
{
    use SoftDeletes;

    public $table = 'favorites';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'campaign_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'campaign_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'campaign_id' => 'required'
    ];

    
}
