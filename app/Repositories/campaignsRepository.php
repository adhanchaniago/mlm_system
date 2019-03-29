<?php

namespace App\Repositories;

use App\Models\campaigns;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class campaignsRepository
 * @package App\Repositories
 * @version January 14, 2019, 12:18 pm UTC
 *
 * @method campaigns findWithoutFail($id, $columns = ['*'])
 * @method campaigns find($id, $columns = ['*'])
 * @method campaigns first($columns = ['*'])
*/
class campaignsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'campaign_id',
        'company_id',
        'campaign_name',
        'campaign_title',
        'campaign_image',
        'campaing_link',
        'campaigns_views',
        'campaign_clicks'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return campaigns::class;
    }
}
