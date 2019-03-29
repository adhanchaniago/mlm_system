<?php
namespace App\Http\Controllers;
use App\DataTables\affilateDataTable;
use App\Http\Requests\CreateaffiliateRequest;
use App\Http\Requests\UpdateaffiliateRequest;
use App\Models\company;
use App\Models\emailcontent;
use App\Models\level;
use App\Models\payouthistory;
use App\Models\plantable;
use App\Models\rank;
use App\Repositories\affiliateRepository;
use App\Http\Controllers\AppBaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\affiliate;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Flash;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\User;
require_once public_path('TCPDF-master/examples/tcpdf_include.php');
require_once public_path('TCPDF-master/tcpdf.php');

class MYPDF extends \TCPDF {
    // Page footer
    public function Footer() {
        // Position at 25 mm from bottom
//        $this->Ln();
        // Page number
//        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
//        $this->Ln();
    }
}

class affiliateController extends AppBaseController
{
    /** @var  affiliateRepository */
    private $affiliateRepository;
    public function __construct(affiliateRepository $affiliateRepo)
    {
        $this->middleware('auth');
        $this->affiliateRepository = $affiliateRepo;
    }
    /**
     * Display a listing of the affiliate.
     * @param Request $request
     * @return Response
     */
    public function index(affilateDataTable $affilateDataTable)
    {
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        if (Auth::user()->status == '1' || Auth::user()->status == '4') {
            $companyId = Auth::user()->company_id;
            $company = company::whereId($companyId)->first();
            $plansTable = DB::table('companyAffiliatePlans')->where('company_id',$companyId)->orderby('id','desc')->first();
            if ($plansTable->payment == 0) {
                return redirect('stripe');
            } elseif (Auth::user()->activated == 0) {
                return redirect('confirmEmail');
            } elseif ($company->affiliate_disabled == 1) {
                return view('frontEnd.disabled');
            } elseif (Auth::user()->profile == 0) {
                return redirect('myProfile');
            }

            if (affiliate::where('company_id', $companyId)->exists()) {
                $affiliates = affiliate::where('company_id', $companyId)->get();
            }
            $ranks = rank::where('company_id', $companyId)->orderby('id')->get();

            $current_affiliates = affiliate::where('company_id', $companyId)->count();
            $plan = plantable::whereId($plansTable->planid)->first();
            $max_levels = $plan->levels;
            $max_affiliates = $plan->affiliates;
            $level = 1;

            return $affilateDataTable->render('affiliates.index', compact('level', 'affiliates', 'ranks', 'max_levels', 'max_affiliates', 'current_affiliates'));
        }
        else
        {
            return redirect('home');
        }

    }
    /**
     * Show the form for creating a new affiliate.
     *
     * @return Response
     */
    public function create()
    {
        return view('affiliates.create');
    }
    /**
     * Store a newly created affiliate in storage.
     *
     * @param CreateaffiliateRequest $request
     *
     * @return Response
     */
    public function store(CreateaffiliateRequest $request)
    {
        $input = $request->all('photo'); //storing all input values except photo

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
                $input['photo'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $affliate = $this->affiliateRepository->create($input); //store appliates
        $id = $affliate->id;
        $userInput['affiliate_id'] = $id;
        $userInput['name'] = $request->name;
        $userInput['email'] = $request->email;
        $userInput['type'] = "affliate";
        $userInput['password'] = bcrypt($request->password);
        $userInput['status'] = "user";
        User::create($userInput); //creating affliates to laravel user table
        Flash::success(trans('affiliate.saved'));
        return redirect('login');
    }
    /**
     * Display the specified affiliate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $affiliate = $this->affiliateRepository->findWithoutFail($id);
        if (empty($affiliate)) {
            Flash::error(trans('affiliate.error'));
            return redirect(route('affiliates.index'));
        }
        return view('affiliates.show')->with('affiliate', $affiliate);
    }
    /**
     * Show the form for editing the specified affiliate.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $affiliate = $this->affiliateRepository->findWithoutFail($id);
        if (empty($affiliate)) {
            Flash::error(trans('affiliate.error'));
            return redirect(route('affiliate.index'));
        }
        return view('affiliates.edit')->with('affiliate', $affiliate);
    }
    /**
     * Update the specified affiliate in storage.
     *
     * @param  int              $id
     * @param UpdateaffiliateRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateaffiliateRequest $request){
        $affiliate = $this->affiliateRepository->findWithoutFail($id);
        $update = $request->except('_token','photo');
        if (empty($affiliate)) {
            Flash::error(trans('affiliate.error'));
            return redirect(route('affiliates.index'));
        }
        if ($request->hasFile('photo'))
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
                $filepath = public_path('avatars' . '/' . $affiliate->photo);
                $this->UnlinkImage($filepath);
                $photoName = rand(1, 777777777) . time() . '.' . $request->photo->getClientOriginalExtension();
                $mime = $request->photo->getClientOriginalExtension();

                $this->compress($request->photo, public_path('avatars') . '/' . $photoName, 100, $mime);
                $update['photo'] = $photoName;
                $user_update['photo'] = 1;
                User::whereEmail($affiliate->email)->update($user_update);
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $affiliate = $this->affiliateRepository->update($update, $id);
        Flash::success(trans('affiliate.update'));
        return redirect(route('affiliates.index'));
    }
    public function updateUser($id, UpdateaffiliateRequest $request){
        $affiliate = $this->affiliateRepository->findWithoutFail($id);
        $update = $request->except('_token','photo','fname','lname');
        if (empty($affiliate)) {
            Flash::error(trans('affiliate.error'));
            return redirect(url('home'));
        }
        if ($request->hasFile('photo'))
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
                $filepath = public_path('avatars' . '/' . $affiliate->photo);
                $this->UnlinkImage($filepath);
                $photoName = rand(1, 777777777) . time() . '.' . $request->photo->getClientOriginalExtension();
                $mime = $request->photo->getClientOriginalExtension();
                $this->compress($request->photo, public_path('avatars') . '/' . $photoName, 100, $mime);
                $update['photo'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $update['name'] = $request->fname.' '.$request->lname;
        $update['fname'] = $request->fname;
        $update['lname'] = $request->lname;
        $affiliate = $this->affiliateRepository->update($update, $id);
        $user_update['profile'] = 1;
        User::whereId(Auth::user()->id)->update($user_update);
        Flash::success(trans('affiliate.update'));
        return redirect("home");
    }
    /**
     * Remove the specified affiliate from storage.
     *
     * @param  int $id
     *
     * @return Response
     */



    public function destroy($id){
        $affiliate = $this->affiliateRepository->findWithoutFail($id);
        if (empty($affiliate)) {
            Flash::error(trans('affiliate.error'));
            return redirect(route('affiliates.index'));
        }
        $user = User::where('affiliate_id',$id)->first();
        affiliate::where('invitee',$user->id)->delete();
        DB::table('purchase_links')->where('affiliate_id',$id)->delete();
        $this->affiliateRepository->delete($id);
        Flash::success(trans('affiliate.delete'));
        return redirect(route('affiliates.index'));
    }
    public function checkMail($email)
    {
        if (User::whereEmail($email)->exists()) {
            return "failed";
        }

    }
    public function inviteEmail(Request $request){
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['invitee'] = $request->invitee;
        $user = User::whereId($request->invitee)->first();
        if (Auth::user()->status == '1')
        {
            $cid = Auth::user()->company_id;
            $company = company::whereId($cid)->first();
            if (emailcontent::where('company_id',$cid)->exists())
            {
                $email_content = emailcontent::where('company_id',$cid)->first();
                if($email_content->smtp == '' || empty($email_content->smtp) || $email_content->smtp_user_id == '' || empty($email_content->smtp_user_id) || $email_content->smtp_password == '' || empty($email_content->smtp_password))
                {
                    Flash::error(trans('affiliate.smtp_not_found'));
                    return redirect()->back();
                }
                $data['affiliate_text'] = $email_content->new_affiliate_text;
                $data['company'] = $company->name;
            }
            else
            {
                Flash::error(trans('affiliate.smtp_not_found'));
                return redirect()->back();
            }
            if (DB::table('paypal_credential')->where('company_id',$cid)->exists() == 0 && Auth::user()->special_type != 1)
            {
                Flash::error(trans('affiliate.paypal_not_found'));
                return redirect()->back();
            }
            $rank_count = rank::where('company_id',$cid)->count();
            $level_count = level::where('company_id',$cid)->count();
            $data['enc_company'] = encrypt($user->company_id);
            $data['enc_invitee'] = encrypt($request->invitee);
            $data['enc_email'] = encrypt($request->email);
            $data['special'] = 0;
        }
        elseif (Auth::user()->status == '2')
        {
            $affiliate = affiliate::whereId($user->affiliate_id)->first();
            $company = company::whereId($affiliate->company_id)->first();
            if (emailcontent::where('company_id',$company->id)->exists())
            {
                $email_content = emailcontent::where('company_id',$company->id)->first();
                if($email_content->smtp == '' || empty($email_content->smtp) || $email_content->smtp_user_id == '' || empty($email_content->smtp_user_id) || $email_content->smtp_password == '' || empty($email_content->smtp_password))
                {
                    Flash::error(trans('home.smtp_not_found_admin'));
                    return redirect()->back();
                }
                $data['affiliate_text'] = $email_content->new_affiliate_text;
            }
            else
            {
                Flash::error(trans('home.smtp_not_found_admin'));
                return redirect()->back();
            }
            $rank_count = rank::where('company_id',$company->id)->count();
            $level_count = level::where('company_id',$company->id)->count();
            $data['enc_company'] = encrypt($company->id);
            $data['enc_invitee'] = encrypt($request->invitee);
            $data['enc_email'] = encrypt($request->email);
            $data['company'] = $company->name;
            $data['special'] = 0;
        }
        elseif(Auth::user()->status == '4')
        {
            $domain = request()->getHost();
            if ($domain != ''.env('APP_DOMAIN').'')
            {
                $data['special'] = 1;
                $affiliateId = Auth::user()->affiliate_id;

                $affiliate = affiliate::whereId($affiliateId)->first();
                $company = company::whereId($affiliate->company_id)->first();
                if (emailcontent::where('company_id',$affiliate->company_id)->exists())
                {
                    $email_content = emailcontent::where('company_id',$affiliate->company_id)->first();
                    if($email_content->smtp == '' || empty($email_content->smtp) || $email_content->smtp_user_id == '' || empty($email_content->smtp_user_id) || $email_content->smtp_password == '' || empty($email_content->smtp_password))
                    {
                        Flash::error(trans('home.smtp_not_found_admin'));
                        return redirect()->back();
                    }
                    $data['affiliate_text'] = $email_content->new_affiliate_text;
                }
                else
                {
                    Flash::error(trans('home.smtp_not_found_admin'));
                    return redirect()->back();
                }
                $rank_count = rank::where('company_id',$affiliate->company_id)->count();
                $level_count = level::where('company_id',$affiliate->company_id)->count();
                $data['enc_company'] = encrypt($affiliate->company_id);
                $data['enc_invitee'] = encrypt($request->invitee);
                $data['enc_email'] = encrypt($request->email);
                $data['company'] = $company->name;
            }
            else
            {
                $data['special'] = 0;
                $companyId = Auth::user()->company_id;

                $company = company::whereId($companyId)->first();
                if (emailcontent::where('company_id',$companyId)->exists())
                {
                    $email_content = emailcontent::where('company_id',$companyId)->first();
                    if($email_content->smtp == '' || empty($email_content->smtp) || $email_content->smtp_user_id == '' || empty($email_content->smtp_user_id) || $email_content->smtp_password == '' || empty($email_content->smtp_password))
                    {
                        Flash::error(trans('affiliate.smtp_not_found'));
                        return redirect()->back();
                    }
                    $data['affiliate_text'] = $email_content->new_affiliate_text;
                    $data['company'] = $company->name;
                }
                else
                {
                    Flash::error(trans('affiliate.smtp_not_found'));
                    return redirect()->back();
                }
                if (DB::table('paypal_credential')->where('company_id',$companyId)->exists() == 0 && Auth::user()->special_type != 1)
                {
                    Flash::error(trans('affiliate.paypal_not_found'));
                    return redirect()->back();
                }
                $rank_count = rank::where('company_id',$companyId)->count();
                $level_count = level::where('company_id',$companyId)->count();
                $data['enc_company'] = encrypt($companyId);
                $data['enc_invitee'] = encrypt($request->invitee);
                $data['enc_email'] = encrypt($request->email);
            }
        }

        if ($rank_count > 0 && $level_count >0)
        {
            if (User::whereEmail($request->email)->exists() == 0) {

                $from= array('address' => $email_content->smtp_user_id, 'name' => $company->fname.' '.$company->lname);
                $username = $email_content->smtp_user_id;
                $password =  $email_content->smtp_password;
                $host =  $email_content->smtp;

                Config::set('mail.from', $from);
                Config::set('mail.username', $username);
                Config::set('mail.password', $password);
                Config::set('mail.host', $host);
//                try {
                Mail::send('email.invitation', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'], $data['name'])->subject(trans('mail.welcome_to').$data['company'].'!');
                });
                Flash::success(trans('affiliate.invite_sent'));
//                }
//                catch (\Swift_TransportException $ex) {
//                    Flash::error(trans('home.smtp_credential_wrong'));
//                    return redirect()->back();
//                }
            } else {
                Flash::error(trans('affiliate.invite_failed'));
            }
        }
        else
        {
            if ($rank_count == 0 && $level_count == 0)
            {
                Flash::error(trans('affiliate.invite_failed_rank_level'));
            }
            elseif ($rank_count == 0)
            {
                Flash::error(trans('affiliate.invite_failed_rank'));
            }
            elseif ($level_count == 0 && $level_count < 12)
            {
                Flash::error(trans('affiliate.invite_failed_level'));
            }
        }
        return redirect()->back();
    }
    public function purchaseLink(Request $request)
    {

        $affiliateId= Auth::user()->affiliate_id;

        $array['email'] = $request->email;
        $array['link'] = $request->link;
        $array['type'] = 1;
        $affiliate = affiliate::whereId($affiliateId)->first();
        $company = company::whereId($affiliate->company_id)->first();
        if (emailcontent::where('company_id',$company->id)->exists())
        {
            $email_content = emailcontent::where('company_id',$company->id)->first();
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
            else
            {
                Flash::error(trans('home.smtp_not_found_admin'));
                return redirect()->back();
            }
        }
        else
        {
            Flash::error(trans('home.smtp_not_found_admin'));
            return redirect()->back();
        }
        try{
            Mail::send('email.purchase', ['array' => $array], function ($message) use($array)
            {
                $message->to($array['email'],'User')->subject(trans('mail.purchase_link'));
            });
        }
        catch (\Swift_TransportException $ex) {
            Flash::error(trans('home.smtp_credential_wrong'));
            return redirect()->back();
        }
        Flash::success(trans('home.purchase_sent'));
        return redirect()->back();
    }
    public function samyBotLink(Request $request)
    {
        $array['email'] = $request->email;
        $array['link'] = $request->link;
        $array['type'] = 2;
        $affiliate = affiliate::whereId(Auth::user()->affiliate_id)->first();
        $company = company::whereId($affiliate->company_id)->first();
        if (emailcontent::where('company_id',$company->id)->exists())
        {
            $email_content = emailcontent::where('company_id',$company->id)->first();
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
            else
            {
                Flash::error(trans('home.smtp_not_found_admin'));
                return redirect()->back();
            }
        }
        else
        {
            Flash::error(trans('home.smtp_not_found_admin'));
            return redirect()->back();
        }
        try {
            Mail::send('email.purchase', ['array' => $array], function ($message) use ($array) {
                $message->to($array['email'], 'User')->subject(trans('mail.samybot_purchase_link'));
            });
            Flash::success(trans('home.purchaseBot_sent'));
        }
        catch (\Swift_TransportException $ex) {
            Flash::error(trans('home.smtp_credential_wrong'));
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function purchaseLinksuccess($id,$price)
    {
//        decrypting affiliate id and price
        $link = asset('purchase').'/'.$id.'/'.$price;
        $id = Crypt::decrypt($id);
        $price = Crypt::decrypt($price);
        $transaction_id = time().rand(1,9876457).'/'.$id.'/'.$price;
        $product_id = 1;
        $input['product_id'] = $product_id;
        $input['transaction_id'] = $transaction_id;

        $invited_user = User::where('affiliate_id',$id)->first();
        $invited_affiliate = affiliate::whereId( $id )->first();

        $company = company::whereId($invited_affiliate->company_id)->first();

        $plan = plantable::whereId($company->planid)->first();
        $max_level = $plan->levels;
        $commission = (float)$plan->commission;

        $super_admin_commission = ((float)$price*$commission)/100;
        $super_admin = User::where('status','0')->first();
        $super_admin_revenue = (float)$super_admin->current_revenue;
        $super_admin_new_revenue = $super_admin_revenue+$super_admin_commission;
        $update_super_Admin['current_revenue'] = $super_admin_new_revenue;
        User::where('status','0')->update($update_super_Admin);
        $comission['company_id'] = $company->id;
        $comission['affiliate_id'] = $id;
        $comission['planid'] = $plan->id;
        $comission['amount'] = $super_admin_commission;
        $comission['link'] = $link;
        $comission['transaction_id'] = $transaction_id;
        $comission['created_at'] = new \DateTime();
        DB::table('commission')->insert($comission);

        $purchase['affiliate_id'] = $invited_user->affiliate_id;
        $purchase['company_id'] = $invited_affiliate->company_id;


        $purchase['transaction_id'] = $transaction_id;
        $purchase['link']           = $link;
        $purchase['date']           = new \DateTime();
        $purchase['price']          = $price;
        $purchase['product_id']     = $product_id;
        DB::table( 'purchase_links' )->insert( $purchase );


//        taking details of affiliate who sent the link
        $input['company_id'] = $invited_affiliate->company_id;

        //fetching the details of this affiliate from user table

//        fetching first level as always link shared affiliate will get the level 1 benifit
        if (level::where( 'level', 1 )->where( 'company_id', $invited_affiliate->company_id )->exists())
        {
            $level_content = level::where( 'level', 1 )->where( 'company_id', $invited_affiliate->company_id )->first();
            $share         = $level_content->share_to_team_revenue ;
        }
        else
        {
            $level_content="";
            $share         = 0;
        }

//      Total amount to be added to revenue based on level's share
        $amount_share = ( (float) ( $price ) * (float) $share ) / 100;


//      if current revenue is null assign 0
        if ( $invited_user->current_revenue == "" || empty( $invited_user->current_revenue ) ) {
            $invited_user->current_revenue = 0;
        }

//      adding calculated amount to the old revenue
        $new_revenue                       = (float) ( $invited_user->current_revenue ) + $amount_share;
        $update_revenue['current_revenue'] = $new_revenue;

        //updating revenue in user table and affiliate table
        User::whereId( $invited_user->id )->update( $update_revenue );
        affiliate::whereId( $invited_affiliate->id )->update( $update_revenue );
        $input['affiliate_id'] = $invited_affiliate->id;
        $input['amount']       = $amount_share;
        $input['created_at']   = new \DateTime();
        DB::table( 'purchase_history' )->insert( $input );

        $updated_affiliate         = affiliate::whereId( $invited_affiliate->id )->first();
        $updated_affiliate_revenue = (float) $updated_affiliate->current_revenue;

//        declaring affiliate id and invitee for further user
        $affiliate_id = $id;
        $invitee      = $invited_affiliate->invitee;
        $level        = 2;
        while ( $level <= $max_level ) //checking that level can not be more than plan level admin is not a level, so to exclude the admin
        {
//            taking the details of each level
            if (level::where( 'level', $level )->where( 'company_id', $invited_affiliate->company_id )->exists())
            {
                $level_content = level::where( 'level', $level )->where( 'company_id', $invited_affiliate->company_id )->first();
                $share         = $level_content->share_to_team_revenue ;
            }
            else
            {
                $level_content="";
                $share         = 0;
            }


//          Total amount to be added to revenue based on level's share
            $amount_share = ( (float) ( $price ) * (float) $share ) / 100;

//            fetching the parent user's (person who invited the current affiliate) details
            $user = User::whereId( $invitee )->first();


//          if current revenue is null assign 0
            if ( $user->current_revenue == "" || empty( $user->current_revenue ) ) {
                $user->current_revenue = 0;
            }

//          adding calculated amount to the old revenue
            $new_revenue                       = (float) ( $user->current_revenue ) + $amount_share;
            $update_revenue['current_revenue'] = $new_revenue;


            if ( $user->status == '1' ) //if the parent of current affiliate is admin, then stop the interation and give his share
            {
//                    User::whereId( $user->id )->update( $update_revenue ); //updating new revenue to user table
                break;
            }
            else //if invited/parent of current affiloiate is not admin and its an affiliate, give that affiliate's share based on the level
            {
                $affiliate_id          = $user->affiliate_id;
                $input['amount']       = $amount_share;
                $input['created_at']   = new \DateTime();
                $input['affiliate_id'] = $affiliate_id;
                DB::table( 'purchase_history' )->insert( $input );
                $affiliate = affiliate::whereId( $affiliate_id )->first();
//                updating new revenue to user and affiliate table
                User::whereId( $user->id )->update( $update_revenue );
                affiliate::whereId( $affiliate->id )->update( $update_revenue );



                $invitee = $affiliate->invitee;

                $level = $level + 1; //increasing the level count
                $affiliates = affiliate::where('company_id',$company->id)->get();
                foreach($affiliates as $affiliate)
                {
                    $rankid = $this->calculateRank($affiliate->id);
                    $u_a['rankid'] = $rankid;
                    affiliate::whereId($affiliate->id)->update($u_a);
                }
            }
        }
        echo "done Purchase";
    }
    public function purchase_success(Request $request)
    {
//        decrypting affiliate id and price
        $id = $request->affiliate_id;
        $orderid = $request->order_id;
        $price = $request->total;
        $currency = $request->currency;

        $transaction_id = time().rand(1,9876457).'/'.$id.'/'.$price;
        $product_id = 1;
        $input['product_id'] = $product_id;
        $input['transaction_id'] = $transaction_id;

        $invited_user = User::where('affiliate_id',$id)->first();
        $invited_affiliate = affiliate::whereId( $id )->first();

        $company = company::whereId($invited_affiliate->company_id)->first();

        $plan = plantable::whereId($company->planid)->first();
        $max_level = $plan->levels;
        $commission = (float)$plan->commission;

        $super_admin_commission = ((float)$price*$commission)/100;
        $super_admin = User::where('status','0')->first();
        $super_admin_revenue = (float)$super_admin->current_revenue;
        $super_admin_new_revenue = $super_admin_revenue+$super_admin_commission;
        $update_super_Admin['current_revenue'] = $super_admin_new_revenue;
        User::where('status','0')->update($update_super_Admin);
        $comission['company_id'] = $company->id;
        $comission['affiliate_id'] = $id;
        $comission['planid'] = $plan->id;
        $comission['amount'] = $super_admin_commission;
        $comission['transaction_id'] = $transaction_id;
        $comission['created_at'] = new \DateTime();
        DB::table('commission')->insert($comission);

        $purchase['affiliate_id'] = $invited_user->affiliate_id;
        $purchase['company_id'] = $invited_affiliate->company_id;


        $purchase['transaction_id'] = $transaction_id;
        $purchase['date']           = new \DateTime();
        $purchase['price']          = $price;
        $purchase['product_id']     = $product_id;
        DB::table( 'purchase_links' )->insert( $purchase );


//        taking details of affiliate who sent the link
        $input['company_id'] = $invited_affiliate->company_id;

        //fetching the details of this affiliate from user table

//        fetching first level as always link shared affiliate will get the level 1 benifit
        $level_content = level::where( 'level', 1 )->where( 'company_id', $invited_affiliate->company_id )->first();
        $share         = $level_content->share_to_team_revenue ;

//      Total amount to be added to revenue based on level's share
        $amount_share = ( (float) ( $price ) * (float) $share ) / 100;


//      if current revenue is null assign 0
        if ( $invited_user->current_revenue == "" || empty( $invited_user->current_revenue ) ) {
            $invited_user->current_revenue = 0;
        }

//      adding calculated amount to the old revenue
        $new_revenue                       = (float) ( $invited_user->current_revenue ) + $amount_share;
        $update_revenue['current_revenue'] = $new_revenue;

        //updating revenue in user table and affiliate table
        User::whereId( $invited_user->id )->update( $update_revenue );
        affiliate::whereId( $invited_affiliate->id )->update( $update_revenue );
        $input['affiliate_id'] = $invited_affiliate->id;
        $input['amount']       = $amount_share;
        $input['created_at']   = new \DateTime();
        DB::table( 'purchase_history' )->insert( $input );

        $updated_affiliate         = affiliate::whereId( $invited_affiliate->id )->first();
        $updated_affiliate_revenue = (float) $updated_affiliate->current_revenue;

//        declaring affiliate id and invitee for further user
        $affiliate_id = $id;
        $invitee      = $invited_affiliate->invitee;
        $level        = 2;
        while ( $level <= $max_level ) //checking that level can not be more than plan level admin is not a level, so to exclude the admin
        {
//            taking the details of each level
            $level_content = level::where( 'level', $level )->where( 'company_id', $invited_affiliate->company_id )->first();
            $share         = $level_content->share_to_team_revenue ;

//          Total amount to be added to revenue based on level's share
            $amount_share = ( (float) ( $price ) * (float) $share ) / 100;

//            fetching the parent user's (person who invited the current affiliate) details
            $user = User::whereId( $invitee )->first();


//          if current revenue is null assign 0
            if ( $user->current_revenue == "" || empty( $user->current_revenue ) ) {
                $user->current_revenue = 0;
            }

//          adding calculated amount to the old revenue
            $new_revenue                       = (float) ( $user->current_revenue ) + $amount_share;
            $update_revenue['current_revenue'] = $new_revenue;


            if ( $user->status == '1' ) //if the parent of current affiliate is admin, then stop the interation and give his share
            {
                User::whereId( $user->id )->update( $update_revenue ); //updating new revenue to user table
                break;
            } else //if invited/parent of current affiloiate is not admin and its an affiliate, give that affiliate's share based on the level
            {
                $affiliate_id          = $user->affiliate_id;
                $input['amount']       = $amount_share;
                $input['created_at']   = new \DateTime();
                $input['affiliate_id'] = $affiliate_id;
                DB::table( 'purchase_history' )->insert( $input );
                $affiliate = affiliate::whereId( $affiliate_id )->first();
//                updating new revenue to user and affiliate table
                User::whereId( $user->id )->update( $update_revenue );
                affiliate::whereId( $affiliate->id )->update( $update_revenue );


                $invitee = $affiliate->invitee;

                $level = $level + 1; //increasing the level count
                $affiliates = affiliate::where('company_id',$company->id)->get();
                foreach($affiliates as $affiliate)
                {
                    $rankid = $this->calculateRank($affiliate->id);
                    $u_a['rankid'] = $rankid;
                    affiliate::whereId($affiliate->id)->update($u_a);
                }
            }
        }
        echo "done Purchase";
    }


    public function SearchByName($val,$array)
    {
        $cid = Auth::user()->company_id;

        if ($val != 'nullempty' && $array == 'nullempty')
        {
            if (affiliate::where('company_id', $cid)->where('name', 'like', '%' . 'n' . '%')->exists()) {
                $affiliates = affiliate::where('company_id', $cid)->where('name', 'like', '%' . $val . '%')->get();
//                $result = '';
                $result = $this->getResult($affiliates);

            } else {
                $result = "";
            }
        }
        elseif($val == 'nullempty' && $array != 'nullempty')
        {

            $ranks = explode(',', $array);
            if (affiliate::where('company_id', $cid)->whereIn('rankid', $ranks)->exists()) {
                $affiliates = affiliate::where('company_id', $cid)->whereIn('rankid', $ranks)->get();
                $result = $this->getResult($affiliates);
            }
        }
        elseif($val != 'nullempty' && $array != 'nullempty')
        {
            $ranks = explode(',', $array);
            if (affiliate::where('company_id', $cid)->where('name','like','%'.$val.'%')->whereIn('rankid', $ranks)->exists()) {
                $affiliates = affiliate::where('company_id', $cid)->where('name','like','%'.$val.'%')->whereIn('rankid', $ranks)->get();
                $result = $this->getResult($affiliates);
            }
        }
        else
        {
            $affiliates = affiliate::where('company_id',$cid)->get();
            $result = $this->getResult($affiliates);
        }
        return $result;
    }
    public function descendingSort($value,$array)
    {
        $cid = Auth::user()->company_id;
        if ($value == 'nullempty' && $array == 'nullempty')
        {
            if (affiliate::where('company_id', $cid)->orderby('current_revenue', 'desc')->exists())
            {
                $affiliates = DB::select("SELECT * FROM `affilates` WHERE company_id = $cid ORDER BY convert(`current_revenue`, decimal) DESC");

                $result = $this->getResult($affiliates);
            } else
            {
                $result = '';
            }
        }
        elseif($value != 'nullempty' && $array == 'nullempty')
        {
            if (affiliate::where('company_id', $cid)->where('name','like','%'.$value.'%')->orderby('current_revenue', 'desc')->exists())
            {
                $affiliates = DB::select("SELECT * FROM `affilates` WHERE company_id = $cid and `name` like '%$value%' ORDER BY convert(`current_revenue`, decimal) DESC");
                $result = $this->getResult($affiliates);

            } else
            {
                $result = '';
            }
        }
        elseif($value == 'nullempty' && $array != 'nullempty')
        {
            $ranks = explode(',', $array);
            if (affiliate::where('company_id', $cid)->whereIn('rankid', $ranks)->orderby('current_revenue','desc')->exists()) {
                $affiliates = DB::select("SELECT * FROM `affilates` WHERE company_id = $cid and `rankid` in ($array) ORDER BY convert(`current_revenue`, decimal) DESC");
                $result = $this->getResult($affiliates);
            }
        }
        else
        {
            $ranks = explode(',', $array);

            if (affiliate::where('company_id', $cid)->where('name','like','%'.$value.'%')->whereIn('rankid', $ranks)->orderby('current_revenue','desc')->exists()) {
                $affiliates = DB::select("SELECT * FROM `affilates` WHERE company_id = $cid and `rankid` in ($array) and `name` like '%$value%' ORDER BY convert(`current_revenue`, decimal) DESC");
                $result = $this->getResult($affiliates);
            }
            else
            {
                $result = '';
            }
        }
        return $result;
    }
    public function ascendingSort($value,$array)
    {
        $cid = Auth::user()->company_id;
        if ($value == 'nullempty' && $array == 'nullempty')
        {
            if (affiliate::where('company_id', $cid)->orderby('current_revenue')->exists())
            {
                $affiliates = DB::select("SELECT * FROM `affilates` WHERE company_id = $cid ORDER BY convert(`current_revenue`, decimal)");
                $result = $this->getResult($affiliates);
            }
            else
            {
                $result = '';
            }
        }
        elseif($value != 'nullempty' && $array == 'nullempty')
        {
            if (affiliate::where('company_id', $cid)->where('name','like','%'.$value.'%')->orderby('current_revenue')->exists())
            {
                $affiliates = DB::select("SELECT * FROM `affilates` WHERE company_id = $cid and `name` like '%$value%' ORDER BY convert(`current_revenue`, decimal)");
                $result = $this->getResult($affiliates);
            } else
            {
                $result = '';
            }
        }
        elseif($value == 'nullempty' && $array != 'nullempty')
        {
            $ranks = explode(',', $array);
            if (affiliate::where('company_id', $cid)->whereIn('rankid', $ranks)->orderby('current_revenue')->exists()) {
                $affiliates = DB::select("SELECT * FROM `affilates` WHERE company_id = $cid and rankid in ($array) ORDER BY convert(`current_revenue`, decimal)");
                $result = $this->getResult($affiliates);
            }
            else
            {
                $result = '';
            }
        }
        else
        {
            $ranks = explode(',', $array);
            $result = '';
            if (affiliate::where('company_id', $cid)->where('name','like','%'.$value.'%')->whereIn('rankid', $ranks)->orderby('current_revenue')->exists()) {
                $affiliates = DB::select("SELECT * FROM `affilates` WHERE company_id = $cid and `name` like '%$value%' and rankid in ($array) ORDER BY convert(`current_revenue`, decimal)");
                $result = $this->getResult($affiliates);
            }
        }
        return $result;
    }
    public function filterbyRank($array,$value)
    {
        $cid = Auth::user()->company_id;

        if ($array != 'nullempty' && $value == 'nullempty')
        {
            $result = '';
            $ranks = explode(',',$array);
            if (affiliate::where('company_id', $cid)->whereIn('rankid',[$array])->exists()) {
                $affiliates = affiliate::where('company_id', $cid)->whereIn('rankid',$ranks)->get();
                $result = $this->getResult($affiliates);
            }
            return $result;
        }
        elseif ($array == 'nullempty' && $value != 'nullempty')
        {
            if (affiliate::where('company_id',$cid)->where('name','like','%'.$value.'%')->exists())
            {
                $affiliates = affiliate::where('company_id',$cid)->where('name','like','%'.$value.'%')->get();
                $result = $this->getResult($affiliates);
            }
            else
            {
                $result = "";
            }
        }
        elseif ($array != 'nullempty' && $value != 'nullempty')
        {
            $result = '';
            $ranks = explode(',',$array);
            if (affiliate::where('company_id', $cid)->where('name','like','%'.$value.'%')->whereIn('rankid', $ranks)->exists()) {
                $affiliates = affiliate::where('company_id', $cid)->where('name','like','%'.$value.'%')->whereIn('rankid', $ranks)->get();
                $result = $this->getResult($affiliates);
            }
        }
        else
        {
            $result = '';
            $affiliates = affiliate::where('company_id',$cid)->get();
            $i=0;
            $result = $this->getResult($affiliates);
        }
        return $result;
    }



    function getResult($affiliates)
    {
        $result = '';
        foreach ($affiliates as $affiliate) {
            $affUser = \App\User::where('affiliate_id',$affiliate->id)->first();
            if($affUser->status == '4')
            {
                if(\Illuminate\Support\Facades\DB::table('bot_plans')->where('company_id',$affUser->company_id)->where('payment_status',1)->exists() == 0)
                {
                    continue;
                }
            }
            if($affiliate->current_revenue == '')
            {
                $affiliate->current_revenue = 0;
            }
            $rankid = $this->calculateRank($affiliate->id);
            if (rank::where('company_id', $affiliate->company_id)->where('rank', $rankid)->exists()) {
                $current_rank = rank::where('company_id', $affiliate->company_id)->where('rank', $rankid)->first();
            } else {
                $current_rank = "";
            }
            if (DB::table('purchase_history')->where('affiliate_id',$affiliate->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
            {
                $sales = DB::table('purchase_history')->where('affiliate_id',$affiliate->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
            }
            else
            {
                $sales = "";
            }
            $current_user = User::where('affiliate_id', $affiliate->id)->first();
            $parent_user =  User::whereId($affiliate->invitee)->first();
            if ($parent_user->status == '1')
            {
                $parent = company::whereId($parent_user->company_id)->first();
            }
            else
            {
                $parent = affiliate::whereId($parent_user->affiliate_id)->first();
            }
            if (affiliate::where('invitee',$current_user->id)->exists())
            {
                $childrens = affiliate::where('invitee',$current_user->id)->get();
            }
            else
            {
                $childrens = "";
            }
            $joined = strtotime($affiliate->created_at);
            $result .=
                '
                         <tr> 
                         <td class="col-md-1 col-sm-1">';
            if (isset($affiliate->photo) && ($affiliate->photo != '') || !empty($affiliate->photo)) {
                $result .= '<img class="img img-circle img-affiliate" src="' . asset('public/avatars') . '/' . $affiliate->photo . '">';
            } else {
                $result .= '<img class="img img-circle img-affiliate" src="' . asset('public/pictures/default.jpg') . '">';
            }
            $result .= '
                         </td>
                         <td class="col-md-4">
                                <span class="affiliate-table-name">' . $affiliate->name . '</span> <br/>
                                <span class="affiliate-other-details">' . $affiliate->email . '</span> <br/>
                                <span class="affiliate-other-details">' . $affiliate->phone . '</span> <br/>
                                <span class="affiliate-other-details">'.trans('affiliate.joined').' ' . date('m/d/Y', $joined) . '</span> <br/>
                         </td>
                         <td class="col-md-2">';
            if ($current_rank != "") {
                $result .= '<h4 class="affiliate-table-h4"><b>' . strtoupper($current_rank->name) . '</b></h4>';
            } else {
                $result .= '<h4 class="affiliate-table-h4"><b>-</b></h4>';
            }
            $result .= '
                                <h4 class="affiliate-table-h4"><b>&dollar;' . number_format($affiliate->current_revenue) . '</b></h4>
                                <h6 class="affiliate-table-h6"><b>'.trans('home.revenue').'</b></h6>
                         </td>
                         <td class="col-md-2">
                                <h4 class="affiliate-table-h4">'.trans('home.payouts').'</h4>';
            if ($current_rank != "") {
                $result .= '<h4 class="affiliate-table-h4">&dollar;' . number_format($current_rank->payout_amount) . '</h4>';
            } else {
                $result .= '<h4 class="affiliate-table-h4"><b>-</b></h4>';
            }
            $result .= '
                                  <h6 class="affiliate-table-h6">'.trans('home.payouts').'</h6>
                         </td>
                         <td class="col-md-3">
                            <button type="button" class="btn btn-primaryy affiliate-table-btn" data-toggle="modal" data-target="#treeModal'.$affiliate->id.'">'.trans('affiliate.show_tree').'</button> 
                            <button type="button" class="btn btn-primaryy affiliate-table-btn" data-toggle="modal" data-target="#salesModal'.$affiliate->id.'">'.trans('affiliate.see_sales').'</button>
                         
                     
                     <div class="modal fade" id="salesModal'.$affiliate->id.'" role="dialog">
                        <div class="modal-dialog add-level-modal">
                            <div class="modal-content add-level-content">
                                <div class="modal-header">
                                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                                   <center><b><h2 class="modal-title">'.trans('affiliate.direct_sales').'</h2></b></center>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                       <center>
                                           <a href="'.url('exportSales').'/'.$affiliate->id.'" class="btn btn-primaryy">'.trans('home.export_csv').'</a>
                                           <a href="'.url('exportSalesPdf').'/'.$affiliate->id.'" class="btn btn-primaryy">'.trans('home.export_pdf').'</a>
                                       </center>
                                    </div>';
            if($sales != "")
            {
                $result .= '<div class="row">';
                foreach($sales as $sale)
                {
                    $result .='<div class="col-md-12 affiliate-sales">
                                                                        <div class="col-md-4">
                                                                            <h5><b>'.date('m/d/Y',strtotime($sale->created_at)).'</b></h5>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <h5><b>'.$sale->name.'</b></h5>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <h5><b>$'.number_format($sale->amount).'</b></h5>
                                                                        </div>
                                                                    </div>';
                }
                $result .= '</div>';
            }
            else
            {
                $result .='<div class="row">
                                                    <div class="col-md-12 affiliate-sales">
                                                        <h4><b>'.trans('affiliate.no_sale').'</b></h4>
                                                    </div>
                                                   </div>';
            }

            $result .= '</div>
                                <div class="modal-footer">
                                </div>
                            </div>
                        </div>
                     </div>
                     <div class="modal fade" id="treeModal'.$affiliate->id.'" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content add-level-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <section class="management-hierarchy">
                                        <div class="hv-container">
                                            <div class="hv-wrapper">
                                                <div class="hv-item">
                                                   <div class="hv-item-parent">
                                                    <div class="person">';
            if($parent_user->status == '1')
            {
                if(isset($parent->logo))
                {
                    $result .='<img src="'.asset('public/avatars').'/'.$parent->logo.'" alt="">';
                }
                else
                {
                    $result .='<img src="'.asset('public/pictures/default.jpg').'" alt="">';
                }
            }
            else
            {
                if(isset($parent->photo))
                {
                    $result .='<img src="'.asset('public/avatars').'/'.$parent->photo.'" alt="">';
                }
                else
                {
                    $result .='<img src="'.asset('public/pictures/default.jpg').'" alt="">';
                }
            }

            $result .= '    <p class="name">
                                                             '.$parent->name.'
                                                         </p>
                                                    </div>
                                                    </div>
                                                    <div class="hv-item-children">
                                                        <div class="hv-item-child">
                                                            <div class="hv-item">
                                                                <div class="hv-item-parent">
                                                                    <div class="person">';
            if(isset($affiliate->photo))
            {
                $result .='<img src="'.asset('public/avatars').'/'.$affiliate->photo.'" alt="">';
            }
            else
            {
                $result .='<img src="'.asset('public/pictures/default.jpg').'" alt="">';
            }

            $result .='           <p class="name">
                                                                                '.$affiliate->name.'
                                                                            </p>
                                                                     </div>
                                                                </div>
                                                                <div class="hv-item-children">';
            if($childrens != "")
            {
                foreach($childrens as $children)
                {
                    $child_user = User::where('affiliate_id', $children->id)->first();
                    $child_count = affiliate::where('invitee', $child_user->id)->count();

                    $result .='<div class="hv-item-child">
                                                                                         <div class="person">';
                    if (isset($children->photo))
                    {
                        $result .='<img src="'.asset('public/avatars').'/'.$children->photo.'" alt="">';
                    }
                    else
                    {
                        $result .='<img src="'.asset('public/pictures/default.jpg').'" alt="">';
                    }
                    $result .='  <p class="name">
                                                                                            '.$children->name.'
                                                                                        </p>
                                                                                        '.$child_count.' '.trans('home.affiliates').' 
                                                                                        </div>
                                                                                      </div>              
                                                                                                    ';
                }
            }
            else
            {
                $result .='<p class="name">
                                                                                    0 '.trans('home.affiliates').'
                                                                                  </p>';
            }
            $result .='</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="modal-footer">
                                </div>
                                
                            </div>
                        </div>
                     </div>
                     </td>
                     </tr>
                     ';
        }
        return $result;
    }


    public function exportSales($id)
    {
        $affiliate = affiliate::whereId($id)->first();

        if (DB::table('purchase_history')->where('company_id',$affiliate->company_id)->where('affiliate_id',$affiliate->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
        {
            $sales = DB::table('purchase_history')->where('company_id',$affiliate->company_id)->where('affiliate_id',$affiliate->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
        }
        else
        {
            $sales = "";
        }
        $Info = array();
        array_push($Info, ['Date', 'Name','Amount']);
        if($sales != "")
        {
            foreach ($sales as $sale)
            {
                array_push($Info, [date('m/d/Y',strtotime($sale->created_at)),$sale->name,number_format($sale->amount)]);
            }
        }
        Excel::create($affiliate->name.'_Sales', function ($excel) use ($Info) {
            $excel->setTitle('Users');
            $excel->setCreator('milad')->setCompany('Test');
            $excel->setDescription('users file');
            $excel->sheet('sheet1', function ($sheet) use ($Info) {
                $sheet->setRightToLeft(true);
                $sheet->fromArray($Info, null, 'A1', false, false);
            });

        })->download('csv');
    }

    public function exportSalesPdf($id)
    {
        $affiliate = affiliate::whereId($id)->first();

        if (DB::table('purchase_history')->where('company_id',$affiliate->company_id)->where('affiliate_id',$affiliate->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
        {
            $sales = DB::table('purchase_history')->where('company_id',$affiliate->company_id)->where('affiliate_id',$affiliate->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
        }
        else
        {
            $sales = "";
        }
// create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle($affiliate->name.'_Sales');
        $pdf->SetSubject('Sales Details');
        $pdf->SetKeywords('PDF,Sales');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set default font subsetting mode
        $pdf->setFontSubsetting(true);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
// helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 8, '', true);
// Add a page
// This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
// set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        $date = date('m/d/Y',time());
        // <Html part goes here
        $html = <<<EOD
        
                 
                   <center><table style="width: 100%">
                   <thead>
                        <tr>
                            <th style="width: 10%">#</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td colspan="4" style="width: 100%;height: 10px"></td>
                        </tr>
                   </thead>
EOD;
        if($sales != "")
        {
            $k=1;
            foreach ($sales as $sale)
            {
                $date = date('m/d/Y',strtotime($sale->created_at));
                $amount = number_format($sale->amount);
                $html .= <<<EOD
                    <tr>
                        <td style="width: 10%">$k</td>
                        <td style="width: 25%">$date</td>
                        <td style="width: 25%">$sale->name</td>
                        <td style="width: 25%">$amount</td>
                    </tr>
    
EOD;

                $k++;
            }
        }
        $html .= <<<EOD
</table></center>
EOD;


        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output($affiliate->name.'_Sales.pdf', 'D');
    }
    public function marketingHelp()
    {
        $id= Auth::user()->affiliate_id;
        $affiliate = affiliate::whereId($id)->first();
        $company = company::whereId($affiliate->company_id)->first();
        $company_user = User::where('company_id',$affiliate->company_id)->where('status','1')->first();
        if (Auth::user()->status == '4')
        {
            $userCompany = company::whereId(Auth::user()->company_id)->first();
            if(DB::table('bot_plans')->where('company_id',Auth::user()->company_id)->where('payment_status',1)->exists() == 0)
            {
                $invited_user = User::whereId($affiliate->invitee)->first();
                Flash::error(trans('plan.purchase_required'));
                return redirect('samybot/plan?affiliate_id='.$invited_user->affiliate_id);
            }
        }
        if ($company->affiliate_disabled == 1)
        {
            return view('frontEnd.disabled');
        }
        elseif (Auth::user()->activated == 0)
        {
            return redirect('resendMail'.'/'.Auth::user()->id);
        }
        elseif (Auth::user()->profile == 0)
        {
            return redirect('myProfile');
        }
        $pageHeader = trans('home.marketing_help');
        $rankid = $this->calculateRank($id);
        if(rank::where('company_id',$affiliate->company_id)->where('rank',$rankid)->exists())
        {
            $rank = rank::where('company_id',$affiliate->company_id)->where('rank',$rankid)->first();
        }
        else
        {
            $rank = "";
        }
        return view('frontEnd.affiliate.marketing',compact('pageHeader','rank','affiliate'));
    }
    public function affilaiteSales()
    {
        $id= Auth::user()->affiliate_id;
        $affiliate = affiliate::whereId($id)->first();
        if (Auth::user()->status == '4')
        {
            $userCompany = company::whereId(Auth::user()->company_id)->first();
            if(DB::table('bot_plans')->where('company_id',Auth::user()->company_id)->where('payment_status',1)->exists() == 0)
            {
                $invited_user = User::whereId($affiliate->invitee)->first();
                Flash::error(trans('plan.purchase_required'));
                return redirect('samybot/plan?affiliate_id='.$invited_user->affiliate_id);
            }
        }

        $company = company::whereId($affiliate->company_id)->first();

        $company_user = User::where('company_id',$affiliate->company_id)->first();

        if ($company->affiliate_disabled == 1)
        {
            return view('frontEnd.disabled');
        }
        elseif (Auth::user()->activated == 0)
        {
            return redirect('resendMail'.'/'.Auth::user()->id);
        }
        elseif (Auth::user()->profile == 0)
        {
            return redirect('myProfile');
        }
        $pageHeader = trans('home.my_sales');

        $rankid = $this->calculateRank($id);
        if(rank::where('company_id',$affiliate->company_id)->where('rank',$rankid)->exists())
        {
            $rank = rank::where('company_id',$affiliate->company_id)->where('rank',$rankid)->first();
        }
        else
        {
            $rank = "";
        }
        $planTable = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();

        $plan = plantable::whereId($planTable->planid)->first();
        $max_levels = $plan->levels;
        $max_affiliates = $plan->affiliates;
        $current_affiliates = affiliate::where('company_id',$company->id)->count();

        if (Auth::user()->status == '1' || Auth::user()->status == '2' || Auth::user()->status == '4') {
            $level = 1;
            if (Auth::user()->status == '2' || Auth::user()->status == '4') {
                $level = 2;
                $affiliate_id = $id;
                while ($level <= 999) {
                    $affiliate_user = affiliate::whereId($affiliate_id)->first();
                    $invitee = $affiliate_user->invitee;
                    $invited_user = User::whereId($invitee)->first();
                    if ($invited_user->status == '1') {
                        break;
                    }
                    elseif($invited_user->status == 4)
                    {
                        if ($affiliate->company_id == $invited_user->company_id)
                        {
                            break;
                        }
                        else
                        {
                            $level += 1;
                            $affiliate_id = $invited_user->affiliate_id;
                        }
                    }
                    else {
                        $level += 1;
                        $affiliate_id = $invited_user->affiliate_id;

                    }
                }
            }
        }
        if (DB::table('purchase_history')->where('company_id',$affiliate->company_id)->where('affiliate_id',$affiliate->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
        {
            $sales = DB::table('purchase_history')->where('company_id',$affiliate->company_id)->where('affiliate_id',$affiliate->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
        }
        else
        {
            $sales = "";
        }
        return view('frontEnd.affiliate.sales',compact('affiliate','sales','pageHeader','max_levels','max_affiliates','current_affiliates','level','company','rank','company'));
    }
    public function affilaiteStats()
    {
        $id= Auth::user()->affiliate_id;
        $affiliate = affiliate::whereId($id)->first();
        $company = company::whereId($affiliate->company_id)->first();
        $company_user = User::where('company_id',$affiliate->company_id)->where('status','1')->first();
        if (Auth::user()->status == '4')
        {
            $userCompany = company::whereId(Auth::user()->company_id)->first();
            if(DB::table('bot_plans')->where('company_id',Auth::user()->company_id)->where('payment_status',1)->exists() == 0)
            {
                $invited_user = User::whereId($affiliate->invitee)->first();
                Flash::error(trans('plan.purchase_required'));
                return redirect('samybot/plan?affiliate_id='.$invited_user->affiliate_id);
            }
        }
        if ($company->affiliate_disabled == 1)
        {
            return view('frontEnd.disabled');
        }
        elseif (Auth::user()->activated == 0)
        {
            return redirect('resendMail'.'/'.Auth::user()->id);
        }
        elseif (Auth::user()->profile == 0)
        {
            return redirect('myProfile');
        }
        $pageHeader = trans('home.my_affiliates');
        $rankid = $this->calculateRank($id);
        if(rank::where('company_id',$affiliate->company_id)->where('rank',$rankid)->exists())
        {
            $rank = rank::where('company_id',$affiliate->company_id)->where('rank',$rankid)->first();
        }
        else
        {
            $rank = "";
        }
        $planTable = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();
        $plan = plantable::whereId($planTable->planid)->first();
        $max_levels = $plan->levels;
        $max_affiliates = $plan->affiliates;
        $current_affiliates = affiliate::where('company_id',$company->id)->count();
        if (Auth::user()->status == '1' || Auth::user()->status == '2' || Auth::user()->status == '4') {
            $level = 1;
            if (Auth::user()->status == '2' || Auth::user()->status == '4') {
                $level = 2;
                $affiliate_id = $id;
                while ($level <= 999) {
                    $affiliate_user = affiliate::whereId($affiliate_id)->first();
                    $invitee = $affiliate_user->invitee;
                    $invited_user = User::whereId($invitee)->first();
                    if ($invited_user->status == '1') {
                        break;
                    }
                    elseif($invited_user->status == 4)
                    {
                        if ($affiliate->company_id == $invited_user->company_id)
                        {
                            break;
                        }
                        else
                        {
                            $level += 1;
                            $affiliate_id = $invited_user->affiliate_id;
                        }
                    }
                    else
                    {
                        $level += 1;
                        $affiliate_id = $invited_user->affiliate_id;

                    }
                }
            }
        }
        if (affiliate::where('invitee',Auth::user()->id)->exists())
        {
            $affiliates = affiliate::where('invitee',Auth::user()->id)->get();
        }
        else
        {
            $affiliates ="";
        }
        return view('frontEnd.affiliate.affiliates',compact('affiliates','level','pageHeader','max_affiliates','max_levels','current_affiliates','company','rank','affiliate'));
    }
    public function overallStats($value)
    {

        $affiliateId= Auth::user()->affiliate_id;

        $affiliate = affiliate::whereId($affiliateId)->first();
        if ($value == 'one')
        {

            $lastDate  = Carbon::now()->startOfMonth();
            $affiliate_count2 = affiliate::where('invitee', Auth::user()->id)->whereDate('created_at','>=',$lastDate)->get();
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
            $sales_count = DB::table('purchase_history')->where('affiliate_id', $affiliateId)->whereDate('created_at','>=',Carbon::now()->startOfMonth())->count();

//            if (payouthistory::where('affiliate_id',$affiliateId)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
//            {
//                $payouts = payouthistory::where('affiliate_id',$affiliateId)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
////                return $payouts;
//                $payout_total = 0;
//                foreach($payouts as $payout)
//                {
//                    $payout_total +=$payout->amount;
//                }
//            }
//            else
//            {
//                $payout_total = 0;
//            }
            $rankid = $this->calculateRank($affiliateId);
//                        return $rankid;
            if(rank::where('company_id',$affiliate->company_id)->where('rank',$rankid)->exists())
            {
                $rank = rank::where('company_id',$affiliate->company_id)->where('rank',$rankid)->first();
                $payout_total = $rank->payout_amount;
            }
            else
            {
                $rank = "";
                $payout_total=0;
            }

            $revenue_total = 0;
            if (DB::table('purchase_history')->where('affiliate_id',$affiliateId)->where('created_at', '>=', Carbon::now()->startOfMonth())->exists())
            {
                $revenues = DB::table('purchase_history')->where('affiliate_id',$affiliateId)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
                foreach($revenues as $revenue)
                {
                    $revenue_total += $revenue->amount;
                }
            }
            $revenue_total=number_format($affiliate->current_revenue);
        }
        else
        {
            $affiliate_count2 = affiliate::where('invitee',Auth::user()->id)->get();
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
            $sales_count = DB::table('purchase_history')->where('affiliate_id',$affiliateId)->count();
            if (payouthistory::where('affiliate_id',$affiliateId)->exists())
            {
                $payouts = payouthistory::where('affiliate_id',$affiliateId)->get();
                $payout_total = 0;
                foreach($payouts as $payout)
                {
                    $payout_total +=$payout->amount;
                }
            }
            else
            {
                $payout_total = 0;
            }
            $revenue_total = 0;
            if (DB::table('purchase_history')->where('affiliate_id',$affiliateId)->exists())
            {
                $revenues = DB::table('purchase_history')->where('affiliate_id',$affiliateId)->get();
                foreach($revenues as $revenue)
                {
                    $revenue_total += $revenue->amount;
                }
            }
            $revenue_total=number_format($revenue_total);
        }
        $result = '';
        $result .='
        <div class="col-md-10">
                    <div class="col-md-3 grid_system">
                        <p class="affiliate_Number">'.$affiliate_count.'</p>
                        <a class="section2_headings stats_section_headings" href="'.url('affiliates').'"><h2>'.trans('home.affiliates').'</h2></a>
                    </div>
                    <div class="col-md-3 grid_system">
                        <p class="affiliate_Number">'.$sales_count.'</p>
                        <a class="section2_headings stats_section_headings" href="'.url('sales').'"><h2>'.trans('home.sales').'</h2></a>
                    </div>
                    <div class="col-md-3 grid_system">
                        <p class="affiliate_revenue">$'.$revenue_total.'</p>
                        <h2>'.trans('home.revenue').'</h2>
                    </div>
                    <div class="col-md-3 grid_system">
                        <p class="affiliate_revenue">$'.$payout_total.'</p>
                        <h2>'.trans('home.payouts').'</h2>
                    </div>
                </div>
        ';
        return $result;
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