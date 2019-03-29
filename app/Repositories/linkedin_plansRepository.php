<?php

namespace App\Repositories;

use App\Models\linkedin_plans;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class linkedin_plansRepository
 * @package App\Repositories
 * @version February 5, 2019, 5:01 am UTC
 *
 * @method linkedin_plans findWithoutFail($id, $columns = ['*'])
 * @method linkedin_plans find($id, $columns = ['*'])
 * @method linkedin_plans first($columns = ['*'])
*/
class linkedin_plansRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'campaigns',
        'contacts',
        'linkedIn_accounts'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return linkedin_plans::class;
    }
}
