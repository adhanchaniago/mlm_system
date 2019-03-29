<?php

namespace App\Repositories;

use App\Models\favorite;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class favoriteRepository
 * @package App\Repositories
 * @version January 14, 2019, 9:41 am UTC
 *
 * @method favorite findWithoutFail($id, $columns = ['*'])
 * @method favorite find($id, $columns = ['*'])
 * @method favorite first($columns = ['*'])
*/
class favoriteRepository extends BaseRepository
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
        return favorite::class;
    }
}
