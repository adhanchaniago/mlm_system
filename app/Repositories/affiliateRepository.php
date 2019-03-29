<?php

namespace App\Repositories;

use App\Models\affiliate;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class affiliateRepository
 * @package App\Repositories
 * @version December 10, 2018, 5:49 am UTC
 *
 * @method affiliate findWithoutFail($id, $columns = ['*'])
 * @method affiliate find($id, $columns = ['*'])
 * @method affiliate first($columns = ['*'])
*/
class affiliateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'photo',
        'active',
        'name',
        'email',
        'phone',
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
        return affiliate::class;
    }
}
