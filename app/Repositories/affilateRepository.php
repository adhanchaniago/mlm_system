<?php

namespace App\Repositories;

use App\Models\affilate;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class affilateRepository
 * @package App\Repositories
 * @version December 28, 2018, 5:46 am UTC
 *
 * @method affilate findWithoutFail($id, $columns = ['*'])
 * @method affilate find($id, $columns = ['*'])
 * @method affilate first($columns = ['*'])
*/
class affilateRepository extends BaseRepository
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
        return affilate::class;
    }
}
