<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class emailcontent
 * @package App\Models
 * @version December 10, 2018, 6:18 am UTC
 *
 * @property string company_id
 * @property string smtp
 * @property string smtp_user_id
 * @property string smtp_password
 * @property string welcome_text
 * @property string new_password_text
 * @property string new_affiliate_text
 * @property string delete_account_text
 */
class emailcontent extends Model
{
    use SoftDeletes;

    public $table = 'emailcontents';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'string',
        'smtp' => 'string',
        'smtp_user_id' => 'string',
        'smtp_password' => 'string',
        'welcome_text' => 'string',
        'new_password_text' => 'string',
        'new_affiliate_text' => 'string',
        'delete_account_text' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
