<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class campaigns
 * @package App\Models
 * @version January 14, 2019, 12:18 pm UTC
 *
 * @property integer campaign_id
 * @property integer company_id
 * @property string campaign_name
 * @property string campaign_title
 * @property string campaign_image
 * @property string campaing_link
 * @property stringg campaigns_views
 * @property string campaign_clicks
 */
class campaigns extends Model
{
    use SoftDeletes;

    public $table = 'campaigns';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'campaign_id',
        'company_id',
        'campaign_name',
        'campaign_title',
        'campaign_category',
        'campaign_image',
        'campaign_link',
        'campaign_views',
        'campaign_clicks'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'campaign_id' => 'integer',
        'company_id' => 'integer',
        'campaign_category' => 'string',
        'campaign_name' => 'string',
        'campaign_title' => 'string',
        'campaign_image' => 'string',
        'campaign_link' => 'string',
        'campaign_clicks' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'campaign_id' => 'required',
        'company_id' => 'required',
        'campaign_name' => 'required',
        'campaign_title' => 'required',
        'campaign_image' => 'required'
    ];

    public function getCampaignImageAttribute($value)
    {
        return asset("public/campaign_images").'/'.$value;
    }

    public function favorite()
    {
        return $this->hasOne('App\Models\favorite','campaign_id','campaign_id');
    }
}
