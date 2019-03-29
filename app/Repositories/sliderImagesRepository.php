<?php

namespace App\Repositories;

use App\Models\sliderImages;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class sliderImagesRepository
 * @package App\Repositories
 * @version December 31, 2018, 5:47 am UTC
 *
 * @method sliderImages findWithoutFail($id, $columns = ['*'])
 * @method sliderImages find($id, $columns = ['*'])
 * @method sliderImages first($columns = ['*'])
*/
class sliderImagesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'image',
        'parent_id',
        'text'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return sliderImages::class;
    }
}
