<?php

namespace App\Repositories;

use App\Models\salescontent;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class salescontentRepository
 * @package App\Repositories
 * @version December 28, 2018, 6:12 am UTC
 *
 * @method salescontent findWithoutFail($id, $columns = ['*'])
 * @method salescontent find($id, $columns = ['*'])
 * @method salescontent first($columns = ['*'])
*/
class salescontentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'type',
        'content',
        'image',
        'title'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return salescontent::class;
    }
}
