<?php

namespace App\Repositories;

use App\Models\affilates;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class affilatesRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:18 am UTC
 *
 * @method affilates findWithoutFail($id, $columns = ['*'])
 * @method affilates find($id, $columns = ['*'])
 * @method affilates first($columns = ['*'])
*/
class affilatesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'photo',
        'activated',
        'name',
        'email',
        'phone',
        'invitee',
        'paypal_email',
        'rankid',
        'current_revenue',
        'past_revid',
        'level_p1_affiliateid',
        'level_m1_affiliateid'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return affilates::class;
    }
}
