<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
//    public function showRegistrationForm($plan)
//    {
//        return view('companies.company_register')->with('plan',$plan);
//    }

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/stripe';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
            return Validator::make($data, [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
            'email.required' => trans('auth.email_required'),
            'password.required' => trans('auth.psw_required'),
            'email.unique' => trans('auth.email_enique'),
            'password.confirmed' => trans('auth.psw_confirmed'),
            'password.min' => trans('auth.psw_min6'),
            ]
            );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    protected function create(array $data)
    {
//        return $data;
//        code to send verification link starts here
                $hash = bcrypt(time() . rand(0, 99999999));
                $hash = str_replace('/', '', $hash);
                if (DB::table('superAdmin_email')->whereId(1)->exists())
                {
                    $emailContent = DB::table('superAdmin_email')->whereId(1)->first();
                    $array['welcome_text'] = $emailContent->welcome_text;
                }
                $array['email'] = $data['email'];
                $array['name'] = $data['fname'] . ' ' . $data['lname'];
                $array['hash'] = $hash;
                Mail::send('email.welcome', ['array' => $array], function ($message) use ($array) {
                    $message->to($array['email'], $array['name'])->subject(trans('mail.welcome_to_affiliate'));
                });
//        code to send verification link ends here
                $input['fname'] = $data['fname'];
                $input['lname'] = $data['lname'];
                $input['phno'] = $data['phno'];
                $input['email'] = $data['email'];
                $input['address'] = $data['bill_address'];
                $input['address2'] = $data['address2'];
                $input['city'] = $data['city'];
                $input['state'] = $data['state'];
                $input['zip'] = $data['zip'];
                $input['country'] = $data['country'];
                $company = company::create($input);//inserting data to company table
                $PlanInput = [
                    'company_id' => $company->id,
                    'planid' => $data['planid'],
                    'bill_address' => $data['bill_address'],
                ];
                DB::table('companyAffiliatePlans')->insert($PlanInput);
                return User::create([
                    'name' => $data['fname'] . ' ' . $data['lname'],
                    'fname' => $data['fname'],
                    'lname' => $data['lname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'status' => 1,
                    'type' => 'company',
                    'activation_hash' => $hash,
                    'samy_affiliate' => 1,
                    'company_id' => $company->id,
                ]);
    }
}
