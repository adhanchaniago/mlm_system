<?php



namespace App\Http\Controllers;



use App\Models\contactUs;
use App\Models\emailcontent;
use App\Models\payouthistory;
use App\Models\plantable;
use \App\Models\rank;

use App\Models\revenuehistory;
use App\User;

use App\Models\company;
use Carbon\Carbon;
use App\Models\affiliate;

use GuzzleHttp\Subscriber\Redirect;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Mail;

use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Validator;
use Session;

use Illuminate\Support\Facades\Auth;
use App\Models\level;


class home1Controller extends Controller

{

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

//        $this->middleware('auth');

    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()
    {
        if(Auth::user())
        {
            if(Auth::user()->status == '0')
            {
                return view('home');
            }
            if(Auth::user()->special_user == 1)
            {
                if (Auth::user()->activated == 0)
                {
                    return redirect('confirmEmail');
                }
                elseif (Auth::user()->profile == 0)
                {
                    return redirect('myProfile');
                }
                else
                {
                    $cid = Auth::user()->company_id;
                    $total_affiliates2 = affiliate::where('company_id', $cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                    $total_affiliates = 0;
                    foreach($total_affiliates2 as $affCount)
                    {
                        $affUser = User::where('affiliate_id',$affCount->id)->first();
                        if($affUser->status == '4')
                        {
                            if(DB::table('bot_plans')->where('company_id',$affUser->company_id)->where('payment_status',1)->exists())
                            {
                                $total_affiliates++;
                            }
                        }
                        else
                        {
                            $total_affiliates++;
                        }
                    }
                    $sales_count = DB::table('purchase_history')->where('company_id', $cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
                    if (payouthistory::where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
                    {
                        $payouts = payouthistory::where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                        $total_payout = 0;
                        foreach ($payouts as $payout)
                        {
                            $total_payout += $payout->amount;
                        }
                        $total_payout = number_format($total_payout);
                    }
                    else
                    {
                        $total_payout=0;
                    }
                    if (DB::table('purchase_history')->where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
                    {
                        $revenues = DB::table('purchase_history')->where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                        $total_revenue = 0;
                        foreach ($revenues as $revenue)
                        {
                            $total_revenue +=$revenue->amount;
                        }
                        $total_revenue=number_format($total_revenue);
                    }
                    else
                    {
                        $total_revenue = 0;
                    }

                    return view('frontEnd.home',compact('total_affiliates','sales_count','total_payout','total_revenue'));
                }
            }
            if(Auth::user()->status == '1')
            {
                $company = company::whereId(Auth::user()->company_id)->first();
                if(Auth::user()->samy_affiliate == 0)
                {
                    return view('frontEnd.landing');
                }
                elseif($company->affiliate_disabled == 1)
                {
                    return view('frontEnd.disabled');
                }
                $planTable = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();
                $plan = plantable::whereId($planTable->planid)->first();
                if ($planTable->payment == 0)
                {
                    return redirect('stripe');
                }
                elseif (Auth::user()->activated == 0)
                {
                    return redirect('confirmEmail');
                }
                elseif (Auth::user()->profile == 0)
                {
                    return redirect('myProfile');
                }
                else
                {
                    $cid = Auth::user()->company_id;
                    $total_affiliates2 = affiliate::where('company_id', $cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                    $total_affiliates = 0;
                    foreach($total_affiliates2 as $affCount)
                    {
                        $affUser = User::where('affiliate_id',$affCount->id)->first();
                        if($affUser->status == '4')
                        {
                            if(DB::table('bot_plans')->where('company_id',$affUser->company_id)->where('payment_status',1)->exists())
                            {
                                $total_affiliates++;
                            }
                        }
                        else
                        {
                            $total_affiliates++;
                        }
                    }
                    $sales_count = DB::table('purchase_history')->where('company_id', $cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
                    if (payouthistory::where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
                    {
                        $payouts = payouthistory::where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                        $total_payout = 0;
                        foreach ($payouts as $payout)
                        {
                            $total_payout += $payout->amount;
                        }
                        $total_payout = number_format($total_payout);
                    }
                    else
                    {
                        $total_payout=0;
                    }
                    if (DB::table('purchase_history')->where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
                    {
                        $revenues = DB::table('purchase_history')->where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                        $total_revenue = 0;
                        foreach ($revenues as $revenue)
                        {
                            $total_revenue +=$revenue->amount;
                        }
                        $total_revenue=number_format($total_revenue);
                    }
                    else
                    {
                        $total_revenue = 0;
                    }

                    return view('frontEnd.home',compact('total_affiliates','sales_count','total_payout','total_revenue'));
                }
            }
            elseif(auth::user()->status == '4')
            {
                $domain = request()->getHost();
                if ($domain != ''.env('APP_DOMAIN').'')
                {
                    $aid=Auth::user()->affiliate_id;
                    $affiliate = affiliate::whereId($aid)->first();
                    $userCompany = company::whereId(Auth::user()->company_id)->first();
                    if(DB::table('bot_plans')->where('company_id',Auth::user()->company_id)->where('payment_status',1)->exists() == 0)
                    {
                        $invited_user = User::whereId($affiliate->invitee)->first();
                        Flash::error(trans('plan.purchase_required'));
                        return redirect('samybot/plan?affiliate_id='.$invited_user->affiliate_id);
                    }
                    $company = company::whereId($affiliate->company_id)->first();
                    if(Auth::user()->activated == 0)
                    {
                        return redirect('confirmEmail');
                    }
                    elseif(Auth::user()->profile == 0)
                    {
                        return redirect('myProfile');
                    }
                    elseif ($company->affiliate_disabled == 1)
                    {
                        return view('frontEnd.disabled');
                    }
                    else
                    {
                        $aid=Auth::user()->affiliate_id;
                        $affiliate = affiliate::whereId($aid)->first();
                        $company = company::whereId($affiliate->company_id)->first();
                        if ($company->affiliate_disabled == 1)
                        {
                            return view('frontEnd.disabled');
                        }
                        $pageHeader = trans('header.home');

                        $affiliate_count2 = affiliate::where('invitee', Auth::user()->id)->whereDate('created_at','>=',Carbon::now()->startOfMonth())->get();
                        $affiliate_count = 0;
                        foreach($affiliate_count2 as $affCount)
                        {
                            $affUser = User::where('affiliate_id',$affCount->id)->first();
                            if($affUser->status == '4')
                            {
                                if(DB::table('bot_plans')->where('company_id',$affUser->company_id)->where('payment_status',1)->exists())
                                {
                                    $affiliate_count++;
                                }
                            }
                            else
                            {
                                $affiliate_count++;
                            }
                        }
                        $sales_count = DB::table('purchase_history')->where('affiliate_id', Auth::user()->affiliate_id)->where('created_at', '>=', Carbon::now()->startOfMonth())->count();

                        $revenue_total = 0;
                        $rankid = $this->calculateRank($aid);
//                        return $rankid;
                        if(rank::where('company_id',$company->id)->where('rank',$rankid)->exists())
                        {
                            $rank = rank::where('company_id',$company->id)->where('rank',$rankid)->first();
                            $payout_total = $rank->payout_amount;
                        }
                        else
                        {
                            $rank = "";
                            $payout_total=0;
                        }
                        $payout_total = number_format($payout_total);
                        if (DB::table('purchase_history')->where('affiliate_id',$aid)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
                        {
                            $revenues = DB::table('purchase_history')->where('affiliate_id',$aid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                            foreach($revenues as $revenue)
                            {
                                $revenue_total += (float)$revenue->amount;
                            }
                        }
                        $revenue_total=number_format($revenue_total);
                        return view('frontEnd.affiliate.home',compact('affiliate_count','sales_count','payout_total','revenue_total','pageHeader','affiliate','rank'));

                    }
                }
                else
                {
                    $company = company::whereId(Auth::user()->company_id)->first();
                    if (auth::user()->samy_affiliate == 0)
                    {
                        return view('frontEnd.landing');
                    }
                    if ($company->affiliate_disabled == 1)
                    {
                        return view('frontEnd.disabled');
                    }
                    $planTable = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();
                    $plan = plantable::whereId($planTable->planid)->first();
                    if ($planTable->payment == 0)
                    {
                        return redirect('stripe');
                    }
                    elseif (Auth::user()->activated == 0)
                    {
                        return redirect('resendEmail'.'/'.Auth::user()->id);
                    }
                    elseif(Auth::user()->profile == 0)
                    {
                        return redirect('myProfile');
                    }
                    else
                    {
                        $cid = Auth::user()->company_id;
                        $total_affiliates2 = affiliate::where('company_id', $cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                        $total_affiliates = 0;
                        foreach($total_affiliates2 as $affCount)
                        {
                            $affUser = User::where('affiliate_id',$affCount->id)->first();
                            if($affUser->status == '4')
                            {
                                if(DB::table('bot_plans')->where('company_id',$affUser->company_id)->where('payment_status',1)->exists())
                                {
                                    $total_affiliates++;
                                }
                            }
                            else
                            {
                                $total_affiliates++;
                            }
                        }
                        $sales_count = DB::table('purchase_history')->where('company_id', $cid)->where('date', '>=', Carbon::now()->startOfMonth())->count();
                        if (payouthistory::where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
                        {
                            $payouts = payouthistory::where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                            $total_payout = 0;
                            foreach ($payouts as $payout)
                            {
                                $total_payout += $payout->amount;
                            }
                            $total_payout = number_format($total_payout);
                        }
                        else
                        {
                            $total_payout=0;
                        }
                        if (DB::table('purchase_history')->where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
                        {
                            $revenues = DB::table('purchase_history')->where('company_id',$cid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                            $total_revenue = 0;
                            foreach ($revenues as $revenue)
                            {
                                $total_revenue +=(float)$revenue->amount;
                            }
                            $total_revenue=number_format($total_revenue);
                        }
                        else
                        {
                            $total_revenue = 0;
                        }

                        return view('frontEnd.home',compact('total_affiliates','sales_count','total_payout','total_revenue'));
                    }
                }
            }
            elseif (Auth::user()->status == '2')
            {

                $aid=Auth::user()->affiliate_id;
                $affiliate = affiliate::whereId($aid)->first();
                $company = company::whereId($affiliate->company_id)->first();
                if (Auth::user()->activated == 0)
                {
                    return redirect('resendMail'.'/'.Auth::user()->id);
                }
                if (Auth::user()->profile == 0)
                {
                    return redirect('myProfile');
                }
                if ($company->affiliate_disabled == 1)
                {
                    return view('frontEnd.disabled');
                }
                $pageHeader = trans('header.home');

                $affiliate_count = affiliate::where('invitee', Auth::user()->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
                $sales_count = DB::table('purchase_history')->where('affiliate_id', Auth::user()->affiliate_id)->where('created_at', '>=', Carbon::now()->startOfMonth())->count();

                $revenue_total = 0;
                $rankid = $this->calculateRank($aid);
//                        return $rankid;
                if(rank::where('company_id',$company->id)->where('rank',$rankid)->exists())
                {
                    $rank = rank::where('company_id',$company->id)->where('rank',$rankid)->first();
                    $payout_total = $rank->payout_amount;
                }
                else
                {
                    $rank = "";
                    $payout_total=0;
                }
                $payout_total = number_format($payout_total);
                if (DB::table('purchase_history')->where('affiliate_id',$aid)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
                {
                    $revenues = DB::table('purchase_history')->where('affiliate_id',$aid)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                    foreach($revenues as $revenue)
                    {
                        $revenue_total += (float)$revenue->amount;
                    }
                }
                $revenue_total=number_format($revenue_total);
                return view('frontEnd.affiliate.home',compact('affiliate_count','sales_count','payout_total','revenue_total','pageHeader','affiliate','rank'));
            }

        }
        else
        {
            $domain = request()->getHost();
            if ($domain != ''.env('APP_DOMAIN').'')
            {
                return redirect('login');
            }
            else
            {
                return view('frontEnd.landing');
            }
        }
    }


    public function samyindex()
    {
        if (Auth::user())
        {
            if (Auth::user()->status == '2')
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
                    return redirect('welcome');
                }
            }
            return redirect('welcome');
        }
        else
        {
            $domain = request()->getHost();
            if ( $domain != 'samy-tech.com')
            {
                if (company::where('domain_name',$domain)->exists())
                {

                    if(Auth::user())
                    {
                        return redirect('home');
                    }
                    $company = company::where('domain_name',$domain)->first();

                    $login = $company->name;
                    return view('auth.login',compact('company','login'));
                }
                else
                {
                    return redirect('login');
                }
            }
            else
            {
                return view('frontEnd.landing');
            }
        }
    }


    public function CompanyRegister($id)
    {
        $countries = array("AF" => "Afghanistan",
            "AX" => "Åland Islands",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, The Democratic Republic of The",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D'ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and Mcdonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, The Former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and The Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and The South Sandwich Islands",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.S.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe");
        sort($countries);
        $countryArray = array(
            'AD'=>array('name'=>'ANDORRA','code'=>'376'),
            'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
            'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
            'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
            'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
            'AL'=>array('name'=>'ALBANIA','code'=>'355'),
            'AM'=>array('name'=>'ARMENIA','code'=>'374'),
            'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
            'AO'=>array('name'=>'ANGOLA','code'=>'244'),
            'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
            'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
            'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
            'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
            'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
            'AW'=>array('name'=>'ARUBA','code'=>'297'),
            'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
            'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
            'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
            'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
            'BE'=>array('name'=>'BELGIUM','code'=>'32'),
            'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
            'BG'=>array('name'=>'BULGARIA','code'=>'359'),
            'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
            'BI'=>array('name'=>'BURUNDI','code'=>'257'),
            'BJ'=>array('name'=>'BENIN','code'=>'229'),
            'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
            'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
            'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
            'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
            'BR'=>array('name'=>'BRAZIL','code'=>'55'),
            'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
            'BT'=>array('name'=>'BHUTAN','code'=>'975'),
            'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
            'BY'=>array('name'=>'BELARUS','code'=>'375'),
            'BZ'=>array('name'=>'BELIZE','code'=>'501'),
            'CA'=>array('name'=>'CANADA','code'=>'1'),
            'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
            'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
            'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
            'CG'=>array('name'=>'CONGO','code'=>'242'),
            'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
            'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
            'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
            'CL'=>array('name'=>'CHILE','code'=>'56'),
            'CM'=>array('name'=>'CAMEROON','code'=>'237'),
            'CN'=>array('name'=>'CHINA','code'=>'86'),
            'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
            'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
            'CU'=>array('name'=>'CUBA','code'=>'53'),
            'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
            'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
            'CY'=>array('name'=>'CYPRUS','code'=>'357'),
            'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
            'DE'=>array('name'=>'GERMANY','code'=>'49'),
            'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
            'DK'=>array('name'=>'DENMARK','code'=>'45'),
            'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
            'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
            'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
            'EC'=>array('name'=>'ECUADOR','code'=>'593'),
            'EE'=>array('name'=>'ESTONIA','code'=>'372'),
            'EG'=>array('name'=>'EGYPT','code'=>'20'),
            'ER'=>array('name'=>'ERITREA','code'=>'291'),
            'ES'=>array('name'=>'SPAIN','code'=>'34'),
            'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
            'FI'=>array('name'=>'FINLAND','code'=>'358'),
            'FJ'=>array('name'=>'FIJI','code'=>'679'),
            'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
            'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
            'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
            'FR'=>array('name'=>'FRANCE','code'=>'33'),
            'GA'=>array('name'=>'GABON','code'=>'241'),
            'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
            'GD'=>array('name'=>'GRENADA','code'=>'1473'),
            'GE'=>array('name'=>'GEORGIA','code'=>'995'),
            'GH'=>array('name'=>'GHANA','code'=>'233'),
            'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
            'GL'=>array('name'=>'GREENLAND','code'=>'299'),
            'GM'=>array('name'=>'GAMBIA','code'=>'220'),
            'GN'=>array('name'=>'GUINEA','code'=>'224'),
            'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
            'GR'=>array('name'=>'GREECE','code'=>'30'),
            'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
            'GU'=>array('name'=>'GUAM','code'=>'1671'),
            'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
            'GY'=>array('name'=>'GUYANA','code'=>'592'),
            'HK'=>array('name'=>'HONG KONG','code'=>'852'),
            'HN'=>array('name'=>'HONDURAS','code'=>'504'),
            'HR'=>array('name'=>'CROATIA','code'=>'385'),
            'HT'=>array('name'=>'HAITI','code'=>'509'),
            'HU'=>array('name'=>'HUNGARY','code'=>'36'),
            'ID'=>array('name'=>'INDONESIA','code'=>'62'),
            'IE'=>array('name'=>'IRELAND','code'=>'353'),
            'IL'=>array('name'=>'ISRAEL','code'=>'972'),
            'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
            'IN'=>array('name'=>'INDIA','code'=>'91'),
            'IQ'=>array('name'=>'IRAQ','code'=>'964'),
            'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
            'IS'=>array('name'=>'ICELAND','code'=>'354'),
            'IT'=>array('name'=>'ITALY','code'=>'39'),
            'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
            'JO'=>array('name'=>'JORDAN','code'=>'962'),
            'JP'=>array('name'=>'JAPAN','code'=>'81'),
            'KE'=>array('name'=>'KENYA','code'=>'254'),
            'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
            'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
            'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
            'KM'=>array('name'=>'COMOROS','code'=>'269'),
            'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
            'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
            'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
            'KW'=>array('name'=>'KUWAIT','code'=>'965'),
            'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
            'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
            'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
            'LB'=>array('name'=>'LEBANON','code'=>'961'),
            'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
            'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
            'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
            'LR'=>array('name'=>'LIBERIA','code'=>'231'),
            'LS'=>array('name'=>'LESOTHO','code'=>'266'),
            'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
            'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
            'LV'=>array('name'=>'LATVIA','code'=>'371'),
            'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
            'MA'=>array('name'=>'MOROCCO','code'=>'212'),
            'MC'=>array('name'=>'MONACO','code'=>'377'),
            'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
            'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
            'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
            'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
            'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
            'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
            'ML'=>array('name'=>'MALI','code'=>'223'),
            'MM'=>array('name'=>'MYANMAR','code'=>'95'),
            'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
            'MO'=>array('name'=>'MACAU','code'=>'853'),
            'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
            'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
            'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
            'MT'=>array('name'=>'MALTA','code'=>'356'),
            'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
            'MV'=>array('name'=>'MALDIVES','code'=>'960'),
            'MW'=>array('name'=>'MALAWI','code'=>'265'),
            'MX'=>array('name'=>'MEXICO','code'=>'52'),
            'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
            'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
            'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
            'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
            'NE'=>array('name'=>'NIGER','code'=>'227'),
            'NG'=>array('name'=>'NIGERIA','code'=>'234'),
            'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
            'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
            'NO'=>array('name'=>'NORWAY','code'=>'47'),
            'NP'=>array('name'=>'NEPAL','code'=>'977'),
            'NR'=>array('name'=>'NAURU','code'=>'674'),
            'NU'=>array('name'=>'NIUE','code'=>'683'),
            'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
            'OM'=>array('name'=>'OMAN','code'=>'968'),
            'PA'=>array('name'=>'PANAMA','code'=>'507'),
            'PE'=>array('name'=>'PERU','code'=>'51'),
            'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
            'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
            'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
            'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
            'PL'=>array('name'=>'POLAND','code'=>'48'),
            'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
            'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
            'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
            'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
            'PW'=>array('name'=>'PALAU','code'=>'680'),
            'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
            'QA'=>array('name'=>'QATAR','code'=>'974'),
            'RO'=>array('name'=>'ROMANIA','code'=>'40'),
            'RS'=>array('name'=>'SERBIA','code'=>'381'),
            'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
            'RW'=>array('name'=>'RWANDA','code'=>'250'),
            'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
            'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
            'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
            'SD'=>array('name'=>'SUDAN','code'=>'249'),
            'SE'=>array('name'=>'SWEDEN','code'=>'46'),
            'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
            'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
            'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
            'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
            'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
            'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
            'SN'=>array('name'=>'SENEGAL','code'=>'221'),
            'SO'=>array('name'=>'SOMALIA','code'=>'252'),
            'SR'=>array('name'=>'SURINAME','code'=>'597'),
            'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
            'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
            'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
            'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
            'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
            'TD'=>array('name'=>'CHAD','code'=>'235'),
            'TG'=>array('name'=>'TOGO','code'=>'228'),
            'TH'=>array('name'=>'THAILAND','code'=>'66'),
            'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
            'TK'=>array('name'=>'TOKELAU','code'=>'690'),
            'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
            'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
            'TN'=>array('name'=>'TUNISIA','code'=>'216'),
            'TO'=>array('name'=>'TONGA','code'=>'676'),
            'TR'=>array('name'=>'TURKEY','code'=>'90'),
            'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
            'TV'=>array('name'=>'TUVALU','code'=>'688'),
            'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
            'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
            'UA'=>array('name'=>'UKRAINE','code'=>'380'),
            'UG'=>array('name'=>'UGANDA','code'=>'256'),
            'US'=>array('name'=>'UNITED STATES','code'=>'1'),
            'UY'=>array('name'=>'URUGUAY','code'=>'598'),
            'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
            'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
            'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
            'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
            'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
            'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
            'VN'=>array('name'=>'VIET NAM','code'=>'84'),
            'VU'=>array('name'=>'VANUATU','code'=>'678'),
            'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
            'WS'=>array('name'=>'SAMOA','code'=>'685'),
            'XK'=>array('name'=>'KOSOVO','code'=>'381'),
            'YE'=>array('name'=>'YEMEN','code'=>'967'),
            'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
            'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
            'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
            'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
        );
        $plan = plantable::whereId($id)->first();
        $ip =  $_SERVER['REMOTE_ADDR'];
        $json       = file_get_contents("http://ipinfo.io/{$ip}");
        $details    = json_decode($json);
        $country = $details->country;
        $code = $countryArray[$country]['code'];
        if (Auth::user())
        {
            $id = Auth::user()->company_id;

            $company = company::whereId($id)->first();
            if (Auth::user()->samy_affiliate == 1)
            {
                return redirect('home');
            }
            else
            {
                return view('companies.company_register',compact('plan','code','countries','company'));
            }
        }
        return view('companies.company_register',compact('plan','code','countries'));
//        return view('companies.register_backup',compact('plan','code'));
    }


    public function showAffliateForm($id,$invite,$email,$special)
    {
        if (Auth::user())
        {
            return redirect('home');
        }
        $data['company'] = decrypt($id);
        $data['invitee'] =  decrypt($invite);
        $data['email'] = decrypt($email);
        if (User::whereId($data['invitee'])->exists()==0)
        {
            Flash::error(trans('affiliate.no_longer_exists'));
            return redirect('login');
        }
        else
        {
            if(company::whereId($data['company'])->exists() == 0)
            {
                Flash::error(trans('affiliate.company_no_longer_exists'));
                return redirect('login');
            }
            else
            {
                $company = company::whereId($data['company'])->first();
                if($company->affiliate_disabled == 1)
                {
                    Flash::error(trans('affiliate.company_no_longer_exists'));
                    return redirect('login');
                }
            }
        }
        $user = User::whereId($data['invitee'])->first();
        if($user->status == '2' && $user->special_user != 1)
        {
            $id = $user->affiliate_id;
            $old_id = Cookie::get('affiliate_id');
            if ($id == $old_id)
            {
                Cookie::queue('affiliate_id',$id);
            }
            else
            {
                if(affiliate::whereId($old_id)->exists() == 0)
                {
                    Cookie::queue('affiliate_id',$id);
                }
                else
                {
                    $newUser = User::where('affiliate_id',$old_id)->first();
                    $data['invitee'] = $newUser->id;
                    Cookie::queue('affiliate_id',$old_id);
                }
            }
//                return Cookie::get('affiliate_id');
        }
        if($user->special_user == 1)
        {
            Cookie::queue('special_type',$data['invitee']);
            Cookie::queue('special_email',$data['email']);
            return redirect('samybot/plan');
        }
        elseif($user->status == 4)
        {
            if ($special == 1)
            {
                $id = $user->affiliate_id;
                $old_id = Cookie::get('affiliate_id');
                if ($id == $old_id)
                {
                    Cookie::queue('affiliate_id',$id);
                }
                else
                {
                    if(affiliate::whereId($old_id)->exists() == 0)
                    {
                        Cookie::queue('affiliate_id',$id);
                    }
                    else
                    {
                        $newUser = User::where('affiliate_id',$old_id)->first();
                        $data['invitee'] = $newUser->id;
                        Cookie::queue('affiliate_id',$old_id);
                    }
                }


                Cookie::queue('special_type',$data['invitee']);
                Cookie::queue('special_email',$data['email']);
                return redirect('samybot/plan');
            }
            else
            {
                $countryArray = array(
                    'AD'=>array('name'=>'ANDORRA','code'=>'376'),
                    'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
                    'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
                    'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
                    'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
                    'AL'=>array('name'=>'ALBANIA','code'=>'355'),
                    'AM'=>array('name'=>'ARMENIA','code'=>'374'),
                    'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
                    'AO'=>array('name'=>'ANGOLA','code'=>'244'),
                    'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
                    'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
                    'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
                    'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
                    'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
                    'AW'=>array('name'=>'ARUBA','code'=>'297'),
                    'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
                    'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
                    'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
                    'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
                    'BE'=>array('name'=>'BELGIUM','code'=>'32'),
                    'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
                    'BG'=>array('name'=>'BULGARIA','code'=>'359'),
                    'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
                    'BI'=>array('name'=>'BURUNDI','code'=>'257'),
                    'BJ'=>array('name'=>'BENIN','code'=>'229'),
                    'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
                    'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
                    'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
                    'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
                    'BR'=>array('name'=>'BRAZIL','code'=>'55'),
                    'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
                    'BT'=>array('name'=>'BHUTAN','code'=>'975'),
                    'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
                    'BY'=>array('name'=>'BELARUS','code'=>'375'),
                    'BZ'=>array('name'=>'BELIZE','code'=>'501'),
                    'CA'=>array('name'=>'CANADA','code'=>'1'),
                    'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
                    'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
                    'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
                    'CG'=>array('name'=>'CONGO','code'=>'242'),
                    'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
                    'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
                    'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
                    'CL'=>array('name'=>'CHILE','code'=>'56'),
                    'CM'=>array('name'=>'CAMEROON','code'=>'237'),
                    'CN'=>array('name'=>'CHINA','code'=>'86'),
                    'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
                    'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
                    'CU'=>array('name'=>'CUBA','code'=>'53'),
                    'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
                    'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
                    'CY'=>array('name'=>'CYPRUS','code'=>'357'),
                    'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
                    'DE'=>array('name'=>'GERMANY','code'=>'49'),
                    'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
                    'DK'=>array('name'=>'DENMARK','code'=>'45'),
                    'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
                    'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
                    'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
                    'EC'=>array('name'=>'ECUADOR','code'=>'593'),
                    'EE'=>array('name'=>'ESTONIA','code'=>'372'),
                    'EG'=>array('name'=>'EGYPT','code'=>'20'),
                    'ER'=>array('name'=>'ERITREA','code'=>'291'),
                    'ES'=>array('name'=>'SPAIN','code'=>'34'),
                    'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
                    'FI'=>array('name'=>'FINLAND','code'=>'358'),
                    'FJ'=>array('name'=>'FIJI','code'=>'679'),
                    'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
                    'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
                    'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
                    'FR'=>array('name'=>'FRANCE','code'=>'33'),
                    'GA'=>array('name'=>'GABON','code'=>'241'),
                    'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
                    'GD'=>array('name'=>'GRENADA','code'=>'1473'),
                    'GE'=>array('name'=>'GEORGIA','code'=>'995'),
                    'GH'=>array('name'=>'GHANA','code'=>'233'),
                    'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
                    'GL'=>array('name'=>'GREENLAND','code'=>'299'),
                    'GM'=>array('name'=>'GAMBIA','code'=>'220'),
                    'GN'=>array('name'=>'GUINEA','code'=>'224'),
                    'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
                    'GR'=>array('name'=>'GREECE','code'=>'30'),
                    'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
                    'GU'=>array('name'=>'GUAM','code'=>'1671'),
                    'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
                    'GY'=>array('name'=>'GUYANA','code'=>'592'),
                    'HK'=>array('name'=>'HONG KONG','code'=>'852'),
                    'HN'=>array('name'=>'HONDURAS','code'=>'504'),
                    'HR'=>array('name'=>'CROATIA','code'=>'385'),
                    'HT'=>array('name'=>'HAITI','code'=>'509'),
                    'HU'=>array('name'=>'HUNGARY','code'=>'36'),
                    'ID'=>array('name'=>'INDONESIA','code'=>'62'),
                    'IE'=>array('name'=>'IRELAND','code'=>'353'),
                    'IL'=>array('name'=>'ISRAEL','code'=>'972'),
                    'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
                    'IN'=>array('name'=>'INDIA','code'=>'91'),
                    'IQ'=>array('name'=>'IRAQ','code'=>'964'),
                    'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
                    'IS'=>array('name'=>'ICELAND','code'=>'354'),
                    'IT'=>array('name'=>'ITALY','code'=>'39'),
                    'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
                    'JO'=>array('name'=>'JORDAN','code'=>'962'),
                    'JP'=>array('name'=>'JAPAN','code'=>'81'),
                    'KE'=>array('name'=>'KENYA','code'=>'254'),
                    'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
                    'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
                    'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
                    'KM'=>array('name'=>'COMOROS','code'=>'269'),
                    'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
                    'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
                    'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
                    'KW'=>array('name'=>'KUWAIT','code'=>'965'),
                    'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
                    'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
                    'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
                    'LB'=>array('name'=>'LEBANON','code'=>'961'),
                    'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
                    'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
                    'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
                    'LR'=>array('name'=>'LIBERIA','code'=>'231'),
                    'LS'=>array('name'=>'LESOTHO','code'=>'266'),
                    'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
                    'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
                    'LV'=>array('name'=>'LATVIA','code'=>'371'),
                    'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
                    'MA'=>array('name'=>'MOROCCO','code'=>'212'),
                    'MC'=>array('name'=>'MONACO','code'=>'377'),
                    'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
                    'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
                    'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
                    'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
                    'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
                    'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
                    'ML'=>array('name'=>'MALI','code'=>'223'),
                    'MM'=>array('name'=>'MYANMAR','code'=>'95'),
                    'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
                    'MO'=>array('name'=>'MACAU','code'=>'853'),
                    'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
                    'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
                    'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
                    'MT'=>array('name'=>'MALTA','code'=>'356'),
                    'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
                    'MV'=>array('name'=>'MALDIVES','code'=>'960'),
                    'MW'=>array('name'=>'MALAWI','code'=>'265'),
                    'MX'=>array('name'=>'MEXICO','code'=>'52'),
                    'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
                    'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
                    'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
                    'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
                    'NE'=>array('name'=>'NIGER','code'=>'227'),
                    'NG'=>array('name'=>'NIGERIA','code'=>'234'),
                    'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
                    'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
                    'NO'=>array('name'=>'NORWAY','code'=>'47'),
                    'NP'=>array('name'=>'NEPAL','code'=>'977'),
                    'NR'=>array('name'=>'NAURU','code'=>'674'),
                    'NU'=>array('name'=>'NIUE','code'=>'683'),
                    'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
                    'OM'=>array('name'=>'OMAN','code'=>'968'),
                    'PA'=>array('name'=>'PANAMA','code'=>'507'),
                    'PE'=>array('name'=>'PERU','code'=>'51'),
                    'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
                    'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
                    'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
                    'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
                    'PL'=>array('name'=>'POLAND','code'=>'48'),
                    'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
                    'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
                    'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
                    'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
                    'PW'=>array('name'=>'PALAU','code'=>'680'),
                    'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
                    'QA'=>array('name'=>'QATAR','code'=>'974'),
                    'RO'=>array('name'=>'ROMANIA','code'=>'40'),
                    'RS'=>array('name'=>'SERBIA','code'=>'381'),
                    'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
                    'RW'=>array('name'=>'RWANDA','code'=>'250'),
                    'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
                    'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
                    'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
                    'SD'=>array('name'=>'SUDAN','code'=>'249'),
                    'SE'=>array('name'=>'SWEDEN','code'=>'46'),
                    'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
                    'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
                    'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
                    'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
                    'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
                    'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
                    'SN'=>array('name'=>'SENEGAL','code'=>'221'),
                    'SO'=>array('name'=>'SOMALIA','code'=>'252'),
                    'SR'=>array('name'=>'SURINAME','code'=>'597'),
                    'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
                    'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
                    'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
                    'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
                    'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
                    'TD'=>array('name'=>'CHAD','code'=>'235'),
                    'TG'=>array('name'=>'TOGO','code'=>'228'),
                    'TH'=>array('name'=>'THAILAND','code'=>'66'),
                    'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
                    'TK'=>array('name'=>'TOKELAU','code'=>'690'),
                    'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
                    'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
                    'TN'=>array('name'=>'TUNISIA','code'=>'216'),
                    'TO'=>array('name'=>'TONGA','code'=>'676'),
                    'TR'=>array('name'=>'TURKEY','code'=>'90'),
                    'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
                    'TV'=>array('name'=>'TUVALU','code'=>'688'),
                    'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
                    'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
                    'UA'=>array('name'=>'UKRAINE','code'=>'380'),
                    'UG'=>array('name'=>'UGANDA','code'=>'256'),
                    'US'=>array('name'=>'UNITED STATES','code'=>'1'),
                    'UY'=>array('name'=>'URUGUAY','code'=>'598'),
                    'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
                    'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
                    'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
                    'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
                    'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
                    'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
                    'VN'=>array('name'=>'VIET NAM','code'=>'84'),
                    'VU'=>array('name'=>'VANUATU','code'=>'678'),
                    'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
                    'WS'=>array('name'=>'SAMOA','code'=>'685'),
                    'XK'=>array('name'=>'KOSOVO','code'=>'381'),
                    'YE'=>array('name'=>'YEMEN','code'=>'967'),
                    'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
                    'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
                    'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
                    'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
                );
                $ip =  $_SERVER['REMOTE_ADDR'];
                $json       = file_get_contents("http://ipinfo.io/{$ip}");
                $details    = json_decode($json);
                $country = $details->country;
                $code = $countryArray[$country]['code'];
                $company = company::whereId($data['company'])->first();
//        return $company;
                $company_name = $company->name;
                return view('affiliates.affliate_register',compact('company','company_name','data','code'));
            }
        }
        $countryArray = array(
            'AD'=>array('name'=>'ANDORRA','code'=>'376'),
            'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
            'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
            'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
            'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
            'AL'=>array('name'=>'ALBANIA','code'=>'355'),
            'AM'=>array('name'=>'ARMENIA','code'=>'374'),
            'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
            'AO'=>array('name'=>'ANGOLA','code'=>'244'),
            'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
            'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
            'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
            'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
            'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
            'AW'=>array('name'=>'ARUBA','code'=>'297'),
            'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
            'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
            'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
            'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
            'BE'=>array('name'=>'BELGIUM','code'=>'32'),
            'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
            'BG'=>array('name'=>'BULGARIA','code'=>'359'),
            'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
            'BI'=>array('name'=>'BURUNDI','code'=>'257'),
            'BJ'=>array('name'=>'BENIN','code'=>'229'),
            'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
            'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
            'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
            'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
            'BR'=>array('name'=>'BRAZIL','code'=>'55'),
            'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
            'BT'=>array('name'=>'BHUTAN','code'=>'975'),
            'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
            'BY'=>array('name'=>'BELARUS','code'=>'375'),
            'BZ'=>array('name'=>'BELIZE','code'=>'501'),
            'CA'=>array('name'=>'CANADA','code'=>'1'),
            'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
            'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
            'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
            'CG'=>array('name'=>'CONGO','code'=>'242'),
            'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
            'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
            'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
            'CL'=>array('name'=>'CHILE','code'=>'56'),
            'CM'=>array('name'=>'CAMEROON','code'=>'237'),
            'CN'=>array('name'=>'CHINA','code'=>'86'),
            'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
            'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
            'CU'=>array('name'=>'CUBA','code'=>'53'),
            'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
            'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
            'CY'=>array('name'=>'CYPRUS','code'=>'357'),
            'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
            'DE'=>array('name'=>'GERMANY','code'=>'49'),
            'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
            'DK'=>array('name'=>'DENMARK','code'=>'45'),
            'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
            'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
            'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
            'EC'=>array('name'=>'ECUADOR','code'=>'593'),
            'EE'=>array('name'=>'ESTONIA','code'=>'372'),
            'EG'=>array('name'=>'EGYPT','code'=>'20'),
            'ER'=>array('name'=>'ERITREA','code'=>'291'),
            'ES'=>array('name'=>'SPAIN','code'=>'34'),
            'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
            'FI'=>array('name'=>'FINLAND','code'=>'358'),
            'FJ'=>array('name'=>'FIJI','code'=>'679'),
            'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
            'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
            'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
            'FR'=>array('name'=>'FRANCE','code'=>'33'),
            'GA'=>array('name'=>'GABON','code'=>'241'),
            'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
            'GD'=>array('name'=>'GRENADA','code'=>'1473'),
            'GE'=>array('name'=>'GEORGIA','code'=>'995'),
            'GH'=>array('name'=>'GHANA','code'=>'233'),
            'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
            'GL'=>array('name'=>'GREENLAND','code'=>'299'),
            'GM'=>array('name'=>'GAMBIA','code'=>'220'),
            'GN'=>array('name'=>'GUINEA','code'=>'224'),
            'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
            'GR'=>array('name'=>'GREECE','code'=>'30'),
            'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
            'GU'=>array('name'=>'GUAM','code'=>'1671'),
            'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
            'GY'=>array('name'=>'GUYANA','code'=>'592'),
            'HK'=>array('name'=>'HONG KONG','code'=>'852'),
            'HN'=>array('name'=>'HONDURAS','code'=>'504'),
            'HR'=>array('name'=>'CROATIA','code'=>'385'),
            'HT'=>array('name'=>'HAITI','code'=>'509'),
            'HU'=>array('name'=>'HUNGARY','code'=>'36'),
            'ID'=>array('name'=>'INDONESIA','code'=>'62'),
            'IE'=>array('name'=>'IRELAND','code'=>'353'),
            'IL'=>array('name'=>'ISRAEL','code'=>'972'),
            'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
            'IN'=>array('name'=>'INDIA','code'=>'91'),
            'IQ'=>array('name'=>'IRAQ','code'=>'964'),
            'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
            'IS'=>array('name'=>'ICELAND','code'=>'354'),
            'IT'=>array('name'=>'ITALY','code'=>'39'),
            'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
            'JO'=>array('name'=>'JORDAN','code'=>'962'),
            'JP'=>array('name'=>'JAPAN','code'=>'81'),
            'KE'=>array('name'=>'KENYA','code'=>'254'),
            'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
            'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
            'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
            'KM'=>array('name'=>'COMOROS','code'=>'269'),
            'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
            'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
            'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
            'KW'=>array('name'=>'KUWAIT','code'=>'965'),
            'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
            'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
            'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
            'LB'=>array('name'=>'LEBANON','code'=>'961'),
            'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
            'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
            'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
            'LR'=>array('name'=>'LIBERIA','code'=>'231'),
            'LS'=>array('name'=>'LESOTHO','code'=>'266'),
            'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
            'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
            'LV'=>array('name'=>'LATVIA','code'=>'371'),
            'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
            'MA'=>array('name'=>'MOROCCO','code'=>'212'),
            'MC'=>array('name'=>'MONACO','code'=>'377'),
            'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
            'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
            'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
            'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
            'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
            'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
            'ML'=>array('name'=>'MALI','code'=>'223'),
            'MM'=>array('name'=>'MYANMAR','code'=>'95'),
            'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
            'MO'=>array('name'=>'MACAU','code'=>'853'),
            'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
            'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
            'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
            'MT'=>array('name'=>'MALTA','code'=>'356'),
            'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
            'MV'=>array('name'=>'MALDIVES','code'=>'960'),
            'MW'=>array('name'=>'MALAWI','code'=>'265'),
            'MX'=>array('name'=>'MEXICO','code'=>'52'),
            'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
            'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
            'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
            'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
            'NE'=>array('name'=>'NIGER','code'=>'227'),
            'NG'=>array('name'=>'NIGERIA','code'=>'234'),
            'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
            'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
            'NO'=>array('name'=>'NORWAY','code'=>'47'),
            'NP'=>array('name'=>'NEPAL','code'=>'977'),
            'NR'=>array('name'=>'NAURU','code'=>'674'),
            'NU'=>array('name'=>'NIUE','code'=>'683'),
            'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
            'OM'=>array('name'=>'OMAN','code'=>'968'),
            'PA'=>array('name'=>'PANAMA','code'=>'507'),
            'PE'=>array('name'=>'PERU','code'=>'51'),
            'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
            'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
            'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
            'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
            'PL'=>array('name'=>'POLAND','code'=>'48'),
            'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
            'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
            'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
            'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
            'PW'=>array('name'=>'PALAU','code'=>'680'),
            'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
            'QA'=>array('name'=>'QATAR','code'=>'974'),
            'RO'=>array('name'=>'ROMANIA','code'=>'40'),
            'RS'=>array('name'=>'SERBIA','code'=>'381'),
            'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
            'RW'=>array('name'=>'RWANDA','code'=>'250'),
            'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
            'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
            'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
            'SD'=>array('name'=>'SUDAN','code'=>'249'),
            'SE'=>array('name'=>'SWEDEN','code'=>'46'),
            'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
            'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
            'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
            'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
            'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
            'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
            'SN'=>array('name'=>'SENEGAL','code'=>'221'),
            'SO'=>array('name'=>'SOMALIA','code'=>'252'),
            'SR'=>array('name'=>'SURINAME','code'=>'597'),
            'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
            'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
            'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
            'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
            'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
            'TD'=>array('name'=>'CHAD','code'=>'235'),
            'TG'=>array('name'=>'TOGO','code'=>'228'),
            'TH'=>array('name'=>'THAILAND','code'=>'66'),
            'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
            'TK'=>array('name'=>'TOKELAU','code'=>'690'),
            'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
            'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
            'TN'=>array('name'=>'TUNISIA','code'=>'216'),
            'TO'=>array('name'=>'TONGA','code'=>'676'),
            'TR'=>array('name'=>'TURKEY','code'=>'90'),
            'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
            'TV'=>array('name'=>'TUVALU','code'=>'688'),
            'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
            'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
            'UA'=>array('name'=>'UKRAINE','code'=>'380'),
            'UG'=>array('name'=>'UGANDA','code'=>'256'),
            'US'=>array('name'=>'UNITED STATES','code'=>'1'),
            'UY'=>array('name'=>'URUGUAY','code'=>'598'),
            'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
            'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
            'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
            'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
            'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
            'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
            'VN'=>array('name'=>'VIET NAM','code'=>'84'),
            'VU'=>array('name'=>'VANUATU','code'=>'678'),
            'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
            'WS'=>array('name'=>'SAMOA','code'=>'685'),
            'XK'=>array('name'=>'KOSOVO','code'=>'381'),
            'YE'=>array('name'=>'YEMEN','code'=>'967'),
            'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
            'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
            'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
            'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
        );
        $ip =  $_SERVER['REMOTE_ADDR'];
        $json       = file_get_contents("http://ipinfo.io/{$ip}");
        $details    = json_decode($json);
        $country = $details->country;
        $code = $countryArray[$country]['code'];
        $company = company::whereId($data['company'])->first();
        $company_name = $company->name;
        return view('affiliates.affliate_register',compact('company','company_name','data','code'));
    }


    public function showAffliateForm1($company,$id,$invite,$email,$special)
    {
        if (Auth::user())
        {
            return redirect('home');
        }
        $data['company'] = decrypt($id);
        $data['invitee'] =  decrypt($invite);
        $data['email'] = decrypt($email);
        if (User::whereId($data['invitee'])->exists()==0)
        {
            Flash::error(trans('affiliate.no_longer_exists'));
            return redirect('login');
        }
        else
        {
            if(company::whereId($data['company'])->exists() == 0)
            {
                Flash::error(trans('affiliate.company_no_longer_exists'));
                return redirect('login');
            }
            else
            {
                $company = company::whereId($data['company'])->first();
                if($company->affiliate_disabled == 1)
                {
                    Flash::error(trans('affiliate.company_no_longer_exists'));
                    return redirect('login');
                }
            }
        }
        $user = User::whereId($data['invitee'])->first();
        if($user->status == '2' && $user->special_user != 1)
        {
            $id = $user->affiliate_id;
            $old_id = Cookie::get('affiliate_id');
            if ($id == $old_id)
            {
                Cookie::queue('affiliate_id',$id);
            }
            else
            {
                if(affiliate::whereId($old_id)->exists() == 0)
                {
                    Cookie::queue('affiliate_id',$id);
                }
                else
                {
                    $newUser = User::where('affiliate_id',$old_id)->first();
                    $data['invitee'] = $newUser->id;
                    Cookie::queue('affiliate_id',$old_id);
                }
            }
//                return Cookie::get('affiliate_id');
        }
        if($user->special_user == 1)
        {
            Cookie::queue('special_type',$data['invitee']);
            Cookie::queue('special_email',$data['email']);
            return redirect('samybot/plan');
        }
        elseif($user->status == 4)
        {
            if ($special == 1)
            {
                $id = $user->affiliate_id;
                $old_id = Cookie::get('affiliate_id');
                if ($id == $old_id)
                {
                    Cookie::queue('affiliate_id',$id);
                }
                else
                {
                    if(affiliate::whereId($old_id)->exists() == 0)
                    {
                        Cookie::queue('affiliate_id',$id);
                    }
                    else
                    {
                        $newUser = User::where('affiliate_id',$old_id)->first();
                        $data['invitee'] = $newUser->id;
                        Cookie::queue('affiliate_id',$old_id);
                    }
                }


                Cookie::queue('special_type',$data['invitee']);
                Cookie::queue('special_email',$data['email']);
                return redirect('samybot/plan');
            }
            else
            {
                $countryArray = array(
                    'AD'=>array('name'=>'ANDORRA','code'=>'376'),
                    'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
                    'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
                    'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
                    'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
                    'AL'=>array('name'=>'ALBANIA','code'=>'355'),
                    'AM'=>array('name'=>'ARMENIA','code'=>'374'),
                    'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
                    'AO'=>array('name'=>'ANGOLA','code'=>'244'),
                    'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
                    'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
                    'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
                    'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
                    'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
                    'AW'=>array('name'=>'ARUBA','code'=>'297'),
                    'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
                    'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
                    'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
                    'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
                    'BE'=>array('name'=>'BELGIUM','code'=>'32'),
                    'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
                    'BG'=>array('name'=>'BULGARIA','code'=>'359'),
                    'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
                    'BI'=>array('name'=>'BURUNDI','code'=>'257'),
                    'BJ'=>array('name'=>'BENIN','code'=>'229'),
                    'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
                    'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
                    'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
                    'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
                    'BR'=>array('name'=>'BRAZIL','code'=>'55'),
                    'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
                    'BT'=>array('name'=>'BHUTAN','code'=>'975'),
                    'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
                    'BY'=>array('name'=>'BELARUS','code'=>'375'),
                    'BZ'=>array('name'=>'BELIZE','code'=>'501'),
                    'CA'=>array('name'=>'CANADA','code'=>'1'),
                    'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
                    'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
                    'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
                    'CG'=>array('name'=>'CONGO','code'=>'242'),
                    'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
                    'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
                    'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
                    'CL'=>array('name'=>'CHILE','code'=>'56'),
                    'CM'=>array('name'=>'CAMEROON','code'=>'237'),
                    'CN'=>array('name'=>'CHINA','code'=>'86'),
                    'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
                    'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
                    'CU'=>array('name'=>'CUBA','code'=>'53'),
                    'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
                    'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
                    'CY'=>array('name'=>'CYPRUS','code'=>'357'),
                    'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
                    'DE'=>array('name'=>'GERMANY','code'=>'49'),
                    'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
                    'DK'=>array('name'=>'DENMARK','code'=>'45'),
                    'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
                    'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
                    'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
                    'EC'=>array('name'=>'ECUADOR','code'=>'593'),
                    'EE'=>array('name'=>'ESTONIA','code'=>'372'),
                    'EG'=>array('name'=>'EGYPT','code'=>'20'),
                    'ER'=>array('name'=>'ERITREA','code'=>'291'),
                    'ES'=>array('name'=>'SPAIN','code'=>'34'),
                    'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
                    'FI'=>array('name'=>'FINLAND','code'=>'358'),
                    'FJ'=>array('name'=>'FIJI','code'=>'679'),
                    'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
                    'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
                    'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
                    'FR'=>array('name'=>'FRANCE','code'=>'33'),
                    'GA'=>array('name'=>'GABON','code'=>'241'),
                    'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
                    'GD'=>array('name'=>'GRENADA','code'=>'1473'),
                    'GE'=>array('name'=>'GEORGIA','code'=>'995'),
                    'GH'=>array('name'=>'GHANA','code'=>'233'),
                    'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
                    'GL'=>array('name'=>'GREENLAND','code'=>'299'),
                    'GM'=>array('name'=>'GAMBIA','code'=>'220'),
                    'GN'=>array('name'=>'GUINEA','code'=>'224'),
                    'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
                    'GR'=>array('name'=>'GREECE','code'=>'30'),
                    'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
                    'GU'=>array('name'=>'GUAM','code'=>'1671'),
                    'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
                    'GY'=>array('name'=>'GUYANA','code'=>'592'),
                    'HK'=>array('name'=>'HONG KONG','code'=>'852'),
                    'HN'=>array('name'=>'HONDURAS','code'=>'504'),
                    'HR'=>array('name'=>'CROATIA','code'=>'385'),
                    'HT'=>array('name'=>'HAITI','code'=>'509'),
                    'HU'=>array('name'=>'HUNGARY','code'=>'36'),
                    'ID'=>array('name'=>'INDONESIA','code'=>'62'),
                    'IE'=>array('name'=>'IRELAND','code'=>'353'),
                    'IL'=>array('name'=>'ISRAEL','code'=>'972'),
                    'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
                    'IN'=>array('name'=>'INDIA','code'=>'91'),
                    'IQ'=>array('name'=>'IRAQ','code'=>'964'),
                    'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
                    'IS'=>array('name'=>'ICELAND','code'=>'354'),
                    'IT'=>array('name'=>'ITALY','code'=>'39'),
                    'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
                    'JO'=>array('name'=>'JORDAN','code'=>'962'),
                    'JP'=>array('name'=>'JAPAN','code'=>'81'),
                    'KE'=>array('name'=>'KENYA','code'=>'254'),
                    'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
                    'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
                    'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
                    'KM'=>array('name'=>'COMOROS','code'=>'269'),
                    'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
                    'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
                    'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
                    'KW'=>array('name'=>'KUWAIT','code'=>'965'),
                    'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
                    'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
                    'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
                    'LB'=>array('name'=>'LEBANON','code'=>'961'),
                    'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
                    'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
                    'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
                    'LR'=>array('name'=>'LIBERIA','code'=>'231'),
                    'LS'=>array('name'=>'LESOTHO','code'=>'266'),
                    'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
                    'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
                    'LV'=>array('name'=>'LATVIA','code'=>'371'),
                    'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
                    'MA'=>array('name'=>'MOROCCO','code'=>'212'),
                    'MC'=>array('name'=>'MONACO','code'=>'377'),
                    'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
                    'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
                    'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
                    'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
                    'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
                    'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
                    'ML'=>array('name'=>'MALI','code'=>'223'),
                    'MM'=>array('name'=>'MYANMAR','code'=>'95'),
                    'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
                    'MO'=>array('name'=>'MACAU','code'=>'853'),
                    'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
                    'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
                    'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
                    'MT'=>array('name'=>'MALTA','code'=>'356'),
                    'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
                    'MV'=>array('name'=>'MALDIVES','code'=>'960'),
                    'MW'=>array('name'=>'MALAWI','code'=>'265'),
                    'MX'=>array('name'=>'MEXICO','code'=>'52'),
                    'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
                    'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
                    'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
                    'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
                    'NE'=>array('name'=>'NIGER','code'=>'227'),
                    'NG'=>array('name'=>'NIGERIA','code'=>'234'),
                    'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
                    'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
                    'NO'=>array('name'=>'NORWAY','code'=>'47'),
                    'NP'=>array('name'=>'NEPAL','code'=>'977'),
                    'NR'=>array('name'=>'NAURU','code'=>'674'),
                    'NU'=>array('name'=>'NIUE','code'=>'683'),
                    'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
                    'OM'=>array('name'=>'OMAN','code'=>'968'),
                    'PA'=>array('name'=>'PANAMA','code'=>'507'),
                    'PE'=>array('name'=>'PERU','code'=>'51'),
                    'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
                    'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
                    'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
                    'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
                    'PL'=>array('name'=>'POLAND','code'=>'48'),
                    'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
                    'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
                    'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
                    'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
                    'PW'=>array('name'=>'PALAU','code'=>'680'),
                    'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
                    'QA'=>array('name'=>'QATAR','code'=>'974'),
                    'RO'=>array('name'=>'ROMANIA','code'=>'40'),
                    'RS'=>array('name'=>'SERBIA','code'=>'381'),
                    'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
                    'RW'=>array('name'=>'RWANDA','code'=>'250'),
                    'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
                    'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
                    'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
                    'SD'=>array('name'=>'SUDAN','code'=>'249'),
                    'SE'=>array('name'=>'SWEDEN','code'=>'46'),
                    'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
                    'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
                    'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
                    'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
                    'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
                    'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
                    'SN'=>array('name'=>'SENEGAL','code'=>'221'),
                    'SO'=>array('name'=>'SOMALIA','code'=>'252'),
                    'SR'=>array('name'=>'SURINAME','code'=>'597'),
                    'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
                    'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
                    'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
                    'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
                    'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
                    'TD'=>array('name'=>'CHAD','code'=>'235'),
                    'TG'=>array('name'=>'TOGO','code'=>'228'),
                    'TH'=>array('name'=>'THAILAND','code'=>'66'),
                    'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
                    'TK'=>array('name'=>'TOKELAU','code'=>'690'),
                    'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
                    'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
                    'TN'=>array('name'=>'TUNISIA','code'=>'216'),
                    'TO'=>array('name'=>'TONGA','code'=>'676'),
                    'TR'=>array('name'=>'TURKEY','code'=>'90'),
                    'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
                    'TV'=>array('name'=>'TUVALU','code'=>'688'),
                    'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
                    'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
                    'UA'=>array('name'=>'UKRAINE','code'=>'380'),
                    'UG'=>array('name'=>'UGANDA','code'=>'256'),
                    'US'=>array('name'=>'UNITED STATES','code'=>'1'),
                    'UY'=>array('name'=>'URUGUAY','code'=>'598'),
                    'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
                    'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
                    'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
                    'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
                    'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
                    'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
                    'VN'=>array('name'=>'VIET NAM','code'=>'84'),
                    'VU'=>array('name'=>'VANUATU','code'=>'678'),
                    'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
                    'WS'=>array('name'=>'SAMOA','code'=>'685'),
                    'XK'=>array('name'=>'KOSOVO','code'=>'381'),
                    'YE'=>array('name'=>'YEMEN','code'=>'967'),
                    'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
                    'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
                    'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
                    'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
                );
                $ip =  $_SERVER['REMOTE_ADDR'];
                $json       = file_get_contents("http://ipinfo.io/{$ip}");
                $details    = json_decode($json);
                $country = $details->country;
                $code = $countryArray[$country]['code'];
                $company = company::whereId($data['company'])->first();
//        return $company;
                $company_name = $company->name;
                return view('affiliates.affliate_register',compact('company','company_name','data','code'));
            }
        }

        $countryArray = array(
            'AD'=>array('name'=>'ANDORRA','code'=>'376'),
            'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
            'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
            'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
            'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
            'AL'=>array('name'=>'ALBANIA','code'=>'355'),
            'AM'=>array('name'=>'ARMENIA','code'=>'374'),
            'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
            'AO'=>array('name'=>'ANGOLA','code'=>'244'),
            'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
            'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
            'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
            'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
            'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
            'AW'=>array('name'=>'ARUBA','code'=>'297'),
            'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
            'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
            'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
            'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
            'BE'=>array('name'=>'BELGIUM','code'=>'32'),
            'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
            'BG'=>array('name'=>'BULGARIA','code'=>'359'),
            'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
            'BI'=>array('name'=>'BURUNDI','code'=>'257'),
            'BJ'=>array('name'=>'BENIN','code'=>'229'),
            'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
            'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
            'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
            'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
            'BR'=>array('name'=>'BRAZIL','code'=>'55'),
            'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
            'BT'=>array('name'=>'BHUTAN','code'=>'975'),
            'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
            'BY'=>array('name'=>'BELARUS','code'=>'375'),
            'BZ'=>array('name'=>'BELIZE','code'=>'501'),
            'CA'=>array('name'=>'CANADA','code'=>'1'),
            'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
            'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
            'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
            'CG'=>array('name'=>'CONGO','code'=>'242'),
            'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
            'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
            'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
            'CL'=>array('name'=>'CHILE','code'=>'56'),
            'CM'=>array('name'=>'CAMEROON','code'=>'237'),
            'CN'=>array('name'=>'CHINA','code'=>'86'),
            'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
            'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
            'CU'=>array('name'=>'CUBA','code'=>'53'),
            'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
            'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
            'CY'=>array('name'=>'CYPRUS','code'=>'357'),
            'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
            'DE'=>array('name'=>'GERMANY','code'=>'49'),
            'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
            'DK'=>array('name'=>'DENMARK','code'=>'45'),
            'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
            'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
            'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
            'EC'=>array('name'=>'ECUADOR','code'=>'593'),
            'EE'=>array('name'=>'ESTONIA','code'=>'372'),
            'EG'=>array('name'=>'EGYPT','code'=>'20'),
            'ER'=>array('name'=>'ERITREA','code'=>'291'),
            'ES'=>array('name'=>'SPAIN','code'=>'34'),
            'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
            'FI'=>array('name'=>'FINLAND','code'=>'358'),
            'FJ'=>array('name'=>'FIJI','code'=>'679'),
            'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
            'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
            'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
            'FR'=>array('name'=>'FRANCE','code'=>'33'),
            'GA'=>array('name'=>'GABON','code'=>'241'),
            'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
            'GD'=>array('name'=>'GRENADA','code'=>'1473'),
            'GE'=>array('name'=>'GEORGIA','code'=>'995'),
            'GH'=>array('name'=>'GHANA','code'=>'233'),
            'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
            'GL'=>array('name'=>'GREENLAND','code'=>'299'),
            'GM'=>array('name'=>'GAMBIA','code'=>'220'),
            'GN'=>array('name'=>'GUINEA','code'=>'224'),
            'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
            'GR'=>array('name'=>'GREECE','code'=>'30'),
            'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
            'GU'=>array('name'=>'GUAM','code'=>'1671'),
            'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
            'GY'=>array('name'=>'GUYANA','code'=>'592'),
            'HK'=>array('name'=>'HONG KONG','code'=>'852'),
            'HN'=>array('name'=>'HONDURAS','code'=>'504'),
            'HR'=>array('name'=>'CROATIA','code'=>'385'),
            'HT'=>array('name'=>'HAITI','code'=>'509'),
            'HU'=>array('name'=>'HUNGARY','code'=>'36'),
            'ID'=>array('name'=>'INDONESIA','code'=>'62'),
            'IE'=>array('name'=>'IRELAND','code'=>'353'),
            'IL'=>array('name'=>'ISRAEL','code'=>'972'),
            'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
            'IN'=>array('name'=>'INDIA','code'=>'91'),
            'IQ'=>array('name'=>'IRAQ','code'=>'964'),
            'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
            'IS'=>array('name'=>'ICELAND','code'=>'354'),
            'IT'=>array('name'=>'ITALY','code'=>'39'),
            'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
            'JO'=>array('name'=>'JORDAN','code'=>'962'),
            'JP'=>array('name'=>'JAPAN','code'=>'81'),
            'KE'=>array('name'=>'KENYA','code'=>'254'),
            'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
            'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
            'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
            'KM'=>array('name'=>'COMOROS','code'=>'269'),
            'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
            'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
            'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
            'KW'=>array('name'=>'KUWAIT','code'=>'965'),
            'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
            'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
            'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
            'LB'=>array('name'=>'LEBANON','code'=>'961'),
            'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
            'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
            'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
            'LR'=>array('name'=>'LIBERIA','code'=>'231'),
            'LS'=>array('name'=>'LESOTHO','code'=>'266'),
            'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
            'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
            'LV'=>array('name'=>'LATVIA','code'=>'371'),
            'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
            'MA'=>array('name'=>'MOROCCO','code'=>'212'),
            'MC'=>array('name'=>'MONACO','code'=>'377'),
            'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
            'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
            'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
            'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
            'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
            'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
            'ML'=>array('name'=>'MALI','code'=>'223'),
            'MM'=>array('name'=>'MYANMAR','code'=>'95'),
            'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
            'MO'=>array('name'=>'MACAU','code'=>'853'),
            'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
            'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
            'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
            'MT'=>array('name'=>'MALTA','code'=>'356'),
            'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
            'MV'=>array('name'=>'MALDIVES','code'=>'960'),
            'MW'=>array('name'=>'MALAWI','code'=>'265'),
            'MX'=>array('name'=>'MEXICO','code'=>'52'),
            'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
            'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
            'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
            'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
            'NE'=>array('name'=>'NIGER','code'=>'227'),
            'NG'=>array('name'=>'NIGERIA','code'=>'234'),
            'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
            'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
            'NO'=>array('name'=>'NORWAY','code'=>'47'),
            'NP'=>array('name'=>'NEPAL','code'=>'977'),
            'NR'=>array('name'=>'NAURU','code'=>'674'),
            'NU'=>array('name'=>'NIUE','code'=>'683'),
            'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
            'OM'=>array('name'=>'OMAN','code'=>'968'),
            'PA'=>array('name'=>'PANAMA','code'=>'507'),
            'PE'=>array('name'=>'PERU','code'=>'51'),
            'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
            'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
            'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
            'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
            'PL'=>array('name'=>'POLAND','code'=>'48'),
            'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
            'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
            'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
            'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
            'PW'=>array('name'=>'PALAU','code'=>'680'),
            'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
            'QA'=>array('name'=>'QATAR','code'=>'974'),
            'RO'=>array('name'=>'ROMANIA','code'=>'40'),
            'RS'=>array('name'=>'SERBIA','code'=>'381'),
            'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
            'RW'=>array('name'=>'RWANDA','code'=>'250'),
            'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
            'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
            'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
            'SD'=>array('name'=>'SUDAN','code'=>'249'),
            'SE'=>array('name'=>'SWEDEN','code'=>'46'),
            'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
            'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
            'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
            'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
            'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
            'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
            'SN'=>array('name'=>'SENEGAL','code'=>'221'),
            'SO'=>array('name'=>'SOMALIA','code'=>'252'),
            'SR'=>array('name'=>'SURINAME','code'=>'597'),
            'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
            'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
            'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
            'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
            'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
            'TD'=>array('name'=>'CHAD','code'=>'235'),
            'TG'=>array('name'=>'TOGO','code'=>'228'),
            'TH'=>array('name'=>'THAILAND','code'=>'66'),
            'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
            'TK'=>array('name'=>'TOKELAU','code'=>'690'),
            'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
            'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
            'TN'=>array('name'=>'TUNISIA','code'=>'216'),
            'TO'=>array('name'=>'TONGA','code'=>'676'),
            'TR'=>array('name'=>'TURKEY','code'=>'90'),
            'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
            'TV'=>array('name'=>'TUVALU','code'=>'688'),
            'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
            'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
            'UA'=>array('name'=>'UKRAINE','code'=>'380'),
            'UG'=>array('name'=>'UGANDA','code'=>'256'),
            'US'=>array('name'=>'UNITED STATES','code'=>'1'),
            'UY'=>array('name'=>'URUGUAY','code'=>'598'),
            'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
            'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
            'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
            'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
            'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
            'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
            'VN'=>array('name'=>'VIET NAM','code'=>'84'),
            'VU'=>array('name'=>'VANUATU','code'=>'678'),
            'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
            'WS'=>array('name'=>'SAMOA','code'=>'685'),
            'XK'=>array('name'=>'KOSOVO','code'=>'381'),
            'YE'=>array('name'=>'YEMEN','code'=>'967'),
            'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
            'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
            'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
            'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
        );
        $ip =  $_SERVER['REMOTE_ADDR'];
        $json       = file_get_contents("http://ipinfo.io/{$ip}");
        $details    = json_decode($json);
        $country = $details->country;
        $code = $countryArray[$country]['code'];
        $company = company::whereId($data['company'])->first();
//        return $company;
        $company_name = $company->name;
        return view('affiliates.affliate_register',compact('company','company_name','data','code'));
    }


    public function showDirectAffliateForm($id,$invite)
    {
        if ($data['company'] = decrypt($id) && $data['invitee'] = decrypt($invite))
        {
            if (Auth::user())
            {
                return redirect('home');
            }
            $data['company'] = decrypt($id);
            $data['invitee'] = decrypt($invite);
            $data['email'] = "";
            if (User::whereId($data['invitee'])->exists()==0)
            {
                Flash::error(trans('affiliate.no_longer_exists'));
                return redirect('login');
            }
            else
            {
                if(company::whereId($data['company'])->exists() == 0)
                {
                    Flash::error(trans('affiliate.company_no_longer_exists'));
                    return redirect('login');
                }
                else
                {
                    $company = company::whereId($data['company'])->first();
                    if($company->affiliate_disabled == 1)
                    {
                        Flash::error(trans('affiliate.company_no_longer_exists'));
                        return redirect('login');
                    }
                }
            }
            $user = User::whereId($data['invitee'])->first();
            if($user->status == '2')
            {
                $id = $user->affiliate_id;
                $old_id = Cookie::get('affiliate_id');
                if ($id == $old_id)
                {
                    Cookie::queue('affiliate_id',$id);
                }
                else
                {
                    if(affiliate::whereId($old_id)->exists() == 0)
                    {
                        Cookie::queue('affiliate_id',$id);
                    }
                    else
                    {
                        $newUser = User::where('affiliate_id',$old_id)->first();
                        $data['invitee'] = $newUser->id;
                        Cookie::queue('affiliate_id',$old_id);
                    }
                }
//                return Cookie::get('affiliate_id');
            }
            if($user->special_user == 1 || $user->status == '4')
            {
                $id = $user->affiliate_id;
                $old_id = Cookie::get('affiliate_id');
                if ($id == $old_id)
                {
                    Cookie::queue('affiliate_id',$id);
                }
                else
                {
                    if(affiliate::whereId($old_id)->exists() == 0)
                    {
                        Cookie::queue('affiliate_id',$id);
                    }
                    else
                    {
                        $newUser = User::where('affiliate_id',$old_id)->first();
                        $data['invitee'] = $newUser->id;
                    }
                }
                Cookie::queue('special_type',$data['invitee']);
                return redirect('samybot/plan');
            }
            $countryArray = array(
                'AD'=>array('name'=>'ANDORRA','code'=>'376'),
                'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
                'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
                'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
                'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
                'AL'=>array('name'=>'ALBANIA','code'=>'355'),
                'AM'=>array('name'=>'ARMENIA','code'=>'374'),
                'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
                'AO'=>array('name'=>'ANGOLA','code'=>'244'),
                'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
                'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
                'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
                'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
                'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
                'AW'=>array('name'=>'ARUBA','code'=>'297'),
                'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
                'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
                'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
                'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
                'BE'=>array('name'=>'BELGIUM','code'=>'32'),
                'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
                'BG'=>array('name'=>'BULGARIA','code'=>'359'),
                'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
                'BI'=>array('name'=>'BURUNDI','code'=>'257'),
                'BJ'=>array('name'=>'BENIN','code'=>'229'),
                'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
                'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
                'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
                'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
                'BR'=>array('name'=>'BRAZIL','code'=>'55'),
                'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
                'BT'=>array('name'=>'BHUTAN','code'=>'975'),
                'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
                'BY'=>array('name'=>'BELARUS','code'=>'375'),
                'BZ'=>array('name'=>'BELIZE','code'=>'501'),
                'CA'=>array('name'=>'CANADA','code'=>'1'),
                'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
                'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
                'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
                'CG'=>array('name'=>'CONGO','code'=>'242'),
                'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
                'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
                'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
                'CL'=>array('name'=>'CHILE','code'=>'56'),
                'CM'=>array('name'=>'CAMEROON','code'=>'237'),
                'CN'=>array('name'=>'CHINA','code'=>'86'),
                'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
                'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
                'CU'=>array('name'=>'CUBA','code'=>'53'),
                'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
                'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
                'CY'=>array('name'=>'CYPRUS','code'=>'357'),
                'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
                'DE'=>array('name'=>'GERMANY','code'=>'49'),
                'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
                'DK'=>array('name'=>'DENMARK','code'=>'45'),
                'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
                'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
                'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
                'EC'=>array('name'=>'ECUADOR','code'=>'593'),
                'EE'=>array('name'=>'ESTONIA','code'=>'372'),
                'EG'=>array('name'=>'EGYPT','code'=>'20'),
                'ER'=>array('name'=>'ERITREA','code'=>'291'),
                'ES'=>array('name'=>'SPAIN','code'=>'34'),
                'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
                'FI'=>array('name'=>'FINLAND','code'=>'358'),
                'FJ'=>array('name'=>'FIJI','code'=>'679'),
                'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
                'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
                'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
                'FR'=>array('name'=>'FRANCE','code'=>'33'),
                'GA'=>array('name'=>'GABON','code'=>'241'),
                'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
                'GD'=>array('name'=>'GRENADA','code'=>'1473'),
                'GE'=>array('name'=>'GEORGIA','code'=>'995'),
                'GH'=>array('name'=>'GHANA','code'=>'233'),
                'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
                'GL'=>array('name'=>'GREENLAND','code'=>'299'),
                'GM'=>array('name'=>'GAMBIA','code'=>'220'),
                'GN'=>array('name'=>'GUINEA','code'=>'224'),
                'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
                'GR'=>array('name'=>'GREECE','code'=>'30'),
                'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
                'GU'=>array('name'=>'GUAM','code'=>'1671'),
                'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
                'GY'=>array('name'=>'GUYANA','code'=>'592'),
                'HK'=>array('name'=>'HONG KONG','code'=>'852'),
                'HN'=>array('name'=>'HONDURAS','code'=>'504'),
                'HR'=>array('name'=>'CROATIA','code'=>'385'),
                'HT'=>array('name'=>'HAITI','code'=>'509'),
                'HU'=>array('name'=>'HUNGARY','code'=>'36'),
                'ID'=>array('name'=>'INDONESIA','code'=>'62'),
                'IE'=>array('name'=>'IRELAND','code'=>'353'),
                'IL'=>array('name'=>'ISRAEL','code'=>'972'),
                'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
                'IN'=>array('name'=>'INDIA','code'=>'91'),
                'IQ'=>array('name'=>'IRAQ','code'=>'964'),
                'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
                'IS'=>array('name'=>'ICELAND','code'=>'354'),
                'IT'=>array('name'=>'ITALY','code'=>'39'),
                'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
                'JO'=>array('name'=>'JORDAN','code'=>'962'),
                'JP'=>array('name'=>'JAPAN','code'=>'81'),
                'KE'=>array('name'=>'KENYA','code'=>'254'),
                'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
                'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
                'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
                'KM'=>array('name'=>'COMOROS','code'=>'269'),
                'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
                'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
                'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
                'KW'=>array('name'=>'KUWAIT','code'=>'965'),
                'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
                'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
                'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
                'LB'=>array('name'=>'LEBANON','code'=>'961'),
                'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
                'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
                'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
                'LR'=>array('name'=>'LIBERIA','code'=>'231'),
                'LS'=>array('name'=>'LESOTHO','code'=>'266'),
                'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
                'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
                'LV'=>array('name'=>'LATVIA','code'=>'371'),
                'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
                'MA'=>array('name'=>'MOROCCO','code'=>'212'),
                'MC'=>array('name'=>'MONACO','code'=>'377'),
                'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
                'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
                'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
                'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
                'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
                'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
                'ML'=>array('name'=>'MALI','code'=>'223'),
                'MM'=>array('name'=>'MYANMAR','code'=>'95'),
                'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
                'MO'=>array('name'=>'MACAU','code'=>'853'),
                'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
                'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
                'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
                'MT'=>array('name'=>'MALTA','code'=>'356'),
                'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
                'MV'=>array('name'=>'MALDIVES','code'=>'960'),
                'MW'=>array('name'=>'MALAWI','code'=>'265'),
                'MX'=>array('name'=>'MEXICO','code'=>'52'),
                'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
                'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
                'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
                'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
                'NE'=>array('name'=>'NIGER','code'=>'227'),
                'NG'=>array('name'=>'NIGERIA','code'=>'234'),
                'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
                'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
                'NO'=>array('name'=>'NORWAY','code'=>'47'),
                'NP'=>array('name'=>'NEPAL','code'=>'977'),
                'NR'=>array('name'=>'NAURU','code'=>'674'),
                'NU'=>array('name'=>'NIUE','code'=>'683'),
                'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
                'OM'=>array('name'=>'OMAN','code'=>'968'),
                'PA'=>array('name'=>'PANAMA','code'=>'507'),
                'PE'=>array('name'=>'PERU','code'=>'51'),
                'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
                'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
                'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
                'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
                'PL'=>array('name'=>'POLAND','code'=>'48'),
                'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
                'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
                'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
                'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
                'PW'=>array('name'=>'PALAU','code'=>'680'),
                'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
                'QA'=>array('name'=>'QATAR','code'=>'974'),
                'RO'=>array('name'=>'ROMANIA','code'=>'40'),
                'RS'=>array('name'=>'SERBIA','code'=>'381'),
                'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
                'RW'=>array('name'=>'RWANDA','code'=>'250'),
                'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
                'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
                'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
                'SD'=>array('name'=>'SUDAN','code'=>'249'),
                'SE'=>array('name'=>'SWEDEN','code'=>'46'),
                'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
                'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
                'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
                'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
                'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
                'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
                'SN'=>array('name'=>'SENEGAL','code'=>'221'),
                'SO'=>array('name'=>'SOMALIA','code'=>'252'),
                'SR'=>array('name'=>'SURINAME','code'=>'597'),
                'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
                'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
                'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
                'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
                'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
                'TD'=>array('name'=>'CHAD','code'=>'235'),
                'TG'=>array('name'=>'TOGO','code'=>'228'),
                'TH'=>array('name'=>'THAILAND','code'=>'66'),
                'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
                'TK'=>array('name'=>'TOKELAU','code'=>'690'),
                'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
                'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
                'TN'=>array('name'=>'TUNISIA','code'=>'216'),
                'TO'=>array('name'=>'TONGA','code'=>'676'),
                'TR'=>array('name'=>'TURKEY','code'=>'90'),
                'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
                'TV'=>array('name'=>'TUVALU','code'=>'688'),
                'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
                'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
                'UA'=>array('name'=>'UKRAINE','code'=>'380'),
                'UG'=>array('name'=>'UGANDA','code'=>'256'),
                'US'=>array('name'=>'UNITED STATES','code'=>'1'),
                'UY'=>array('name'=>'URUGUAY','code'=>'598'),
                'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
                'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
                'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
                'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
                'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
                'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
                'VN'=>array('name'=>'VIET NAM','code'=>'84'),
                'VU'=>array('name'=>'VANUATU','code'=>'678'),
                'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
                'WS'=>array('name'=>'SAMOA','code'=>'685'),
                'XK'=>array('name'=>'KOSOVO','code'=>'381'),
                'YE'=>array('name'=>'YEMEN','code'=>'967'),
                'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
                'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
                'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
                'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
            );
            $ip =  $_SERVER['REMOTE_ADDR'];
            $json       = file_get_contents("http://ipinfo.io/{$ip}");
            $details    = json_decode($json);
            $country = $details->country;
            $code = $countryArray[$country]['code'];
            $company = company::whereId($data['company'])->first();
            $company_name = $company->name;
            return view('affiliates.affliate_register',compact('company','company_name','data','code'));
        }
        else
        {
            return "Oops Page not Found!";
        }

    }


    public function showinDirectAffliateForm($company,$id,$invite)
    {
        if ($data['company'] = decrypt($id) && $data['invitee'] = decrypt($invite))
        {
            if (Auth::user())
            {
                return redirect('home');
            }
            $data['company'] = decrypt($id);
            $data['invitee'] = decrypt($invite);
            $data['email'] = "";
            if (User::whereId($data['invitee'])->exists()==0)
            {
                Flash::error(trans('affiliate.no_longer_exists'));
                return redirect('login');
            }
            else
            {
                if(company::whereId($data['company'])->exists() == 0)
                {
                    Flash::error(trans('affiliate.company_no_longer_exists'));
                    return redirect('login');
                }
                else
                {
                    $company = company::whereId($data['company'])->first();
                    if($company->affiliate_disabled == 1)
                    {
                        Flash::error(trans('affiliate.company_no_longer_exists'));
                        return redirect('login');
                    }
                }
            }
            $user = User::whereId($data['invitee'])->first();
            if($user->status == '2')
            {
                $id = $user->affiliate_id;
                $old_id = Cookie::get('affiliate_id');
                if ($id == $old_id)
                {
                    Cookie::queue('affiliate_id',$id);
                }
                else
                {
                    if(affiliate::whereId($old_id)->exists() == 0)
                    {
                        Cookie::queue('affiliate_id',$id);
                    }
                    else
                    {
                        $newUser = User::where('affiliate_id',$old_id)->first();
                        $data['invitee'] = $newUser->id;
                        Cookie::queue('affiliate_id',$old_id);
                    }
                }
//                return Cookie::get('affiliate_id');
            }
            if($user->special_user == 1  || $user->status == '4')
            {
                $id = $user->affiliate_id;
                $old_id = Cookie::get('affiliate_id');
                if ($id == $old_id)
                {
                    Cookie::queue('affiliate_id',$id);
                }
                else
                {
                    if(affiliate::whereId($old_id)->exists() == 0)
                    {
                        Cookie::queue('affiliate_id',$id);
                    }
                    else
                    {
                        $newUser = User::where('affiliate_id',$old_id)->first();
                        $data['invitee'] = $newUser->id;
                    }
                }
                Cookie::queue('special_type',$data['invitee']);
                return redirect('samybot/plan');
            }
            $countryArray = array(
                'AD'=>array('name'=>'ANDORRA','code'=>'376'),
                'AE'=>array('name'=>'UNITED ARAB EMIRATES','code'=>'971'),
                'AF'=>array('name'=>'AFGHANISTAN','code'=>'93'),
                'AG'=>array('name'=>'ANTIGUA AND BARBUDA','code'=>'1268'),
                'AI'=>array('name'=>'ANGUILLA','code'=>'1264'),
                'AL'=>array('name'=>'ALBANIA','code'=>'355'),
                'AM'=>array('name'=>'ARMENIA','code'=>'374'),
                'AN'=>array('name'=>'NETHERLANDS ANTILLES','code'=>'599'),
                'AO'=>array('name'=>'ANGOLA','code'=>'244'),
                'AQ'=>array('name'=>'ANTARCTICA','code'=>'672'),
                'AR'=>array('name'=>'ARGENTINA','code'=>'54'),
                'AS'=>array('name'=>'AMERICAN SAMOA','code'=>'1684'),
                'AT'=>array('name'=>'AUSTRIA','code'=>'43'),
                'AU'=>array('name'=>'AUSTRALIA','code'=>'61'),
                'AW'=>array('name'=>'ARUBA','code'=>'297'),
                'AZ'=>array('name'=>'AZERBAIJAN','code'=>'994'),
                'BA'=>array('name'=>'BOSNIA AND HERZEGOVINA','code'=>'387'),
                'BB'=>array('name'=>'BARBADOS','code'=>'1246'),
                'BD'=>array('name'=>'BANGLADESH','code'=>'880'),
                'BE'=>array('name'=>'BELGIUM','code'=>'32'),
                'BF'=>array('name'=>'BURKINA FASO','code'=>'226'),
                'BG'=>array('name'=>'BULGARIA','code'=>'359'),
                'BH'=>array('name'=>'BAHRAIN','code'=>'973'),
                'BI'=>array('name'=>'BURUNDI','code'=>'257'),
                'BJ'=>array('name'=>'BENIN','code'=>'229'),
                'BL'=>array('name'=>'SAINT BARTHELEMY','code'=>'590'),
                'BM'=>array('name'=>'BERMUDA','code'=>'1441'),
                'BN'=>array('name'=>'BRUNEI DARUSSALAM','code'=>'673'),
                'BO'=>array('name'=>'BOLIVIA','code'=>'591'),
                'BR'=>array('name'=>'BRAZIL','code'=>'55'),
                'BS'=>array('name'=>'BAHAMAS','code'=>'1242'),
                'BT'=>array('name'=>'BHUTAN','code'=>'975'),
                'BW'=>array('name'=>'BOTSWANA','code'=>'267'),
                'BY'=>array('name'=>'BELARUS','code'=>'375'),
                'BZ'=>array('name'=>'BELIZE','code'=>'501'),
                'CA'=>array('name'=>'CANADA','code'=>'1'),
                'CC'=>array('name'=>'COCOS (KEELING) ISLANDS','code'=>'61'),
                'CD'=>array('name'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=>'243'),
                'CF'=>array('name'=>'CENTRAL AFRICAN REPUBLIC','code'=>'236'),
                'CG'=>array('name'=>'CONGO','code'=>'242'),
                'CH'=>array('name'=>'SWITZERLAND','code'=>'41'),
                'CI'=>array('name'=>'COTE D IVOIRE','code'=>'225'),
                'CK'=>array('name'=>'COOK ISLANDS','code'=>'682'),
                'CL'=>array('name'=>'CHILE','code'=>'56'),
                'CM'=>array('name'=>'CAMEROON','code'=>'237'),
                'CN'=>array('name'=>'CHINA','code'=>'86'),
                'CO'=>array('name'=>'COLOMBIA','code'=>'57'),
                'CR'=>array('name'=>'COSTA RICA','code'=>'506'),
                'CU'=>array('name'=>'CUBA','code'=>'53'),
                'CV'=>array('name'=>'CAPE VERDE','code'=>'238'),
                'CX'=>array('name'=>'CHRISTMAS ISLAND','code'=>'61'),
                'CY'=>array('name'=>'CYPRUS','code'=>'357'),
                'CZ'=>array('name'=>'CZECH REPUBLIC','code'=>'420'),
                'DE'=>array('name'=>'GERMANY','code'=>'49'),
                'DJ'=>array('name'=>'DJIBOUTI','code'=>'253'),
                'DK'=>array('name'=>'DENMARK','code'=>'45'),
                'DM'=>array('name'=>'DOMINICA','code'=>'1767'),
                'DO'=>array('name'=>'DOMINICAN REPUBLIC','code'=>'1809'),
                'DZ'=>array('name'=>'ALGERIA','code'=>'213'),
                'EC'=>array('name'=>'ECUADOR','code'=>'593'),
                'EE'=>array('name'=>'ESTONIA','code'=>'372'),
                'EG'=>array('name'=>'EGYPT','code'=>'20'),
                'ER'=>array('name'=>'ERITREA','code'=>'291'),
                'ES'=>array('name'=>'SPAIN','code'=>'34'),
                'ET'=>array('name'=>'ETHIOPIA','code'=>'251'),
                'FI'=>array('name'=>'FINLAND','code'=>'358'),
                'FJ'=>array('name'=>'FIJI','code'=>'679'),
                'FK'=>array('name'=>'FALKLAND ISLANDS (MALVINAS)','code'=>'500'),
                'FM'=>array('name'=>'MICRONESIA, FEDERATED STATES OF','code'=>'691'),
                'FO'=>array('name'=>'FAROE ISLANDS','code'=>'298'),
                'FR'=>array('name'=>'FRANCE','code'=>'33'),
                'GA'=>array('name'=>'GABON','code'=>'241'),
                'GB'=>array('name'=>'UNITED KINGDOM','code'=>'44'),
                'GD'=>array('name'=>'GRENADA','code'=>'1473'),
                'GE'=>array('name'=>'GEORGIA','code'=>'995'),
                'GH'=>array('name'=>'GHANA','code'=>'233'),
                'GI'=>array('name'=>'GIBRALTAR','code'=>'350'),
                'GL'=>array('name'=>'GREENLAND','code'=>'299'),
                'GM'=>array('name'=>'GAMBIA','code'=>'220'),
                'GN'=>array('name'=>'GUINEA','code'=>'224'),
                'GQ'=>array('name'=>'EQUATORIAL GUINEA','code'=>'240'),
                'GR'=>array('name'=>'GREECE','code'=>'30'),
                'GT'=>array('name'=>'GUATEMALA','code'=>'502'),
                'GU'=>array('name'=>'GUAM','code'=>'1671'),
                'GW'=>array('name'=>'GUINEA-BISSAU','code'=>'245'),
                'GY'=>array('name'=>'GUYANA','code'=>'592'),
                'HK'=>array('name'=>'HONG KONG','code'=>'852'),
                'HN'=>array('name'=>'HONDURAS','code'=>'504'),
                'HR'=>array('name'=>'CROATIA','code'=>'385'),
                'HT'=>array('name'=>'HAITI','code'=>'509'),
                'HU'=>array('name'=>'HUNGARY','code'=>'36'),
                'ID'=>array('name'=>'INDONESIA','code'=>'62'),
                'IE'=>array('name'=>'IRELAND','code'=>'353'),
                'IL'=>array('name'=>'ISRAEL','code'=>'972'),
                'IM'=>array('name'=>'ISLE OF MAN','code'=>'44'),
                'IN'=>array('name'=>'INDIA','code'=>'91'),
                'IQ'=>array('name'=>'IRAQ','code'=>'964'),
                'IR'=>array('name'=>'IRAN, ISLAMIC REPUBLIC OF','code'=>'98'),
                'IS'=>array('name'=>'ICELAND','code'=>'354'),
                'IT'=>array('name'=>'ITALY','code'=>'39'),
                'JM'=>array('name'=>'JAMAICA','code'=>'1876'),
                'JO'=>array('name'=>'JORDAN','code'=>'962'),
                'JP'=>array('name'=>'JAPAN','code'=>'81'),
                'KE'=>array('name'=>'KENYA','code'=>'254'),
                'KG'=>array('name'=>'KYRGYZSTAN','code'=>'996'),
                'KH'=>array('name'=>'CAMBODIA','code'=>'855'),
                'KI'=>array('name'=>'KIRIBATI','code'=>'686'),
                'KM'=>array('name'=>'COMOROS','code'=>'269'),
                'KN'=>array('name'=>'SAINT KITTS AND NEVIS','code'=>'1869'),
                'KP'=>array('name'=>'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=>'850'),
                'KR'=>array('name'=>'KOREA REPUBLIC OF','code'=>'82'),
                'KW'=>array('name'=>'KUWAIT','code'=>'965'),
                'KY'=>array('name'=>'CAYMAN ISLANDS','code'=>'1345'),
                'KZ'=>array('name'=>'KAZAKSTAN','code'=>'7'),
                'LA'=>array('name'=>'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=>'856'),
                'LB'=>array('name'=>'LEBANON','code'=>'961'),
                'LC'=>array('name'=>'SAINT LUCIA','code'=>'1758'),
                'LI'=>array('name'=>'LIECHTENSTEIN','code'=>'423'),
                'LK'=>array('name'=>'SRI LANKA','code'=>'94'),
                'LR'=>array('name'=>'LIBERIA','code'=>'231'),
                'LS'=>array('name'=>'LESOTHO','code'=>'266'),
                'LT'=>array('name'=>'LITHUANIA','code'=>'370'),
                'LU'=>array('name'=>'LUXEMBOURG','code'=>'352'),
                'LV'=>array('name'=>'LATVIA','code'=>'371'),
                'LY'=>array('name'=>'LIBYAN ARAB JAMAHIRIYA','code'=>'218'),
                'MA'=>array('name'=>'MOROCCO','code'=>'212'),
                'MC'=>array('name'=>'MONACO','code'=>'377'),
                'MD'=>array('name'=>'MOLDOVA, REPUBLIC OF','code'=>'373'),
                'ME'=>array('name'=>'MONTENEGRO','code'=>'382'),
                'MF'=>array('name'=>'SAINT MARTIN','code'=>'1599'),
                'MG'=>array('name'=>'MADAGASCAR','code'=>'261'),
                'MH'=>array('name'=>'MARSHALL ISLANDS','code'=>'692'),
                'MK'=>array('name'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=>'389'),
                'ML'=>array('name'=>'MALI','code'=>'223'),
                'MM'=>array('name'=>'MYANMAR','code'=>'95'),
                'MN'=>array('name'=>'MONGOLIA','code'=>'976'),
                'MO'=>array('name'=>'MACAU','code'=>'853'),
                'MP'=>array('name'=>'NORTHERN MARIANA ISLANDS','code'=>'1670'),
                'MR'=>array('name'=>'MAURITANIA','code'=>'222'),
                'MS'=>array('name'=>'MONTSERRAT','code'=>'1664'),
                'MT'=>array('name'=>'MALTA','code'=>'356'),
                'MU'=>array('name'=>'MAURITIUS','code'=>'230'),
                'MV'=>array('name'=>'MALDIVES','code'=>'960'),
                'MW'=>array('name'=>'MALAWI','code'=>'265'),
                'MX'=>array('name'=>'MEXICO','code'=>'52'),
                'MY'=>array('name'=>'MALAYSIA','code'=>'60'),
                'MZ'=>array('name'=>'MOZAMBIQUE','code'=>'258'),
                'NA'=>array('name'=>'NAMIBIA','code'=>'264'),
                'NC'=>array('name'=>'NEW CALEDONIA','code'=>'687'),
                'NE'=>array('name'=>'NIGER','code'=>'227'),
                'NG'=>array('name'=>'NIGERIA','code'=>'234'),
                'NI'=>array('name'=>'NICARAGUA','code'=>'505'),
                'NL'=>array('name'=>'NETHERLANDS','code'=>'31'),
                'NO'=>array('name'=>'NORWAY','code'=>'47'),
                'NP'=>array('name'=>'NEPAL','code'=>'977'),
                'NR'=>array('name'=>'NAURU','code'=>'674'),
                'NU'=>array('name'=>'NIUE','code'=>'683'),
                'NZ'=>array('name'=>'NEW ZEALAND','code'=>'64'),
                'OM'=>array('name'=>'OMAN','code'=>'968'),
                'PA'=>array('name'=>'PANAMA','code'=>'507'),
                'PE'=>array('name'=>'PERU','code'=>'51'),
                'PF'=>array('name'=>'FRENCH POLYNESIA','code'=>'689'),
                'PG'=>array('name'=>'PAPUA NEW GUINEA','code'=>'675'),
                'PH'=>array('name'=>'PHILIPPINES','code'=>'63'),
                'PK'=>array('name'=>'PAKISTAN','code'=>'92'),
                'PL'=>array('name'=>'POLAND','code'=>'48'),
                'PM'=>array('name'=>'SAINT PIERRE AND MIQUELON','code'=>'508'),
                'PN'=>array('name'=>'PITCAIRN','code'=>'870'),
                'PR'=>array('name'=>'PUERTO RICO','code'=>'1'),
                'PT'=>array('name'=>'PORTUGAL','code'=>'351'),
                'PW'=>array('name'=>'PALAU','code'=>'680'),
                'PY'=>array('name'=>'PARAGUAY','code'=>'595'),
                'QA'=>array('name'=>'QATAR','code'=>'974'),
                'RO'=>array('name'=>'ROMANIA','code'=>'40'),
                'RS'=>array('name'=>'SERBIA','code'=>'381'),
                'RU'=>array('name'=>'RUSSIAN FEDERATION','code'=>'7'),
                'RW'=>array('name'=>'RWANDA','code'=>'250'),
                'SA'=>array('name'=>'SAUDI ARABIA','code'=>'966'),
                'SB'=>array('name'=>'SOLOMON ISLANDS','code'=>'677'),
                'SC'=>array('name'=>'SEYCHELLES','code'=>'248'),
                'SD'=>array('name'=>'SUDAN','code'=>'249'),
                'SE'=>array('name'=>'SWEDEN','code'=>'46'),
                'SG'=>array('name'=>'SINGAPORE','code'=>'65'),
                'SH'=>array('name'=>'SAINT HELENA','code'=>'290'),
                'SI'=>array('name'=>'SLOVENIA','code'=>'386'),
                'SK'=>array('name'=>'SLOVAKIA','code'=>'421'),
                'SL'=>array('name'=>'SIERRA LEONE','code'=>'232'),
                'SM'=>array('name'=>'SAN MARINO','code'=>'378'),
                'SN'=>array('name'=>'SENEGAL','code'=>'221'),
                'SO'=>array('name'=>'SOMALIA','code'=>'252'),
                'SR'=>array('name'=>'SURINAME','code'=>'597'),
                'ST'=>array('name'=>'SAO TOME AND PRINCIPE','code'=>'239'),
                'SV'=>array('name'=>'EL SALVADOR','code'=>'503'),
                'SY'=>array('name'=>'SYRIAN ARAB REPUBLIC','code'=>'963'),
                'SZ'=>array('name'=>'SWAZILAND','code'=>'268'),
                'TC'=>array('name'=>'TURKS AND CAICOS ISLANDS','code'=>'1649'),
                'TD'=>array('name'=>'CHAD','code'=>'235'),
                'TG'=>array('name'=>'TOGO','code'=>'228'),
                'TH'=>array('name'=>'THAILAND','code'=>'66'),
                'TJ'=>array('name'=>'TAJIKISTAN','code'=>'992'),
                'TK'=>array('name'=>'TOKELAU','code'=>'690'),
                'TL'=>array('name'=>'TIMOR-LESTE','code'=>'670'),
                'TM'=>array('name'=>'TURKMENISTAN','code'=>'993'),
                'TN'=>array('name'=>'TUNISIA','code'=>'216'),
                'TO'=>array('name'=>'TONGA','code'=>'676'),
                'TR'=>array('name'=>'TURKEY','code'=>'90'),
                'TT'=>array('name'=>'TRINIDAD AND TOBAGO','code'=>'1868'),
                'TV'=>array('name'=>'TUVALU','code'=>'688'),
                'TW'=>array('name'=>'TAIWAN, PROVINCE OF CHINA','code'=>'886'),
                'TZ'=>array('name'=>'TANZANIA, UNITED REPUBLIC OF','code'=>'255'),
                'UA'=>array('name'=>'UKRAINE','code'=>'380'),
                'UG'=>array('name'=>'UGANDA','code'=>'256'),
                'US'=>array('name'=>'UNITED STATES','code'=>'1'),
                'UY'=>array('name'=>'URUGUAY','code'=>'598'),
                'UZ'=>array('name'=>'UZBEKISTAN','code'=>'998'),
                'VA'=>array('name'=>'HOLY SEE (VATICAN CITY STATE)','code'=>'39'),
                'VC'=>array('name'=>'SAINT VINCENT AND THE GRENADINES','code'=>'1784'),
                'VE'=>array('name'=>'VENEZUELA','code'=>'58'),
                'VG'=>array('name'=>'VIRGIN ISLANDS, BRITISH','code'=>'1284'),
                'VI'=>array('name'=>'VIRGIN ISLANDS, U.S.','code'=>'1340'),
                'VN'=>array('name'=>'VIET NAM','code'=>'84'),
                'VU'=>array('name'=>'VANUATU','code'=>'678'),
                'WF'=>array('name'=>'WALLIS AND FUTUNA','code'=>'681'),
                'WS'=>array('name'=>'SAMOA','code'=>'685'),
                'XK'=>array('name'=>'KOSOVO','code'=>'381'),
                'YE'=>array('name'=>'YEMEN','code'=>'967'),
                'YT'=>array('name'=>'MAYOTTE','code'=>'262'),
                'ZA'=>array('name'=>'SOUTH AFRICA','code'=>'27'),
                'ZM'=>array('name'=>'ZAMBIA','code'=>'260'),
                'ZW'=>array('name'=>'ZIMBABWE','code'=>'263')
            );
            $ip =  $_SERVER['REMOTE_ADDR'];
            $json       = file_get_contents("http://ipinfo.io/{$ip}");
            $details    = json_decode($json);
            $country = $details->country;
            $code = $countryArray[$country]['code'];
            $company = company::whereId($data['company'])->first();
            $company_name = $company->name;
            return view('affiliates.affliate_register',compact('company','company_name','data','code'));
        }
        else
        {
            return "Oops Page not Found!";
        }

    }


    public function affliateRegister(Request $request)
    {
        if(User::where('email',$request->email)->exists())
        {
            \Session::flash('error', trans('auth.user_exists'));
            return redirect()->back();
        }
        if ($request->password != $request->password_confirmation)
        {
            \Session::flash('error', trans('auth.psw_confirmed'));
            return Redirect()->back()->withInput(Input::all());
        }
        $input_affliate = $request->except('_token','photo','first_name','last_name');
        if ($request->hasFile('photo')) //if it contains photo, upload it to server
        {
            $validator=Validator::make($request->all(), [
                'photo' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'photo.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes())
            {
                $photoName = rand(1, 777777777) . time() . '.' . $request->photo->getClientOriginalExtension();
                $mime = $request->photo->getClientOriginalExtension();

                $this->compress($request->photo, public_path('avatars') . '/' . $photoName, 100, $mime);
                $input_affliate['photo'] = $photoName;
                $input['image'] = $photoName;
            }
            else
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $input_affliate['name'] = $request->first_name.' '.$request->last_name;
        $input_affliate['fname'] = $request->first_name;
        $input_affliate['lname'] = $request->last_name;
        $affliate = affiliate::create($input_affliate);
        if (User::whereEmail($request->email)->exists() == 0)
        {
            $hash = bcrypt(time().rand(0,9999999999));
            $hash = str_replace('/','',$hash);
            $array['email'] = $request->email;
            $array['name'] = $request->first_name.' '.$request->last_name;
            $array['hash'] = $hash;
            $array['company_id'] = $request->company_id;
            $company = company::whereId($request->company_id)->first();
            if (emailcontent::where('company_id',$request->id)->exists())
            {
                $email_content = emailcontent::where('company_id',$request->id)->first();
                if($email_content->smtp != '' || !empty($email_content->smtp) || $email_content->smtp_user_id != '' || !empty($email_content->smtp_user_id) || $email_content->smtp_password != '' || !empty($email_content->smtp_password))
                {
                    $from= array('address' => $email_content->smtp_user_id, 'name' => $company->first_name.' '.$company->last_name);
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
            try{
                Mail::send('email.welcome', ['array' => $array], function ($message) use($array)
                {
                    $message->to($array['email'], $array['name'])->subject(trans('mail.welcome').'!');
                });
            }
            catch (\Swift_TransportException $ex) {
                return redirect('login');
            }
//        code to send verification link ends here
            $input['name'] = $request->first_name.' '.$request->last_name;
            $input['fname'] = $request->first_name;
            $input['lname'] = $request->last_name;
            $input['phone'] = $request->phone;
            $input['email'] =  $request->email;
            $input['password'] =  bcrypt($request->password);
            $input['status'] =  "2";
            $input['activation_hash'] = $hash;
            $input['affiliate_id'] = $affliate->id;
            User::create($input);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication passed...
            return redirect()->intended('home');
        }
        else
        {
            return redirect('login');
        }
    }


    public function contactUsStore(Request $request)
    {
        $input = $request->except('token');
        contactUs::create($input);
        return redirect('home');
    }


    public function validatePhone($phone)
    {
        if (affiliate::where('phone',$phone)->exists() || company::where('phno',$phone)->exists())
        {
            return "failed";
        }
        else
        {
            return "success";
        }
    }


    public function plans()
    {
        if (plantable::where('term','month')->exists())
        {
            $plans = plantable::where('term','month')->get();
        }
        else
        {
            $plans = 0;
        }
        $i=1;
        return view('plantables.listPlans',compact('plans','i'));
    }


    public function changeTerm($val)
    {
        $result = "";
        if ($val == 'one')
        {
            if (plantable::where('term','month')->exists())
            {
                $plans = plantable::where('term','month')->get();
            }
            else
            {
                $plans = 0;
            }
            $i=1;
            if ($plans!="")
            {
                foreach($plans as $plan)
                {
                    if ($i%2 ==0)
                    {
                        $result .=
                            '
                            <div class="col-md-4">
                                <div class="card1">
                                    <div class="starter-bsns">
                                        <h3 class="name-padding1">'.strtoupper($plan->type).'</h3>
                                    </div>
                                    <div class="starter-middel1">
                                        <h4>- '.trans('home.up_to').' <b>'.$plan->levels.' '.trans('level.levels').'</b> '.trans('home.of').' '.trans('plan.commissions').'</h4>';
                        if ($plan->affiliates == 'unlimited')
                        {
                            $result .='<h4>- <b>'.$plan->affiliates.' '.trans('home.affiliates').'</b></h4>';
                        }
                        else
                        {
                            $result .='<h4>- '.trans('home.up_to').' <b>'.$plan->affiliates.' '.trans('home.affiliates').'</b></h4>';
                        }
                        $result .= '
                                        <h4>- '.trans('plan.unlimited_products').'</h4>
                                        <h4>- '.trans('plan.unlimited_sales').'</h4>
                                        <h4>- '.trans('plan.woocommerce_module').'</h4>
                                        <h4>- '.trans('plan.shopify_module').'</h4>';
                        if ($plan->commission == 0)
                        {
                            $result .='<h4>- <b>'.trans('plan.no_commission').'</b></h4>';
                        }
                        else
                        {
                            $result .='<h4>- '.trans('plan.commissions').':  <b>'.$plan->commission.'%</b> '.trans('home.on').' '.trans('plan.every_sale').'</h4>';
                        }
                        $result .=  '</div>
                                    <div class="starter-middel2 rate-outermrgn">
                                        <h2 class="rate-padding">$'.$plan->amount.'/'.trans('myProfile.month').'</h2>
                                    </div>
                                    <a href="'.url('register').'/'.$plan->id.'"><button><h4>'.trans('plan.order_now').'</h4></button></a>
                                </div>
                            </div>
                            ';
                    }
                    else
                    {
                        $result .=
                            '
                            <div class="col-md-4 side-div-margin">
                                <div class="card">
                                    <div class="starter">
                                        <h3 class="name-padding">'.strtoupper($plan->type).'</h3>
                                    </div>
                                    <div class="starter-middel1">
                                        <h4>- '.trans('home.up_to').' <b>'.$plan->levels.' '.trans('level.levels').'</b> '.trans('home.of').' '.trans('plan.commissions').'</h4>';
                        if ($plan->affiliates == 'unlimited')
                        {
                            $result .='<h4>- <b>'.$plan->affiliates.' '.trans('home.affiliates').'</b></h4>';
                        }
                        else
                        {
                            $result .='<h4>- '.trans('home.up_to').' <b>'.$plan->affiliates.' '.trans('home.affiliates').'</b></h4>';
                        }
                        $result .= '
                                        <h4>- '.trans('plan.unlimited_products').'</h4>
                                        <h4>- '.trans('plan.unlimited_sales').'</h4>
                                        <h4>- '.trans('plan.woocommerce_module').'</h4>
                                        <h4>- '.trans('plan.shopify_module').'</h4>';
                        if ($plan->commission == 0)
                        {
                            $result .='<h4>- <b>'.trans('plan.no_commission').'</b></h4>';
                        }
                        else
                        {
                            $result .='<h4>- '.trans('plan.commissions').':  <b>'.$plan->commission.'%</b> '.trans('home.on').' '.trans('plan.every_sale').'</h4>';
                        }
                        $result .=  '</div>
                                    <div class="starter-middel2 rate-outermrgn">
                                        <h2 class="rate-padding">$'.$plan->amount.'/'.trans('myProfile.month').'</h2>
                                    </div>
                                    <a href="'.url('register').'/'.$plan->id.'"><button><h4>'.trans('plan.order_now').'</h4></button></a>
                                </div>
                            </div>
                            ';
                    }
                    $i++;
                }
            }
            else
            {
                $result = "";
            }
        }
        else
        {
            if (plantable::where('term','year')->exists())
            {
                $plans = plantable::where('term','year')->get();
            }
            else
            {
                $plans = 0;
            }
            $i=1;
            if ($plans!="")
            {
                foreach($plans as $plan)
                {
                    if ($i%2 ==0)
                    {
                        $result .=
                            '
                            <div class="col-md-4">
                                <div class="card1">
                                    <div class="starter-bsns">
                                        <h3 class="name-padding1">'.strtoupper($plan->type).'</h3>
                                    </div>
                                    <div class="starter-middel1">
                                        <h4>- '.trans('home.up_to').' <b>'.$plan->levels.' '.trans('level.levels').'</b> '.trans('home.of').' '.trans('plan.commissions').'</h4>';
                        if ($plan->affiliates == 'unlimited')
                        {
                            $result .='<h4>- <b>'.$plan->affiliates.' '.trans('home.affiliates').'</b></h4>';
                        }
                        else
                        {
                            $result .='<h4>- '.trans('home.up_to').' <b>'.$plan->affiliates.' '.trans('home.affiliates').'</b></h4>';
                        }
                        $result .= '
                                        <h4>- '.trans('plan.unlimited_products').'</h4>
                                        <h4>- '.trans('plan.unlimited_sales').'</h4>
                                        <h4>- '.trans('plan.woocommerce_module').'</h4>
                                        <h4>- '.trans('plan.shopify_module').'</h4>';
                        if ($plan->commission == 0)
                        {
                            $result .='<h4>- <b>'.trans('plan.no_plans').'</b></h4>';
                        }
                        else
                        {
                            $result .='<h4>- '.trans('plan.commissions').':  <b>'.$plan->commission.'%</b> '.trans('home.on').' '.trans('plan.every_sale').'</h4>';
                        }
                        $result .=  '</div>
                                    <div class="starter-middel2 rate-outermrgn">
                                        <h2 class="rate-padding">$'.$plan->amount.'/'.trans('myProfile.year').'</h2>
                                    </div>
                                    <a href="'.url('register').'/'.$plan->id.'"><button><h4>'.trans('plan.order_now').'</h4></button></a>
                                </div>
                            </div>
                            ';
                    }
                    else
                    {
                        $result .=
                            '
                            <div class="col-md-4 side-div-margin">
                                <div class="card">
                                    <div class="starter">
                                        <h3 class="name-padding">'.strtoupper($plan->type).'</h3>
                                    </div>
                                    <div class="starter-middel1">
                                        <h4>- '.trans('home.up_to').' <b>'.$plan->levels.' '.trans('level.levels').'</b> '.trans('home.of').' '.trans('plan.commissions').'</h4>';
                        if ($plan->affiliates == 'unlimited')
                        {
                            $result .='<h4>- <b>'.$plan->affiliates.' '.trans('home.affiliates').'</b></h4>';
                        }
                        else
                        {
                            $result .='<h4>- '.trans('home.up_to').' <b>'.$plan->affiliates.' '.trans('home.affiliates').'</b></h4>';
                        }
                        $result .= '
                                        <h4>- '.trans('plan.unlimited_products').'</h4>
                                        <h4>- '.trans('plan.unlimited_sales').'</h4>
                                        <h4>- '.trans('plan.woocommerce_module').'</h4>
                                        <h4>- '.trans('plan.shopify_module').'</h4>';
                        if ($plan->commission == 0)
                        {
                            $result .='<h4>- <b>'.trans('plan.no_commission').'</b></h4>';
                        }
                        else
                        {
                            $result .='<h4>- '.trans('plan.commissions').':  <b>'.$plan->commission.'%</b> '.trans('home.on').' '.trans('plan.every_sale').'</h4>';
                        }
                        $result .=  '</div>
                                    <div class="starter-middel2 rate-outermrgn">
                                        <h2 class="rate-padding">$'.$plan->amount.'/'.trans('myProfile.year').'</h2>
                                    </div>
                                    <a href="'.url('register').'/'.$plan->id.'"><button><h4>'.trans('plan.order_now').'</h4></button></a>
                                </div>
                            </div>
                            ';
                    }
                    $i++;
                }
            }
            else
            {
                $result = "";
            }
        }
        return $result;
    }


    public function purchase_success(Request $request)
    {
        if(!empty($request->header('Apikey')) && !empty($request->affiliate_id) && !empty($request->total))
        {
//            if(!empty($request->Apikey) && !empty($request->affiliate_id) && !empty($request->total))
//            {
            $api_key = $request->header('Apikey');
//                $api_key = $request->Apikey;
            $id      = $request->affiliate_id;
            $orderid = $request->order_id;
            $price = $request->total;
            $name = $request->name;
            $currency = $request->currency;
            $transaction_id = time().rand(1,9876457).'/'.$id.'/'.$price;
            if(affiliate::whereId($id)->exists())
            {
                $invited_user = User::where('affiliate_id', $id)->first();

                $invited_affiliate = affiliate::whereId($id)->first();
                $company = company::whereId($invited_affiliate->company_id)->first();
                $companyUser = User::where('company_id',$company->id)->where('status','1')->first();


                if ($company->apikey == $api_key) //checking the api key same as the company table
                {
//            code to calculate and update the admin share starts here
                    $planTable = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();
                    $plan = plantable::whereId($planTable->planid)->first(); //checking company plan details
                    $max_level = level::where('company_id',$company->id)->count();
                    $commission = (float)$plan->commission; //fetching commission percent
                    $super_admin_commission = ((float)$price * $commission) / 100; //calculating the commission for total amount
                    $super_admin = User::where('status', '0')->first(); //fetching superadmin details
                    $super_admin_revenue = (float)$super_admin->current_revenue;
                    $super_admin_new_revenue = $super_admin_revenue + $super_admin_commission; //adding commission to previous revenue
                    $update_super_Admin['current_revenue'] = $super_admin_new_revenue;
                    User::where('status', '0')->update($update_super_Admin); //updating the super admin details
                    $comission['company_id'] = $company->id;
                    $comission['affiliate_id'] = $id;
                    $comission['planid'] = $plan->id;
                    $comission['price'] = $price;
                    $comission['commission'] = $commission;
                    $comission['amount'] = $super_admin_commission;
                    $comission['transaction_id'] = $transaction_id;
                    $comission['created_at'] = new \DateTime();
                    DB::table('commission')->insert($comission); //adding entry to the commission table
//            code to calculate and update the admin share ends here

//                code to save the sale details starts here
                    $purchase['affiliate_id'] = $id;
                    $purchase['company_id'] = $invited_affiliate->company_id;
                    $purchase['transaction_id'] = $transaction_id;
                    $purchase['date'] = new \DateTime();
                    $purchase['name'] = $name;
                    $purchase['price'] = $price;
                    DB::table('purchase_links')->insert($purchase); //Each time the purchase is completed, sale is saved in purchase links table (for the sales count)
//                code to save the sale details ends here


//        fetching first level as always link shared affiliate will get the level 1 benifit
                    if (level::where('level', 1)->where('company_id', $invited_affiliate->company_id)->exists()) {
                        $level_content = level::where('level', 1)->where('company_id', $invited_affiliate->company_id)->first();
                        $share = $level_content->share_to_team_revenue;
                    } else {
                        $level_content = "";
                        $share = 0;
                    }
//      Total amount to be added to revenue based on level's share
                    $amount_share = ((float)($price) * (float)$share) / 100;
//      if current revenue is null assign 0
                    if ($invited_user->current_revenue == "" || empty($invited_user->current_revenue)) {
                        $invited_user->current_revenue = 0;
                    }
//      adding calculated amount to the old revenue
                    $new_revenue = (float)($invited_user->current_revenue) + $amount_share;
                    $update_revenue['current_revenue'] = $new_revenue;

                    //updating revenue in user table and affiliate table
                    User::whereId($invited_user->id)->update($update_revenue);
                    affiliate::whereId($invited_affiliate->id)->update($update_revenue);

//                code to save the purchase history/revenue history starts here(for this user)
                    $input['transaction_id'] = $transaction_id;
                    $input['company_id'] = $invited_affiliate->company_id;
                    $input['affiliate_id'] = $invited_affiliate->id;
                    $input['amount'] = $amount_share;
                    $input['name'] = $name;
                    $input['created_at'] = new \DateTime();
                    DB::table('purchase_history')->insert($input); //sale details for this user is inserted
//                code to save the purchase history/revenue history ends here(for this user)


//        declaring affiliate id and invitee for further user
                    $affiliate_id = $id;
                    $invitee = $invited_affiliate->invitee;
                    $level = 2;
                    $child_name = $invited_affiliate->name;

                    while ($level <= $max_level) //checking that level can not be more than allowed level as per the plan
                    {
//            taking the details of each level
                        if (level::where('level', $level)->where('company_id', $invited_affiliate->company_id)->exists()) {
                            $level_content = level::where('level', $level)->where('company_id', $invited_affiliate->company_id)->first();
                            $share = $level_content->share_to_team_revenue;
                        } else {
                            $level_content = "";
                            $share = 0;
                        }


//          Total amount to be added to revenue based on level's share
                        $amount_share = ((float)($price) * (float)$share) / 100;

//            fetching the parent user's (person who invited the current affiliate) details
                        $user = User::whereId($invitee)->first();


//          if current revenue is null assign 0
                        if ($user->current_revenue == "" || empty($user->current_revenue)) {
                            $user->current_revenue = 0;
                        }

//          adding calculated amount to the old revenue
                        $new_revenue = (float)($user->current_revenue) + $amount_share;
                        $update_revenue['current_revenue'] = $new_revenue;


                        if ($user->status == '1' ) //if the parent of current affiliate is admin, then stop the interation and give his share
                        {
                            break;
                        }
                        elseif ($user->status == '4')
                        {
                            if ($invited_affiliate->company_id == $user->company_id)
                            {
                                break;
                            }
                            else
                            {
                                $affiliate_id = $user->affiliate_id;
                                $input['amount'] = $amount_share;
                                $input['created_at'] = new \DateTime();
                                $input['name'] = $child_name;
                                $input['affiliate_id'] = $affiliate_id;
                                DB::table('purchase_history')->insert($input);
                                $affiliate = affiliate::whereId($affiliate_id)->first();
//                updating new revenue to user and affiliate table
                                User::whereId($user->id)->update($update_revenue);
                                affiliate::whereId($affiliate->id)->update($update_revenue);
                                $invitee = $affiliate->invitee;
                                $level = $level + 1; //increasing the level count
                                $child_name = $affiliate->name;
                            }
                        }
                        else //if invited/parent of current affiloiate is not admin and its an affiliate, give that affiliate's share based on the level
                        {
                            $affiliate_id = $user->affiliate_id;
                            $input['amount'] = $amount_share;
                            $input['created_at'] = new \DateTime();
                            $input['affiliate_id'] = $affiliate_id;
                            $input['name'] = $child_name;
                            DB::table('purchase_history')->insert($input);
                            $affiliate = affiliate::whereId($affiliate_id)->first();
//                updating new revenue to user and affiliate table
                            User::whereId($user->id)->update($update_revenue);
                            affiliate::whereId($affiliate->id)->update($update_revenue);
                            $invitee = $affiliate->invitee;
                            $level = $level + 1; //increasing the level count
                            $child_name = $affiliate->name;
                        }
                    }
                } else {
                    return "API key is not Valid!";
                }

//            code to calculate the new rank of the afiliate starts here
                $affiliates = affiliate::where('company_id', $company->id)->get();
                foreach ($affiliates as $affiliate) {
                    $this_rank = $affiliate->rankid;
                    $rankid = $this->calculateRank($affiliate->id);
                    if ($this_rank!=$rankid)
                    {
                        $affiliate_update['payout'] = 0;
                    }
                    $affiliate_update['rankid'] = $rankid;
                    affiliate::whereId($affiliate->id)->update($affiliate_update);
                }
            }
//            code to calculate the new rank of the afiliate ends here
        }
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


    public function errorDomain()
    {
        return view('frontEnd.domain');
    }


    public function loginCompany($value)
    {
        if (company::where('name',$value)->exists())
        {
            $login = $value;
            if(Auth::user())
            {
                return redirect('home');
            }
            $company = company::where('name',$value)->first();
            return view('auth.login',compact('company','login'));
        }
        else
        {
            return response()->view('errors.404',[],404);
        }
    }


    public function cookie_duration(Request $request)
    {
        $affiliate = affiliate::whereId($request->affiliate_id)->first();
        if (company::whereId($affiliate->company_id)->where('apikey',$request->apikey)->exists())
        {
            $company = company::whereId($affiliate->company_id)->where('apikey',$request->apikey)->first();
            $cookie_duration = $company->cookie_duration;
        }
        else
        {
            $cookie_duration = 0;
        }
        return $cookie_duration;
    }


    public function changeLanguage($lang)
    {
//        return $value;
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocale', $lang);
        }
        return redirect()->back();
    }


    public function superAdminRegister(Request $request)
    {
        if(User::where('email',$request->email)->exists() || User::where('status','0')->exists())
        {
            Flash::error(trans('auth.user_exists'));
            return redirect()->back()->withInput(Input::all());
        }
        if ($request->password != $request->password_confirmation)
        {
            Flash::error(trans('auth.psw_confirmed'));
            return Redirect()->back()->withInput(Input::all());
        }
        $input = $request->except('_token','password','password_confirmation');
        $input['password'] = bcrypt($request->password);
        $input['status'] = 0;
        User::create($input);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication passed...
            return redirect()->intended('home');
        }
        else
        {
            return redirect('login');
        }
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


    public function terms()
    {
        return view('frontEnd.terms');
    }


}

