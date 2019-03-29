<?php

namespace App\Repositories;

use App\Models\ranks;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ranksRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:42 am UTC
 *
 * @method ranks findWithoutFail($id, $columns = ['*'])
 * @method ranks find($id, $columns = ['*'])
 * @method ranks first($columns = ['*'])
*/
class ranksRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'name',
        'image',
        'revenue_trigger',
        'payout_amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ranks::class;
    }
}
