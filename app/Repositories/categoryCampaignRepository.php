<?php

namespace App\Repositories;

use App\Models\categoryCampaign;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class categoryCampaignRepository
 * @package App\Repositories
 * @version January 14, 2019, 12:09 pm UTC
 *
 * @method categoryCampaign findWithoutFail($id, $columns = ['*'])
 * @method categoryCampaign find($id, $columns = ['*'])
 * @method categoryCampaign first($columns = ['*'])
*/
class categoryCampaignRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'campaign_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return categoryCampaign::class;
    }
}
