<?php
namespace App\Http\Controllers;
use App\Models\contactUs;
use App\Models\emailcontent;
use App\Models\payouthistory;
use App\Models\rank;
use App\Models\revenuehistory;
use App\Models\users;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Models\company;
use App\Models\affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
//use DOMPDF;
use App\Exports\SalesExport;
use dompdf;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->status == '0')
        {
            return view('home');
        }
        elseif (Auth::user()->status == '2')
        {
            return redirect('home');
        }
        else
        {
            return redirect('welcome');
        }
    }
    public function samyindex()
    {
        if (Auth::user()->status == '1')
        {
            return view('frontEnd.samyhome');
        }
        elseif(Auth::user()->status == '4')
        {
            $domain = request()->getHost();
            if ($domain != ''.env('APP_DOMAIN').'')
            {
                return redirect('home');
            }
            else
            {
                return view('frontEnd.samyhome');
            }
        }
        else
        {
            return redirect('home');
        }
    }
    public function companyIndex() //Comapny homepage
    {
        if(Auth::user()->status == '1' && Auth::user()->payment == '1') //if user is company and payment is completed redirect to company page
        {
            return view('frontEnd.home');
        }
        else //if payment is not completed redirect to payment page
        {
            return redirect('stripe');
        }
    }
    //seting timeout for blocking the user account code starts
    public function timeout()
    {
        $timeout = DB::table('timeout')->first();
        return view('timeout.show')->with('timeout',$timeout);
    }
    public function timeoutEdit()
    {
        $timeout = DB::table('timeout')->first();
        return view('timeout.edit')->with('timeout',$timeout);
    }
    public function timeoutSave(Request $request)
    {
        $update = $request->except('_token');
        if (DB::table('timeout')->whereId(1)->exists())
        {
            DB::table('timeout')->whereId(1)->update($update);
        }
        else
        {
            DB::table('timeout')->insert($update);
        }
        return redirect('timeout');
    }
    //seting timeout for blocking the user account code ends
    //managing confirm email (not yet completely done)
    public function confirmEmail($token)
    {
        $id = Auth::user()->id;
        if (User::whereId($id)->where('activation_hash',$token)->where('email',Auth::user()->email)->exists())
        {
            $update['activated'] = 1; //Updating activated column to true
            User::whereId($id)->update($update);
            \Session::flash('activated', trans('home.email_verified'));
            if (Auth::user()->samy_affiliate == 1)
            {
                return redirect('home');
            }
            elseif(Auth::user()->samy_bot == 1)
            {
                return redirect('samybot/campaigns');
            }
            elseif (Auth::user()->samy_linkedIn == 1)
            {
                return redirect('samylinkedIn/campaigns');
            }
            else
            {
                return redirect('home');
            }
        }
        else
        {
            Flash::error(trans('home.verify_failed'));
            return redirect('confirmEmail');
        }
    }
    public function confirm_Email()
    {
        if (Auth::user()->activated == 1)
        {
            if (Auth::user()->samy_affiliate == 1)
            {
                return redirect('home');
            }
            elseif (Auth::user()->samy_bot == 1)
            {
                return redirect('samybot/campaigns');
            }
            elseif (Auth::user()->samy_linkedIn == 1)
            {
                return redirect('samylinkedIn/campaigns');
            }
            elseif (Auth::user()->status== '2')
            {
                return redirect('home');
            }
            elseif (Auth::user()->status == '4')
            {
                $domain = request()->getHost();
                if ($domain != ''.env('APP_DOMAIN').'')
                {
                    return redirect('home');
                }
                else
                {
                    if (Auth::user()->samy_affiliate == 1)
                    {
                        return redirect('home');
                    }
                    elseif (Auth::user()->samy_bot == 1)
                    {
                        return redirect('samybot/campaigns');
                    }
                    elseif (Auth::user()->samy_linkedIn == 1)
                    {
                        return redirect('samylinkedIn/campaigns');
                    }
                }
            }
        }
        return view('frontEnd.verifyEmail');
    }
    public function resendEmail($id)
    {
        // code to resend verification link starts here
        $data = User::whereId($id)->first();
        if ($data->status == '1')
        {
            if (DB::table('superAdmin_email')->whereId(1)->exists())
            {
                $emailContent = DB::table('superAdmin_email')->whereId(1)->first();
                $array['welcome_text'] = $emailContent->welcome_text;
            }
            $array['company'] = "MLM";

        }
        elseif ($data->status == '2')
        {
            $affiliate = affiliate::whereId($data->affiliate_id)->first();
            $company = company::whereId($affiliate->company_id)->first();
            if(emailcontent::where('company_id',$affiliate->company_id)->exists())
            {
                $email_content = emailcontent::where('company_id',$affiliate->company_id)->first();
                if($email_content->smtp != '' || !empty($email_content->smtp) || $email_content->smtp_user_id != '' || !empty($email_content->smtp_user_id) || $email_content->smtp_password != '' || !empty($email_content->smtp_password))
                {
                    $from= array('address' => $email_content->smtp_user_id, 'name' => $company->fname.' '.$company->lname);
                    $username = $email_content->smtp_user_id;
                    $password =  $email_content->smtp_password;
                    $host =  $email_content->smtp;
                    Config::set('mail.from', $from);
                    Config::set('mail.username', $username);
                    Config::set('mail.password', $password);
                    Config::set('mail.host', $host);
                }
                $array['welcome_text'] = $email_content->welcome_text;
            }
            $array['company'] = $company->name;
        }
        elseif($data->status == '4')
        {
            $domain = request()->getHost();
            if ($domain != ''.env('APP_DOMAIN').'')
            {
                $affiliate = affiliate::whereId($data->affiliate_id)->first();
                $company = company::whereId($affiliate->company_id)->first();
                if(emailcontent::where('company_id',$affiliate->company_id)->exists())
                {
                    $email_content = emailcontent::where('company_id',$affiliate->company_id)->first();
                    if($email_content->smtp != '' || !empty($email_content->smtp) || $email_content->smtp_user_id != '' || !empty($email_content->smtp_user_id) || $email_content->smtp_password != '' || !empty($email_content->smtp_password))
                    {
                        $from= array('address' => $email_content->smtp_user_id, 'name' => $company->fname.' '.$company->lname);
                        $username = $email_content->smtp_user_id;
                        $password =  $email_content->smtp_password;
                        $host =  $email_content->smtp;
                        Config::set('mail.from', $from);
                        Config::set('mail.username', $username);
                        Config::set('mail.password', $password);
                        Config::set('mail.host', $host);
                    }
                    $array['welcome_text'] = $email_content->welcome_text;
                }
                $array['company'] = $company->name;
            }
            else
            {
                if (DB::table('superAdmin_email')->whereId(1)->exists())
                {
                    $emailContent = DB::table('superAdmin_email')->whereId(1)->first();
                    $array['welcome_text'] = $emailContent->welcome_text;
                }
                $array['company'] = "MLM";
            }
        }
        $hash = bcrypt(time().rand(0,9999999999));
        $hash = str_replace('/','',$hash);
        $array['email'] = $data->email;
        $array['name'] = $data->name;
        $array['hash'] = $hash;
        try{
            Mail::send('email.welcome', ['array' => $array], function ($message) use($array)
            {
                $message->to($array['email'], $array['name'])->subject(trans('mail.welcome_to').$array['company'].'!');
            });
            $update_user['activation_hash'] = $hash;
            User::whereId($id)->update($update_user);
            \Session::flash('success', trans('home.email_sent'));
            return redirect('confirmEmail');
        }
        catch (\Swift_TransportException $ex) {
            \Session::flash('success', trans('home.smtp_credential_wrong'));
            return redirect('confirmEmail');
        }
//        code to resend verification link ends here
    }
    public function messages()
    {
        if (Auth::user()->status == '0')
        {
            $contacts = contactUs::get();
            return view('contactUs.index')->with('contacts',$contacts);
        }
        else
        {
            return redirect('home');
        }
    }
    public function deleteMsg($id)
    {
        contactUs::whereId($id)->delete();
        return "Success";
    }
    public function validateProfilePhone($phone)
    {

        if (Auth::user()->status == '1')
        {
            if (company::where('id','!=',Auth::user()->company_id)->where('phno',$phone)->exists())
            {
                return "Failed";
            }
            else
            {
                return "success";
            }
        }
        elseif (Auth::user()->status == '2')
        {
            if (affiliate::where('id','!=',Auth::user()->affiliate_id)->where('phone',$phone)->exists())
            {
                return "Failed";
            }
            else
            {
                return "success";
            }
        }
        elseif (Auth::user()->status == '4')
        {
            $affliate_id = Auth::user()->affiliate_id;
            $company_id = Auth::user()->company_id;
            if (affiliate::where('id','!=',$affliate_id)->where('phone',$phone)->exists() || company::where('id','!=',$company_id)->where('phno',$phone)->exists())
            {
                return "Failed";
            }
            else
            {
                return "success";
            }
        }
    }
    public function testPdf()
    {
//        if (DB::table('purchase_links')->where('affiliate_id',19)->exists())
//        {
//            $sales = DB::table('purchase_links')->where('affiliate_id',19)->get();
//        }
//        else
//        {
//            $sales = "";
//        }
//        @$affiliate = affiliate::whereId(19)->first();
        $data[] = ['Date', 'sdfdesfdewrfewdrfedwName','Amount'];
        $data[] = ['fdsxzDate', 'sdfdesfdewrfewdrfedwName','Amofdsgfunt'];
//        array_push($Info, ['Date', 'Name','Amount']);
//        if($sales != "")
//        {
//            foreach ($sales as $sale)
//            {
//                array_push($Info, [$sale->date,$affiliate->name,$sale->price]);
//            }
//        }
        @$dompdf = new DOMPDF();
        $dompdf->loadHtml('hello world');

// (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
        $dompdf->render();

// Output the generated PDF to Browser
        $dompdf->stream();
    }
    function calculateRank($id)
    {
        $affiliate = affiliate::whereId($id)->first();
        $ranks = rank::where('company_id',$affiliate->company_id)->orderby('id')->get();
        foreach ( $ranks as $rank ) {
            $current_revenue = $affiliate->current_revenue;
            $current_rank = $rank->rank;
            $rank_next = $current_rank+1;
            if(rank::where('company_id',$affiliate->company_id)->where('rank',$rank_next)->exists())
            {
                $next_rank = rank::where('company_id',$affiliate->company_id)->where('rank',$rank_next)->first();
            }
            else
            {
                $next_rank = "";
            }
            if ($next_rank != "")
            {
                if ($current_revenue >= $rank->revenue_trigger && $current_revenue < $next_rank->revenue_trigger)
                {
                    $affiliate_rank = $rank->rank;
                    return $affiliate_rank;
                }
            }
            else
            {
                if ($current_revenue >= $rank->revenue_trigger)
                {
                    $affiliate_rank = $rank->rank;
                }
                else
                {
                    $affiliate_rank = 0;
                }
            }
        }
        return $affiliate_rank;
    }
    public function activateCharge()
    {
        if (DB::table('activateCharge')->whereId(1)->exists())
        {
            $actvate = DB::table('activateCharge')->first();
            $activate_charge = $actvate->amount;
        }
        else
        {
            $activate_charge = "";
        }
        return view('activateCharge.edit',compact('activate_charge'));
    }
    public function activateChargeedit(Request $request)
    {
        $update=$request->except('_token');
        $update['id'] = 1;
        if (DB::table('activateCharge')->whereId(1)->exists())
        {
            DB::table('activateCharge')->whereId(1)->update($update);
        }
        else
        {
            DB::table('activateCharge')->insert($update);
        }
        return redirect()->back();
    }
    public function shipping()
    {
        if (DB::table('shipping')->whereId(1)->exists())
        {
            $shipping = DB::table('shipping')->whereId(1)->first();
            $usa = $shipping->usa;
            $other = $shipping->other;
        }
        else
        {
            $usa = 0;
            $other = 0;
        }
        return view('activateCharge.shipping',compact('usa','other'));
    }
    public function shippingEdit(Request $request)
    {
        $update = $request->except('_token');
        $update['id'] = 1;
        if (DB::table('shipping')->whereId(1)->exists())
        {
            $shipping = DB::table('shipping')->whereId(1)->update($update);
        }
        else
        {
            DB::table('shipping')->insert($update);
        }
        return redirect()->back();
    }
    public function email()
    {
        if (DB::table('superAdmin_email')->whereId(1)->exists())
        {
            $email = DB::table('superAdmin_email')->whereId(1)->first();
        }
        else
        {
            $email = "";
        }
        return view('activateCharge.email',compact('email'));
    }
    public function emailEdit(Request $request)
    {
        $update = $request->except('_token');
        $update['id'] = 1;
        if (DB::table('superAdmin_email')->whereId(1)->exists())
        {
            $shipping = DB::table('superAdmin_email')->whereId(1)->update($update);
        }
        else
        {
            DB::table('superAdmin_email')->insert($update);
        }
        return redirect()->back();
    }
    public function paypalEmail()
    {
        $user = User::whereId(Auth::user()->id)->first();
        return view('superadmin.paypal',compact('user'));
    }
    public function savepaypalEmail(Request $request)
    {
        $input['paypal_email'] = $request->paypal_email;
        User::whereId(Auth::user()->id)->update($input);
        return redirect()->back();
    }
    function compress($source, $destination, $quality,$mime) {



// Set a maximum height and width
        $width = 200;
        $height = 200;

// Content type
        header('Content-Type: image/'.$mime);

// Get new dimensions
        list($width_orig, $height_orig) = \getimagesize($source);

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }

// Resample
        $image_p = \imagecreatetruecolor($width, $height);
        $info = \getimagesize($source);

        if ($info['mime'] == 'image/jpg')
            $image = \imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/jpeg')
            $image = \imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = \imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = \imagecreatefrompng($source);


//            $image = \imagecreatefromjpeg($filename);
        \imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
        \imagejpeg($image_p, $destination, $quality);
        return $destination;
    }
}
