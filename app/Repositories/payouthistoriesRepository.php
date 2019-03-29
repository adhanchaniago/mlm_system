<?php

namespace App\Repositories;

use App\Models\payouthistories;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class payouthistoriesRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:36 am UTC
 *
 * @method payouthistories findWithoutFail($id, $columns = ['*'])
 * @method payouthistories find($id, $columns = ['*'])
 * @method payouthistories first($columns = ['*'])
*/
class payouthistoriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'affiliate_id',
        'month',
        'year',
        'amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return payouthistories::class;
    }
}
