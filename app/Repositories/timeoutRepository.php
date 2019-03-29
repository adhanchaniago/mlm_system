<?php

namespace App\Repositories;

use App\Models\timeout;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class timeoutRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:50 am UTC
 *
 * @method timeout findWithoutFail($id, $columns = ['*'])
 * @method timeout find($id, $columns = ['*'])
 * @method timeout first($columns = ['*'])
*/
class timeoutRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'days'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return timeout::class;
    }
}
