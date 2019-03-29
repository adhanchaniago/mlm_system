<?php

namespace App\Repositories;

use App\Models\rank;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class rankRepository
 * @package App\Repositories
 * @version December 28, 2018, 6:09 am UTC
 *
 * @method rank findWithoutFail($id, $columns = ['*'])
 * @method rank find($id, $columns = ['*'])
 * @method rank first($columns = ['*'])
*/
class rankRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'name',
        'image',
        'revenue_trigger',
        'payout_amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return rank::class;
    }
}
