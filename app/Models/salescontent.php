<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class salescontent
 * @package App\Models
 * @version December 10, 2018, 6:20 am UTC
 *
 * @property string company_id
 * @property string type
 * @property string content
 * @property string image
 * @property string title
 */
class salescontent extends Model
{
    use SoftDeletes;

    public $table = 'salescontents';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'type',
        'content',
        'image',
        'video',
        'title'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'string',
        'type' => 'string',
        'content' => 'string',
        'image' => 'string',
        'title' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
