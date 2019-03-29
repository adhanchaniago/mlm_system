<?php

namespace App\Repositories;

use App\Models\revenuehistories;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class revenuehistoriesRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:45 am UTC
 *
 * @method revenuehistories findWithoutFail($id, $columns = ['*'])
 * @method revenuehistories find($id, $columns = ['*'])
 * @method revenuehistories first($columns = ['*'])
*/
class revenuehistoriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'month',
        'year',
        'amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return revenuehistories::class;
    }
}
