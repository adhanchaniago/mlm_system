<?php

namespace App\Repositories;

use App\Models\revenuehistory;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class revenuehistoryRepository
 * @package App\Repositories
 * @version December 28, 2018, 6:10 am UTC
 *
 * @method revenuehistory findWithoutFail($id, $columns = ['*'])
 * @method revenuehistory find($id, $columns = ['*'])
 * @method revenuehistory first($columns = ['*'])
*/
class revenuehistoryRepository extends BaseRepository
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
        return revenuehistory::class;
    }
}
