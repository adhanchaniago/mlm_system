<?php

namespace App\Repositories;

use App\Models\weeklyfees;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class weeklyfeesRepository
 * @package App\Repositories
 * @version December 28, 2018, 6:13 am UTC
 *
 * @method weeklyfees findWithoutFail($id, $columns = ['*'])
 * @method weeklyfees find($id, $columns = ['*'])
 * @method weeklyfees first($columns = ['*'])
*/
class weeklyfeesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'begining_date',
        'end_date',
        'amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return weeklyfees::class;
    }
}
