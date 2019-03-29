<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class botCampaign
 * @package App\Models
 * @version January 14, 2019, 12:12 pm UTC
 *
 * @property integer bot_id
 * @property integer campaign_id
 */
class botCampaign extends Model
{
    use SoftDeletes;

    public $table = 'bot_campaigns';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'bot_id',
        'campaign_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'bot_id' => 'integer',
        'campaign_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bot_id' => 'required',
        'campaign_id' => 'required'
    ];

    public function campaigns()
    {
        return $this->hasOne('App\Models\campaigns','campaign_id','campaign_id');
    }

    public function bot()
    {
        return $this->hasOne('App\Models\bot','bot_id','bot_id');
    }

}
