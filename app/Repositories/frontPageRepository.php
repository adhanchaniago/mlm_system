<?php

namespace App\Repositories;

use App\Models\frontPage;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class frontPageRepository
 * @package App\Repositories
 * @version December 31, 2018, 5:11 am UTC
 *
 * @method frontPage findWithoutFail($id, $columns = ['*'])
 * @method frontPage find($id, $columns = ['*'])
 * @method frontPage first($columns = ['*'])
*/
class frontPageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slider_image',
        'slider_text',
        'aboutUs_main_description',
        'aboutUs_sub_description',
        'aboutUs_image'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return frontPage::class;
    }
}
