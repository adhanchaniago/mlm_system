<?php

namespace App\Repositories;

use App\Models\level;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class levelRepository
 * @package App\Repositories
 * @version December 28, 2018, 5:57 am UTC
 *
 * @method level findWithoutFail($id, $columns = ['*'])
 * @method level find($id, $columns = ['*'])
 * @method level first($columns = ['*'])
*/
class levelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'share_to_team_revenue'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return level::class;
    }
}
