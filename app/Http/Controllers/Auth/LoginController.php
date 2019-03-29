<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\affiliate;
use App\Models\company;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    public function authenticated(Request $request, $user) {
        $domain = url('/');
        if ($user->status == '2')
        {
            $affiliate = affiliate::whereId($user->affiliate_id)->first();
            $company = company::whereId($affiliate->company_id)->first();
            if (company::whereId($affiliate->company_id)->where('domain_name','like','%'.$domain.'%')->exists() == 0)
            {
                Auth::logout();
                return redirect('domain');
            }
        }
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/welcome';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
