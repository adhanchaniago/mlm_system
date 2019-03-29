<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class bot
 * @package App\Models
 * @version January 14, 2019, 12:15 pm UTC
 *
 * @property integer bot_id
 * @property integer company_id
 * @property string instance_id
 * @property string bot_name
 * @property string bot_type
 */
class bot extends Model
{
    use SoftDeletes;

    public $table = 'bots';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'bot_id',
        'company_id',
        'instance_id',
        'bot_name',
        'bot_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'bot_id' => 'integer',
        'company_id' => 'integer',
        'instance_id' => 'string',
        'bot_name' => 'string',
        'bot_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bot_id' => 'required',
        'company_id' => 'required',
        'bot_name' => 'required'
    ];

    public function botCampaign()
    {
        return $this->hasOne('App\Models\botCampaign','bot_id','bot_id');
    }
}
