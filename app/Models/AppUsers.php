<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class bot
 * @package App\Models
 * @version January 14, 2019, 12:15 pm UTC
 *
 * @property integer bot_id
 * @property integer company_id
 * @property string instance_id
 * @property string bot_name
 * @property string bot_type
 */
class AppUsers extends Model
{
//    use SoftDeletes;
    public $table = 'AppUsers';
//    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo',
        'password',
        'status',
        'activated',
        'activation_hash',
        'receive_email',
        'receive_favorite',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'photo' => 'string',
        'password' => 'string',
        'status' => 'string',
        'activated' => 'string',
        'activation_hash' => 'string',
        'receive_email' => 'string',
        'receive_favorite' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}