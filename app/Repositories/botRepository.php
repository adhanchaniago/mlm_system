<?php

namespace App\Repositories;

use App\Models\bot;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class botRepository
 * @package App\Repositories
 * @version January 14, 2019, 12:15 pm UTC
 *
 * @method bot findWithoutFail($id, $columns = ['*'])
 * @method bot find($id, $columns = ['*'])
 * @method bot first($columns = ['*'])
*/
class botRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'company_id',
        'beacon_UUID',
        'bot_name',
        'bot_type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return bot::class;
    }
}
