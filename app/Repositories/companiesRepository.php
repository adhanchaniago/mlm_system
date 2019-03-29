<?php

namespace App\Repositories;

use App\Models\companies;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class companiesRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:28 am UTC
 *
 * @method companies findWithoutFail($id, $columns = ['*'])
 * @method companies find($id, $columns = ['*'])
 * @method companies first($columns = ['*'])
*/
class companiesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'address',
        'email',
        'phno',
        'bill_address',
        'card_stripe',
        'logo',
        'planid',
        'domain_name',
        'folder',
        'activated',
        'valid',
        'status',
        'apikey'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return companies::class;
    }
}
