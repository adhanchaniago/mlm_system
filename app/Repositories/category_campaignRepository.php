<?php

namespace App\Repositories;

use App\Models\category_campaign;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class category_campaignRepository
 * @package App\Repositories
 * @version January 14, 2019, 9:54 am UTC
 *
 * @method category_campaign findWithoutFail($id, $columns = ['*'])
 * @method category_campaign find($id, $columns = ['*'])
 * @method category_campaign first($columns = ['*'])
*/
class category_campaignRepository extends BaseRepository
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
        return category_campaign::class;
    }
}
