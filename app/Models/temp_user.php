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
class temp_user extends Model
{
//    use SoftDeletes;
    public $table = 'temp_user';
//    protected $dates = ['deleted_at'];

    protected $fillable = [
        'fname',
        'lname',
        'name',
        'email',
        'phno',
        'invitee',
        'photo',
        'password',
        'address',
        'address2',
        'state',
        'city',
        'country',
        'zip',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'fname' => 'string',
        'lname' => 'string',
        'name' => 'string',
        'email' => 'string',
        'phno' => 'string',
        'invitee' => 'string',
        'photo' => 'string',
        'password' => 'string',
        'address' => 'string',
        'address2' => 'string',
        'state' => 'string',
        'city' => 'string',
        'country' => 'string',
        'zip' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}