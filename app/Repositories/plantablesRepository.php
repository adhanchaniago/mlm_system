<?php

namespace App\Repositories;

use App\Models\plantables;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class plantablesRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:40 am UTC
 *
 * @method plantables findWithoutFail($id, $columns = ['*'])
 * @method plantables find($id, $columns = ['*'])
 * @method plantables first($columns = ['*'])
*/
class plantablesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'amount',
        'term',
        'sharing_amount',
        'image'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return plantables::class;
    }
}
