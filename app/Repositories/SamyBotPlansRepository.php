<?php

namespace App\Repositories;

use App\Models\SamyBotPlans;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class plantableRepository
 * @package App\Repositories
 * @version December 28, 2018, 6:06 am UTC
 *
 * @method plantable findWithoutFail($id, $columns = ['*'])
 * @method plantable find($id, $columns = ['*'])
 * @method plantable first($columns = ['*'])
 */
class SamyBotPlansRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'amount',
        'term',
        'image',
        'ad_feat',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SamyBotPlans::class;
    }
}
