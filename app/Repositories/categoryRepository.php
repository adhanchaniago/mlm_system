<?php

namespace App\Repositories;

use App\Models\category;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class categoryRepository
 * @package App\Repositories
 * @version January 14, 2019, 12:16 pm UTC
 *
 * @method category findWithoutFail($id, $columns = ['*'])
 * @method category find($id, $columns = ['*'])
 * @method category first($columns = ['*'])
*/
class categoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'category_name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return category::class;
    }
}
