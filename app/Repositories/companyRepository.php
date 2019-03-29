<?php

namespace App\Repositories;

use App\Models\company;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class companyRepository
 * @package App\Repositories
 * @version December 28, 2018, 5:51 am UTC
 *
 * @method company findWithoutFail($id, $columns = ['*'])
 * @method company find($id, $columns = ['*'])
 * @method company first($columns = ['*'])
*/
class companyRepository extends BaseRepository
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
        return company::class;
    }
}
