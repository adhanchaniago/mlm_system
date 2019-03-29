<?php

namespace App\Repositories;

use App\Models\emailcontents;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class emailcontentsRepository
 * @package App\Repositories
 * @version December 27, 2018, 10:32 am UTC
 *
 * @method emailcontents findWithoutFail($id, $columns = ['*'])
 * @method emailcontents find($id, $columns = ['*'])
 * @method emailcontents first($columns = ['*'])
*/
class emailcontentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'smtp',
        'smtp_user_id',
        'smtp_password',
        'welcome_text',
        'new_password_text',
        'new_affiliate_text',
        'delete_account_text'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return emailcontents::class;
    }
}
