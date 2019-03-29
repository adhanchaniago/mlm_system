<?php

namespace App\Repositories;

use App\Models\emailcontent;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class emailcontentRepository
 * @package App\Repositories
 * @version December 28, 2018, 5:56 am UTC
 *
 * @method emailcontent findWithoutFail($id, $columns = ['*'])
 * @method emailcontent find($id, $columns = ['*'])
 * @method emailcontent first($columns = ['*'])
*/
class emailcontentRepository extends BaseRepository
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
        return emailcontent::class;
    }
}
