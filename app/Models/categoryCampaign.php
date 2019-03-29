<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class categoryCampaign
 * @package App\Models
 * @version January 14, 2019, 12:09 pm UTC
 *
 * @property integer category_id
 * @property integer campaign_id
 */
class categoryCampaign extends Model
{
    use SoftDeletes;

    public $table = 'category_campaigns';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'category_id',
        'campaign_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
        'campaign_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required',
        'campaign_id' => 'required'
    ];

    
}
