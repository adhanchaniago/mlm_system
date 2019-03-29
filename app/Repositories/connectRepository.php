<?php

namespace App\Repositories;

use App\Models\connect;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class connectRepository
 * @package App\Repositories
 * @version January 14, 2019, 9:53 am UTC
 *
 * @method connect findWithoutFail($id, $columns = ['*'])
 * @method connect find($id, $columns = ['*'])
 * @method connect first($columns = ['*'])
*/
class connectRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'company_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return connect::class;
    }
}
