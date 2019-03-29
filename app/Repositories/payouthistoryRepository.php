<?php

namespace App\Repositories;

use App\Models\payouthistory;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class payouthistoryRepository
 * @package App\Repositories
 * @version December 28, 2018, 6:07 am UTC
 *
 * @method payouthistory findWithoutFail($id, $columns = ['*'])
 * @method payouthistory find($id, $columns = ['*'])
 * @method payouthistory first($columns = ['*'])
*/
class payouthistoryRepository extends BaseRepository
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
        return payouthistory::class;
    }
}
