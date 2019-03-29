<?php

namespace App\Repositories;

use App\Models\stripePayment;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class stripePaymentRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:48 am UTC
 *
 * @method stripePayment findWithoutFail($id, $columns = ['*'])
 * @method stripePayment find($id, $columns = ['*'])
 * @method stripePayment first($columns = ['*'])
*/
class stripePaymentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'payment_id',
        'user_id',
        'card_number',
        'amount',
        'date',
        'name',
        'email',
        'phone',
        'address'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return stripePayment::class;
    }
}
