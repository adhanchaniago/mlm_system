<?php

namespace App\Repositories;

use App\Models\levels;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class levelsRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:34 am UTC
 *
 * @method levels findWithoutFail($id, $columns = ['*'])
 * @method levels find($id, $columns = ['*'])
 * @method levels first($columns = ['*'])
*/
class levelsRepository extends BaseRepository
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
        return levels::class;
    }
}
