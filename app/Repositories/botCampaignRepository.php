<?php

namespace App\Repositories;

use App\Models\botCampaign;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class botCampaignRepository
 * @package App\Repositories
 * @version January 14, 2019, 12:12 pm UTC
 *
 * @method botCampaign findWithoutFail($id, $columns = ['*'])
 * @method botCampaign find($id, $columns = ['*'])
 * @method botCampaign first($columns = ['*'])
*/
class botCampaignRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'campaign_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return botCampaign::class;
    }
}
