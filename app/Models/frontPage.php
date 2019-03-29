<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="frontPage",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slider_image",
 *          description="slider_image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="slider_text",
 *          description="slider_text",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="aboutUs_main_description",
 *          description="aboutUs_main_description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="aboutUs_sub_description",
 *          description="aboutUs_sub_description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="aboutUs_image",
 *          description="aboutUs_image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class frontPage extends Model
{
    use SoftDeletes;

    public $table = 'front_pages';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'slider_image',
        'slider_text',
        'aboutUs_main_description',
        'aboutUs_sub_description',
        'aboutUs_image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'slider_image' => 'string',
        'slider_text' => 'string',
        'aboutUs_main_description' => 'string',
        'aboutUs_sub_description' => 'string',
        'aboutUs_image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
