<?php

namespace App\Repositories;

use App\Models\plantable;
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
class plantableRepository extends BaseRepository
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
        return plantable::class;
    }
}
