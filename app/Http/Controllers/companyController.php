<?php
namespace App\Http\Controllers;
use App\DataTables\companyDataTable;
use App\Http\Requests\CreatecompanyRequest;
use App\Http\Requests\UpdatecompanyRequest;
use App\Models\affiliate;
use App\Models\emailcontent;
use App\Models\linkedin_plans;
use App\Models\level;
use App\Models\payouthistory;
use App\Models\plantable;
use App\Models\rank;
use App\Models\revenuehistory;
use App\Models\salescontent;
use App\Models\weeklyfees;
use App\Repositories\companyRepository;
use App\Http\Controllers\AppBaseController;
use Carbon\Carbon;
use Cartalyst\Stripe\Api\Customers;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\company;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;
require_once public_path('TCPDF-master/examples/tcpdf_include.php');
require_once public_path('TCPDF-master/tcpdf.php');

class MYPDF extends \TCPDF {

    public function Header() {
        // Logo
        $image_file = url('/').'/public/pictures/samy-pdf.jpg';
        $this->Image($image_file, 0, 0, 250, '50', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
    // Page footer
    public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-10);
        $this->SetFont('helvetica', 'I', 8);

        $this->Cell(0, 0, url('/'), 0, 0, 'L');
        $this->Cell(0, 0, 'Tech made easy', 0, 0, 'R');
        $this->Ln();
    }
}
class companyController extends AppBaseController
{
    /** @var  companyRepository */
    private $companyRepository;
    public function __construct(companyRepository $companyRepo)
    {
        $this->middleware('auth');
        $this->companyRepository = $companyRepo;
    }
    /**
     * Display a listing of the company.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        if (Auth::user()->status == '0') {
            $companies = company::get();
            return view('companies.index',compact('companies'));
        }
        else
        {
            return redirect('home');
        }
    }
    /**
     * Show the form for creating a new company.
     *
     * @return Response
     */
    public function create()
    {
        return view('companies.create');
    }
    /**
     * Store a newly created company in storage.
     *
     * @param CreatecompanyRequest $request
     *
     * @return Response
     */
    public function store(CreatecompanyRequest $request)
    {
        $input = $request->except('logo'); //Taking all input values except logo
        if ($request->hasFile('logo')) //If input has logo, upload to server
        {
            $validator=Validator::make($request->all(), [
                'logo' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'logo.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes())
            {
                $photoName = rand(1, 777777777) . time() . '.' . $request->logo->getClientOriginalExtension();
                $mime = $request->logo->getClientOriginalExtension();
                $this->compress($request->logo, public_path('avatars') . '/' . $photoName, 100, $mime);
                $input['logo'] = $photoName;
                $userInput['image'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $company = $this->companyRepository->create($input);
        $id = $company->id;
        $userInput['company_id'] = $id;
        $userInput['name'] = $request->name;
        $userInput['email'] = $request->email;
        $userInput['type'] = "company";
        $userInput['password'] = bcrypt($request->password);
        $userInput['status'] = "user";
        User::create($userInput); //Create Laravel user table entry
        Flash::success(trans('company.saved'));
        return redirect('stripe');
    }
    /**
     * Display the specified company.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = User::where('company_id',$id)->first();
        $company = company::whereId($id)->first();
        $planTable = DB::table('companyAffiliatePlans')->where('company_id',$id)->orderby('id','desc')->first();
        $botPlans = DB::table('bot_plans')->where('company_id',$id)->get();
        if (DB::table('purchase_history')->where('company_id',$id)->exists())
        {
            $revenue = DB::table('purchase_history')->where('company_id',$id)->sum('amount');
        }
        else
        {
            $revenue=0;
        }
        $affiliates = affiliate::where('company_id',$id)->count();
        if (empty($company)) {
            Flash::error('Company not found');
            return redirect(route('companies.index'));
        }

        return view('companies.show',compact('company','plan','revenue','affiliates','planTable','user','botPlans'));
    }
    /**
     * Show the form for editing the specified company.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $company = $this->companyRepository->findWithoutFail($id);
        if (empty($company)) {
            Flash::error('Company not found');
            return redirect(route('companies.index'));
        }
        return view('companies.edit')->with('company', $company);
    }
    /**
     * Update the specified company in storage.
     *
     * @param  int              $id
     * @param UpdatecompanyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatecompanyRequest $request)
    {
        $company = $this->companyRepository->findWithoutFail($id);
        $update = $request->except('_token', 'logo');
        if (empty($company)) {
            Flash::error(trans('company.update_error'));
            return redirect(route('companies.index'));
        }
        if ($request->hasFile('logo')) {
            $validator=Validator::make($request->all(), [
                'logo' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'logo.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes())
            {
                $filepath = public_path('avatars' . '/' . $company->logo);
                $this->UnlinkImage($filepath);
                $photoName = rand(1, 777777777) . time() . '.' . $request->logo->getClientOriginalExtension();
                $mime = $request->logo->getClientOriginalExtension();

                $this->compress($request->logo, public_path('avatars') . '/' . $photoName, 100, $mime);
                $update['logo'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $user_update['profile'] = 1;
        $user_update['name'] = $request->name;
        User::whereId(Auth::user()->id)->update($user_update);
        $company = $this->companyRepository->update($update, $id);
        Flash::success(trans('company.update_success'));
        return redirect()->back();
    }
    /**
     * Remove the specified company from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $company = $this->companyRepository->findWithoutFail($id);
        if (empty($company)) {
            Flash::error('Company not found');
            return redirect(route('companies.index'));
        }
        level::where('company_id',$id)->delete();
        rank::where('company_id',$id)->delete();
        affiliate::where('company_id',$id)->delete();
        revenuehistory::where('company_id',$id)->delete();
        emailcontent::where('company_id',$id)->delete();
        salescontent::where('company_id',$id)->delete();
        weeklyfees::where('company_id',$id)->delete();
        DB::table('stripe_cards')->where('company_id',$id)->delete();
        DB::table('stripepayment')->where('user_id',$id)->delete();
        DB::table('purchase_history')->where('company_id',$id)->delete();
        DB::table('purchase_links')->where('company_id',$id)->delete();
        $this->companyRepository->delete($id);
        Flash::success(trans('company.update_delete'));
        return redirect(route('companies.index'));
    }
    public function editDetails()
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
        $months = array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        );
        sort($countries);
        if (Auth::user()->status == '1')
        {
            $i = 1;
            $id = Auth::user()->company_id;
            $savedCards = DB::table('stripe_cards')->where('company_id',$id)->orderby('status','desc')->get();
            $company = company::whereId($id)->first();
//###################################### Samybot plans //######################################
            if(Auth::user()->samy_bot == 1){
                $samyBotPlans = DB::table('bot_plans')->where('company_id',$id)->get();
            }else{
                $samyBotPlans = "";
            }
//###################################### Affiliate plans //######################################
            if(Auth::user()->samy_affiliate == 1){
                $planTable = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();
                $AffiliatePlans = plantable::whereId($planTable->planid)->first();
                $expirydate = str_replace('/','-',$planTable->plan_end);
                $expiryMonth = date('m',strtotime($expirydate));
            }else{
                $AffiliatePlans = "";
            }
//###################################### LuinkedIn plans //######################################
            if(Auth::user()->samy_linkedIn == 1){
                $LinkedInPlans = linkedin_plans::whereId($company->linkedIn_plan)->first();
            }else{
                $LinkedInPlans = "";
            }
//######################################  Plans Ends //######################################
            if (emailcontent::where('company_id',$id)->exists())
            {
                $smtp = emailcontent::where('company_id',$id)->first();
            }
            else
            {
                $smtp="";
            }
            if (DB::table('stripepayment')->where('user_id',$id)->exists())
            {
                $bills=DB::table('stripepayment')->where('user_id',$id)->where('payment_type','!=','commission')->where('type','1')->get();
            }
            else
            {
                $bills='';
            }
            return view('users.edit',compact('company','samyBotPlans','LinkedInPlans','AffiliatePlans','smtp','countries','savedCards','months','expiryMonth','expirydate','bills','i','smtp','planTable'));
        }
        elseif (Auth::user()->status == '2')
        {
            $aid = Auth::user()->affiliate_id;
            $affliate = affiliate::whereId($aid)->first();
            $cid = $affliate->company_id;
            $company = company::whereId($cid)->first();
            $company_name = $company->name;
            if (payouthistory::where('company_id',$cid)->where('affiliate_id',$aid)->exists())
            {
                $payouts = payouthistory::where('company_id',$cid)->where('affiliate_id',$aid)->get();
            }
            else
            {
                $payouts = "";
            }
            return view('users.affiliate_edit',compact('affliate','company','company_name','countries','payouts'));
        }
        elseif (Auth::user()->status == '4')
        {
            $domain = request()->getHost();
            if ($domain != ''.env('APP_DOMAIN').'')
            {
                $aid = Auth::user()->affiliate_id;
                $affliate = affiliate::whereId($aid)->first();
                $cid = $affliate->company_id;
                $company = company::whereId($cid)->first();
                $company_name = $company->name;
                if (payouthistory::where('company_id',$cid)->where('affiliate_id',$aid)->exists())
                {
                    $payouts = payouthistory::where('company_id',$cid)->where('affiliate_id',$aid)->get();
                }
                else
                {
                    $payouts = "";
                }
                return view('users.affiliate_edit',compact('affliate','company','company_name','countries','payouts'));
            }
            else
            {
                $i = 1;
                $id = Auth::user()->company_id;
                $savedCards = DB::table('stripe_cards')->where('company_id',$id)->orderby('status','desc')->get();
                $company = company::whereId($id)->first();
//###################################### Samybot plans //######################################
                if(Auth::user()->samy_bot == 1)
                {
                    $samyBotPlans = DB::table('bot_plans')->where('company_id',$id)->get();
                }
                else
                {
                    $samyBotPlans = "";
                }
//###################################### Affiliate plans //######################################
                if(Auth::user()->samy_affiliate == 1)
                {
                    $planTable = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();
                    $AffiliatePlans = plantable::whereId($planTable->planid)->first();
                    $expirydate = str_replace('/','-',$planTable->plan_end);
                    $expiryMonth = date('m',strtotime($expirydate));
                }
                else
                {
                    $AffiliatePlans = "";
                }
//###################################### LuinkedIn plans //######################################
                if(Auth::user()->samy_linkedIn == 1)
                {
                    $LinkedInPlans = linkedin_plans::whereId($company->linkedIn_plan)->first();
                }
                else
                {
                    $LinkedInPlans = "";
                }
//######################################  Plans Ends //######################################
                if (emailcontent::where('company_id',$id)->exists())
                {
                    $smtp = emailcontent::where('company_id',$id)->first();
                }
                else
                {
                    $smtp="";
                }
                if (DB::table('stripepayment')->where('user_id',$id)->exists())
                {
                    $bills=DB::table('stripepayment')->where('user_id',$id)->where('payment_type','!=','commission')->get();
                }
                else
                {
                    $bills='';
                }
                return view('users.edit',compact('company','samyBotPlans','LinkedInPlans','AffiliatePlans','smtp','countries','savedCards','months','expiryMonth','expirydate','bills','i','smtp','planTable'));
            }
        }
    }
    public function editSMTPDetails()
    {
        $cid= Auth::user()->company_id;
        $company = company::whereId($cid)->first();
        if (emailcontent::where('company_id',$cid)->exists())
        {
            $smtp = emailcontent::where('company_id',$cid)->first();
        }
        else
        {
            $smtp = "";
        }
        return view('companies.smtp',compact('smtp','company'));
    }
    public function billing()
    {
        $cid= Auth::user()->company_id;
        if (Auth::user()->status == '1' || Auth::user()->status == '4')
        {
            $stripes = DB::table('stripepayment')->where('user_id',$cid)->get();
            return view('companies/bill.index')->with('stripes',$stripes);
        }
        else
        {
            return redirect('home');
        }
    }
    public function smtp(Request $request)
    {
        $cid= Auth::user()->company_id;
        $smtp_update = $request->except('_token');
        $smtp_update['smtp'] = $request->smtp;
        $smtp_update['smtp_user_id'] = $request->smtp_user_id;
        $smtp_update['smtp_password'] = $request->smtp_password;
        if (emailcontent::where('company_id',$cid)->exists())
        {
            emailcontent::where('company_id',$cid)->update($smtp_update);
        }
        else
        {
            $smtp_update['company_id'] = $cid;
            emailcontent::create($smtp_update);
        }
        Session::put('success',trans('company.smtp_update'));
        return redirect()->back();
    }
    public function savedCards()
    {
        $cid= Auth::user()->company_id;
        if (DB::table('stripe_cards')->where('company_id',$cid)->exists())
        {
            $cards = DB::table('stripe_cards')->where('company_id',$cid)->get();
        }
        else
        {
            $cards = "";
        }
        return view('companies/cards.index',compact('cards'));
    }
    public function addCard()
    {
        return view('companies/cards.create');
    }
    public function editCard($id)
    {
        $card = DB::table('stripe_cards')->whereId($id)->first();
        $card_number = \Illuminate\Support\Facades\Crypt::decrypt($card->card_detail);
        $card_cvv = \Illuminate\Support\Facades\Crypt::decrypt($card->card_detail_c);
        $card_month = \Illuminate\Support\Facades\Crypt::decrypt($card->card_detail_m);
        $card_year = \Illuminate\Support\Facades\Crypt::decrypt($card->card_detail_y);
        $card_number_show = "XXXXXXXXXXXX".substr($card_number,-4);
        return view('companies/cards.edit',compact('card','card_number','card_month','card_year','card_cvv','card_number_show'));
    }
    public function storeCard(Request $request)
    {
        $cid= Auth::user()->company_id;
        $company = company::whereId($cid)->first();
        $input['card_no'] = $request->cardnum;
        $input['ccExpiryMonth'] = $request->ccExpiryMonth;
        $input['ccExpiryYear'] = $request->ccExpiryYear;
        $input['cvvNumber'] = $request->cvvNumber;

        $validator = Validator::make($input, [
            'card_no' => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
        ]);
        if ($validator->passes()) {
            $stripe = Stripe::make(env('STRIPE_SECRET'));
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $request->get('cardnum'),
                    'exp_month' => $request->get('ccExpiryMonth'),
                    'exp_year' => $request->get('ccExpiryYear'),
                    'cvc' => $request->get('cvvNumber'),
                ],
            ]);
            $stripe_input['brand'] = $token['card']['brand'];
            $stripe_fingerprint = $token['card']['fingerprint'];
            if (!isset($token['id'])) {
                \Session::put('error', trans('stripe.token_error'));
                return redirect()->back();

            }
            if(empty($company->stripe_id)){
                $customer = $stripe->customers()->create([
                    'email' => $company->email,
                    'description' => 'Stripe Customer with user id -'.$company->user_id,
                ]);
                $customerId = $customer['id'];
                company::whereId($company->id)->update(['stripe_id' => $customerId]);
            }
            else{
                $customer = $stripe->customers()->find($company->stripe_id);
                $customerId = $customer['id'];
            }

            if (DB::table('stripe_cards')->where('company_id', $cid)->exists() == 0) {
                $stripe_input['status'] = 1;
            }
            if (DB::table('stripe_cards')->where('company_id', $cid)->where('fingerprint', $stripe_fingerprint)->exists() == 0) //To avoid the duplicate entry
            {
                $cards = $stripe->cards()->create($customerId, $token['id']);
                $stripe_input['company_id'] = $cid;
                $stripe_input['customerId'] = $customerId;
                $stripe_input['cardNo'] = $cards['id'];
                $stripe_input['fingerprint'] = $stripe_fingerprint;
                $stripe_input['digits'] = 'XXXXXXXXXXXX' . substr($request->cardnum, -4);
                DB::table('stripe_cards')->insert($stripe_input); //Entering the card details
            }
        }
        else
        {
            Flash::error(trans('stripe.required'));
            return redirect()->back();
        }
        Flash::success(trans('card.saved'));
        return redirect()->back();
    }
    public function updateCard($id,Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_no' => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
        ]);
        if ($validator->passes())
        {
            $stripe = Stripe::make(env('STRIPE_SECRET'));
            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $request->get('card_no'),
                        'exp_month' => $request->get('ccExpiryMonth'),
                        'exp_year' => $request->get('ccExpiryYear'),
                        'cvc' => $request->get('cvvNumber'),
                    ],
                ]);
                if (!isset($token['id'])) {
                    \Session::put('error', trans('stripe.token_error'));
                    return redirect()->back();
                }
                elseif(isset($token['id']))
                {
                    $input_cards['card_detail'] = \Illuminate\Support\Facades\Crypt::encrypt($request->card_no);
                    $input_cards['card_detail_c'] = \Illuminate\Support\Facades\Crypt::encrypt($request->cvvNumber);
                    $input_cards['card_detail_y'] = \Illuminate\Support\Facades\Crypt::encrypt($request->ccExpiryYear);
                    $input_cards['card_detail_m'] = \Illuminate\Support\Facades\Crypt::encrypt($request->ccExpiryMonth);
                    $input_cards['company_id'] = $request->company_id;
                    DB::table('stripe_cards')->whereId($id)->update($input_cards);
                    Session::put('success',trans('company.card_save'));
                    return redirect('savedCards');
                }
            }
            catch (Exception $e) {
                \Session::put('error', $e->getMessage());
                return redirect()->back();
            } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
                \Session::put('error', $e->getMessage());
                return redirect()->back();
            } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                \Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }
        else {
            \Session::put('error', trans('stripe.required'));
            return redirect('savedCards');
        }
    }
    public function destroyCard($id)
    {
        $card = DB::table('stripe_cards')->whereId($id)->where('status','!=',1)->first();
        $company = company::whereId($card->company_id)->first();
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        $stripe->cards()->delete($company->stripe_id,$card->cardNo);
        DB::table('stripe_cards')->where('status','!=',1)->whereId($id)->delete();
        Session::put('success',trans('company.card_delete'));
        return redirect()->back();
    }
    public function activateCard($id)
    {
        DB::table('stripe_cards')->where('status',1)->update(['status'=>0]);
        DB::table('stripe_cards')->whereId($id)->update(['status'=>1]);
        Session::put('success',trans('company.card_update'));
        return redirect('savedCards');
    }
    public function todayStats($value)
    {
        $cid = Auth::user()->company_id;
        if ($value == 'one')
        {
            $total_affiliates = affiliate::where('company_id', $cid)->whereDate('created_at', Carbon::today())->count();
            $sales_count = DB::table('purchase_links')->where('company_id', $cid)->whereDate('date',Carbon::today())->count();
            if (payouthistory::where('company_id',$cid)->whereDate('created_at', Carbon::today())->exists())
            {
                $payouts = payouthistory::where('company_id',$cid)->whereDate('created_at', Carbon::today())->get();
                $total_payout = 0;
                foreach ($payouts as $payout)
                {
                    $total_payout += $payout->amount;
                }
                $total_payout = number_format($total_payout);
            }
            else
            {
                $total_payout = 0;
            }
            if (DB::table('purchase_history')->where('company_id',$cid)->whereDate('created_at', Carbon::today())->exists())
            {
                $revenues = DB::table('purchase_history')->where('company_id',$cid)->whereDate('created_at', Carbon::today())->get();
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


        }
        elseif ($value == 'two')
        {
            $lastDate = Carbon::now()->startOfMonth();
            $total_affiliates2 = affiliate::where('company_id', $cid)->whereDate('created_at','>=',$lastDate)->get();
            $total_affiliates = 0;
            foreach($total_affiliates2 as $totalAff)
            {
                $AffUser = User::where('affiliate_id',$totalAff->id)->first();
                if($AffUser->status == '4')
                {
                    $affCompany = company::whereId($AffUser->company_id)->first();
                    if (DB::table('bot_plans')->where('company_id',$affCompany->id)->where('payment_status',1)->exists())
                    {
                        $total_affiliates++;
                    }
                }
                else
                {
                    $total_affiliates++;
                }
            }
            $sales_count = DB::table('purchase_links')->where('company_id', $cid)->whereDate('date','>=',$lastDate)->count();
            if (payouthistory::where('company_id',$cid)->whereDate('created_at','>=',$lastDate)->exists())
            {
                $payouts = payouthistory::where('company_id',$cid)->whereDate('created_at','>=',$lastDate)->get();
                $total_payout = 0;
                foreach ($payouts as $payout)
                {
                    $total_payout += $payout->amount;
                }
                $total_payout = number_format($total_payout);
            }
            else
            {
                $total_payout = 0;
            }
            if (DB::table('purchase_history')->where('company_id',$cid)->whereDate('created_at','>=',$lastDate)->exists())
            {
                $revenues = DB::table('purchase_history')->where('company_id',$cid)->whereDate('created_at','>=',$lastDate)->get();
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


        }
        else
        {
            $total_affiliates2 = affiliate::where('company_id', $cid)->get();
            $total_affiliates = 0;
            foreach($total_affiliates2 as $totalAff)
            {
                $AffUser = User::where('affiliate_id',$totalAff->id)->first();
                if($AffUser->status == '4')
                {
                    $affCompany = company::whereId($AffUser->company_id)->first();
                    if (DB::table('bot_plans')->where('company_id',$affCompany->id)->where('payment_status',1)->exists())
                    {
                        $total_affiliates++;
                    }
                }
                else
                {
                    $total_affiliates++;
                }
            }
            $sales_count = DB::table('purchase_links')->where('company_id', $cid)->count();
            if (payouthistory::where('company_id', $cid)->exists())
            {
                $payouts = payouthistory::where('company_id',$cid)->get();
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
            if (DB::table('purchase_history')->where('company_id',$cid)->exists())
            {
                $revenues = DB::table('purchase_history')->where('company_id',$cid)->get();
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
        }
        $result = '';
        $result .= '
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="col-md-3 col-xs-12 col-sm-6">
                    <p class="stats_section2">'.$total_affiliates.'</p>
                    <p class="section2_headings">Affiliates</p>
                </div>
                <div class="col-md-3 col-xs-12  col-sm-6">
                    <p class="stats_section2">'.$sales_count.'</p>
                    <p class="section2_headings">Sales</p>
                </div>
                <div class="col-md-3 col-xs-12  col-sm-6">
                    <p class="section2_payment">$'.$total_revenue.'</p>
                    <p class="section2_headings">Revenue</p>
                </div>
                <div class="col-md-3 col-xs-12  col-sm-6">
                    <p class="section2_payment">$'.$total_payout.'</p>
                    <p class="section2_headings">Payouts</p>
                </div>
            </div>
        </div>
        ';
        return $result;
    }

    public function autorenew($id,$value)
    {
        $update['auto_renewal'] = $value;
        company::where('id',$id)->update($update);
    }

    public function pendingSamyAffiliateCompanies()
    {
        $companies = DB::table('companyAffiliatePlans')->where('payment','!=','1')->get();
        return view('unpaid.affiliate.unpaidAfiiliate',compact('companies'));
    }

    public function pendingSamybotCompanies()
    {
        $companies = DB::table('bot_plans')->where('payment_status','!=','1')->get();
        return view('unpaid.bot.unpaidbot',compact('companies'));
    }

    public function pendingSamyLinkedinCompanies()
    {
        $users = User::where('status','1')->where('linkedIn_payment',0)->where('samy_linkedIn',1)->get();
        return view('unpaid.linkedIn.unpaidLinkedin',compact('users'));
    }
    public function showAffiliateCompany($id)
    {
        $user = User::where('company_id',$id)->first();
        $company = company::whereId($id)->first();
        $affiliates = affiliate::where('company_id',$company->id)->count();
        return view('unpaid.affiliate.showAffiliate',compact('user','company','affiliates'));
    }
    public function showBotCompany($id)
    {
        $user = User::where('company_id',$id)->first();
        $company = company::whereId($id)->first();
        $affiliates = affiliate::where('company_id',$company->id)->count();
        return view('unpaid.bot.showBot',compact('user','company','affiliates'));
    }
    public function checkDomain($value,$id)
    {
        $value = str_replace('QWERTY','/',$value);
        if (company::where('id','!=',$id)->where('domain_name',$value)->orWhere('actual_domain',$value)->exists())
        {
            return "fail";
        }
        else
        {
            return "success";
        }
    }
    public function checkcompanyName($value)
    {
        if (company::where('name', $value)->where('id','!=',Auth::user()->company_id)->exists())
        {
            return "fail";
        }
        else
        {
            return "success";
        }
    }
    public function invoice($billid)
    {
        $bill = DB::table('stripepayment')->whereId($billid)->first();
        $plan = plantable::whereId($bill->planid)->first();
        $company = company::whereId($bill->user_id)->first();
        $invoice_no = time().$billid;
        $amount = (float)$plan->amount;
        $date = date('m/d/Y',strtotime($bill->date));
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle($bill->orderid);
        $pdf->SetSubject('Invoice');
        $pdf->SetKeywords('PDF,Invoice');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set default font subsetting mode
        $pdf->setFontSubsetting(true);
//        $pdf->setPrintHeader(false);
//        $pdf->setPrintFooter(false);
// helvetica or times to reduce file size.
        $pdf->SetFont('arial', '', 12, '', true);
// Add a page
// This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
// set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $html = <<<EOD
            <table style="width: 100%">
                <tr>
                    <td style="font-weight: bolder;font-size: 20px;"><b>SAMY Technologies inc.</b></td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">301 E Pikes Peak Avenue</td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">Colorado Springs, CO 80903</td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">United States</td>
                </tr>
                <tr style="height: 20px">
                <td></td>
                </tr>
                <tr >
                    <td style="width: 50%;font-size: 60px;font-style: normal;"><b>Invoice</b></td>
                </tr>
                <br/>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">#$bill->orderid</td>
                </tr>
                <br/>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;font-size: 18px;"><b>Prepared for</b></td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">$company->fname $company->lname</td>
                </tr>
                <tr>
                    <td style="width: 100%;font-weight: lighter;font-style: normal;">$company->address</td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">$company->city  $company->state  $company->zip</td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">$company->country</td>
                    <td style="width: 50%;font-weight: lighter;text-align: right;font-style: normal;">Paid $date</td>
                </tr>
            </table>
            <br/> <br/> <br/>
            <table style="width: 100%">
                <thead>
                    <tr style="height: 40px;line-height: 40px;background-color: #e73247">
                        <th style="width: 40%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white"><b>ITEM</b></th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Price</th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Qty</th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 30px;line-height: 30px;">
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 40%;">Samy Affiliate $plan->type</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$$amount</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">1</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$$amount</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="width: 100%;background-color: #f3f3f3;text-align: right;font-size: 24px"><b>$$amount</b></td>
                    </tr>
                    <br/><br/>
                    <tr style="height: 40px;line-height: 40px">
                        <td colspan="4" style="font-size: 20px;">Thank you for your business!</td>
                    </tr>
                </tbody>
            </table>
EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output($bill->orderid.'.pdf', 'D');
    }

    public function paypalCredentials()
    {
        $cid= Auth::user()->company_id;
        if (DB::table('paypal_credential')->where('company_id',$cid)->exists())
        {
            $paypal = DB::table('paypal_credential')->where('company_id',$cid)->first();
        }
        else
        {
            $paypal = "";
        }
        return view('companies.cards.paypalField',compact('paypal'));
    }
    public function SavepaypalCredentials(Request $request)
    {
        $input['company_id'] = $request->company_id;
        if ($request->payout == 'man')
        {
            $input['man'] = 1;
            $input['paypal'] = 0;
        }
        else
        {
            $input['man'] = 0;
            $input['paypal'] = 1;
        }
        if(DB::table('payout_type')->where('company_id',$request->company_id)->exists())
        {
            DB::table('payout_type')->where('company_id',$request->company_id)->update($input);
        }
        else
        {
            DB::table('payout_type')->insert($input);
        }

        $update = $request->except('_token','payout');

        if (DB::table('paypal_credential')->where('company_id',$request->company_id)->exists())
        {
            DB::table('paypal_credential')->where('company_id',$request->company_id)->update($update);
        }
        else
        {
            DB::table('paypal_credential')->insert($update);
        }
        Flash::success(trans('payout.method_saved'));
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
    function UnlinkImage($filepath)
    {
        $old_image = $filepath;
        if (file_exists($old_image)) {
            @unlink($old_image);
        }
    }
}
