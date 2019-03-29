<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Models\company;
use App\Models\plantable;
use App\Models\SamyBotPlans;
use Illuminate\Http\Request;
use App\Models\affiliate;
use App\Models\temp_user;
use App\Models\linkedin_plans;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use Validator;
use URL;
use Session;
use Redirect;
use Illuminate\Support\Facades\Input;
use App\User;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Api\Subscriptions;
use Cartalyst\Stripe\Exception\NotFoundException;
use Stripe\Error\Card;
use Illuminate\Support\Facades\Auth;
class StripeController extends Controller
{
    public function __construct()
    {

    }

    public function activateCard($id)
    {
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $card = DB::table('stripe_cards')->whereId($id)->first();
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        DB::table('stripe_cards')->where('company_id',$card->company_id)->where('status',1)->update(['status'=>0]);
        DB::table('stripe_cards')->whereId($id)->update(['status'=>1]);
        $customer = $stripe->customers()->update( $card->customerId, [
            'default_source' => $card->cardNo
        ]);
        Session::put('success',trans('company.card_update'));
        return redirect()->back();
    }

    public function payWithStripe()
    {
        if (!Auth::check())
        {
            return redirect('login');
        }
        $id = Auth::user()->company_id;
        $company = company::whereId($id)->first();

        if (Auth::user()->samy_affiliate == 0)
        {
            return redirect('plans');
        }
        $planTable = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();
        $plan = plantable::whereId($planTable->planid)->first();
        if(empty($company) || empty($company)){
            return view('frontEnd.home');
        }
        $amount = $plan->amount;
        if ($plan->term == 'month')
        {
            $expiry = date('d/m/Y', strtotime("+30 days"));
        }
        elseif ($plan->term == 'year')
        {
            $expiry = date('d/m/Y', strtotime("+365 days"));
        }


        $card = "";
        $card_number = "";
        $card_cvv = "";
        $card_month = "";
        $card_year = "";
        $stripe_card = "";
        $show_card_number="";


        if (DB::table('stripe_cards')->where('company_id',$id)->where('status',1)->exists())
        {
            $stripeCard = DB::table('stripe_cards')->where('company_id',$id)->where('status',1)->first();
            $stripe = Stripe::make(env('STRIPE_SECRET'));
            try
            {
                $stripe_card = $stripe->cards()->find($stripeCard->customerId, $stripeCard->cardNo);
                $show_card_number = 'XXXX-XXXX-XXXX-'.substr($stripe_card['last4'],-4);
                return view('stripe.paywithstripe',compact('id','act_amt','total_ShipAmt','expiry','amount','show_card_number','ship_amt','activation_charge','shipping_charge','type','plan','total','stripe_card'));
            }
            catch (NotFoundException $e)
            {
                return view('stripe.paywithstripe',compact('id','user','stripe_card','type','plan','amount','expiry','card','card_number','card_cvv','card_month','card_year','show_card_number'));
            }
            catch (ApiLimitExceededException $e)
            {
                return view('stripe.paywithstripe',compact('id','user','stripe_card','type','plan','amount','expiry','card','card_number','card_cvv','card_month','card_year','show_card_number'));
            }
            catch (BadRequestException $e)
            {
                return view('stripe.paywithstripe',compact('id','user','stripe_card','type','plan','amount','expiry','card','card_number','card_cvv','card_month','card_year','show_card_number'));
            }
        }
        else{
            return view('stripe.paywithstripe',compact('id','user','stripe_card','type','plan','amount','expiry','card','card_number','card_cvv','card_month','card_year','show_card_number'));
        }
    }

    public function postPaymentWithStripe(Request $request)
    {
        $id = Auth::user()->company_id;
        $company = company::whereId($id)->first();
        $planFirst = DB::table('companyAffiliatePlans')->where('company_id',$id)->orderby('id','desc')->first();
        $plan = plantable::whereId($planFirst->planid)->first();
        $plan_amount = (float)$plan->amount;
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        $input  = $request->all();
        $input  = array_except($input, array('_token'));

        if(empty($company->stripe_id)){
            $customer = $stripe->customers()->create([
                'email' => $company->email,
                'description' => 'Stripe Customer with Company id -'.$company->id,
            ]);
            $customerId = $customer['id'];
            company::whereId($company->id)->update(['stripe_id' => $customerId]);
        }
        else {

            try {
                $customer = $stripe->customers()->find($company->stripe_id);
                $customerId = $customer['id'];
            } catch (\Exception $e) {
                $customer = $stripe->customers()->create([
                    'email' => $company->email,
                    'description' => 'Stripe Customer with Company id -' . $company->id,
                ]);
                $customerId = $customer['id'];
                company::whereId($company->id)->update(['stripe_id' => $customerId]);
            }
        }

        if(!isset($request->fingerprint)){
            $validator = Validator::make($request->all(), [
                'card_no' => 'required',
                'ccExpiryMonth' => 'required',
                'ccExpiryYear' => 'required',
                'cvvNumber' => 'required',
                'amount' => 'required',
            ],
                [
                    'card_no.required' => trans('card.card_number_required'),
                    'ccExpiryMonth.required' => trans('card.expire_month_required'),
                    'ccExpiryYear.required' => trans('card.expire_year_required'),
                    'cvvNumber.required' => trans('card.cvv_required'),
                    'amount.required' => trans('card.psw_required'),
                ]
            );
            if ($validator->fails()) {
                \Session::put('error', trans('stripe.required'));
                return redirect('stripe');
            }
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $request->get('card_no'),
                    'exp_month' => $request->get('ccExpiryMonth'),
                    'exp_year' => $request->get('ccExpiryYear'),
                    'cvc' => $request->get('cvvNumber'),
                ],
            ]);
            $stripe_input['brand'] = $token['card']['brand'];
            $stripe_fingerprint = $token['card']['fingerprint'];
            if (!isset($token['id'])) {
                \Session::put('error', trans('stripe.token_error'));
                return redirect('stripe');
            }

            $stripe_input['brand'] = $token['card']['brand'];
            $stripe_fingerprint = $token['card']['fingerprint'];
            if(DB::table('stripe_cards')->where('company_id',$id)->exists() == 0){

                $stripe_input['status'] = 1;
            }
            if (DB::table('stripe_cards')->where('company_id',$id)->where('fingerprint',$stripe_fingerprint)->exists() == 0) //To avoid the duplicate entry
            {
                try{
                    $cards = $stripe->cards()->create($customerId, $token['id']);
                }catch (\Exception $e){
                }
                $stripe_input['company_id'] = $id;
                $stripe_input['customerId'] = $customerId;
                $stripe_input['cardNo']  = $cards['id'];
                $stripe_input['fingerprint'] = $stripe_fingerprint;
                $stripe_input['digits'] = 'XXXXXXXXXXXX'.substr($request->card_no,-4);
                DB::table('stripe_cards')->insert($stripe_input); //Entering the card details
            }
        }
        if($plan->term == 'year'){
            $trial_end=	strtotime("+1 year", time());
        }
        else{
            $trial_end=	strtotime("+1 month", time());
        }
        try {
            $subscription = $stripe->subscriptions()->create($customerId, [
                'plan' => $plan->stripe_plan_id,
            ]);
        }
        catch (CardErrorException $ex)
        {
            Flash::error($ex->getMessage());
            return redirect('stripe');
        }
        catch (\Exception $ex)
        {
            Flash::error($ex->getMessage());
            return redirect('stripe');
        }
        $updatePlan['plan_start'] = date('d/m/Y',time());
        $updatePlan['plan_end'] = date('d/m/Y',$trial_end);
        $updatePlan['stripe_plan_id'] = $plan->stripe_plan_id;
        $updatePlan['stripe_subscription_id'] = $subscription['id'];
        if ($subscription['status'] == 'active')
        {
            $updatePlan['payment'] = 1;
            $updatePlan['status'] = 1;
            $status = 1;
        }
        else
        {
            $updatePlan['payment'] = 0;
            $updatePlan['status'] = 0;
            $status = 0;
        }
        if ($subscription['status'] == 'incomplete')
        {
            Flash::error("Something went Wrong Please try again");
            return redirect('stripe');
        }
        if ($status == 1)
        {
            if (!isset($company->apikey) && $company->apikey == '' || empty($company->apikey))
            {
                $apikey = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5) . time() . substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,5);
                company::whereId($company->id)->update(['apikey' => $apikey]);
            }
            DB::table('companyAffiliatePlans')->whereId($planFirst->id)->update($updatePlan);
            $card_details =  DB::table('stripe_cards')->where('company_id',$id)->where('status',1)->first();
            //adding payment detailes code starts here
            $input_pay['payment_id'] = $subscription['id'];
            $input_pay['user_id'] = $id;
            $input_pay['card_number'] = $card_details->id;
            $input_pay['payment_type'] = "Subscribe";
            $input_pay['amount'] = $plan_amount;
            $input_pay['date'] = new \DateTime();
            $input_pay['name'] = $company->fname.' '.$company->lname;
            $input_pay['email'] = $company->email;
            $input_pay['phone'] = $company->phno;
            $input_pay['address'] = $company->address;
            $input_pay['orderid'] = time().$id;
            $input_pay['type'] = $request->type;
            $input_pay['planid'] = $planFirst->planid;

            DB::table('stripepayment')->insert($input_pay);
            if ($company->affiliate_disabled == 1 && $company->affiliate_disabled_reason == '0')
            {
                company::whereId($company->id)->update(['affiliate_disabled'=>0,'affiliate_disabled_reason'=>null]);
            }
            elseif ($company->affiliate_disabled == 1 && ($company->affiliate_disabled_reason == '1,0'|| $company->affiliate_disabled_reason == '0,1'))
            {
                company::whereId($company->id)->update(['affiliate_disabled'=>1,'affiliate_disabled_reason'=>'1']);
            }
            User::whereId(Auth::user()->id)->update(['samy_affiliate' => 1]);
            return redirect('home');
        }
        else
        {
            return redirect('stripe');
        }




    }

    public function LinkedInpayment(){
        if (Auth::user()->link_disabled == 1)
        {
            return view('frontEnd.disabled');
        }
        $id= Auth::user()->company_id;
        $user = company::whereId($id)->first();
        $plan = linkedin_plans::whereId($user->linkedIn_plan)->first();
        $amount = $plan->amount;

        if (DB::table('stripe_cards')->where('company_id',$id)->where('status',1)->exists())
        {
            $stripeCard = DB::table('stripe_cards')->where('company_id',$id)->where('status',1)->first();
            $stripe = Stripe::make(env('STRIPE_SECRET'));
            $stripe_card = $stripe->cards()->find($stripeCard->customerId, $stripeCard->cardNo);
            $type=2;
            $show_card_number = 'XXXX-XXXX-XXXX-'.substr($stripe_card['last4'],-4);
            return view('stripe.paywithstripe',compact('id','act_amt','total_ShipAmt','show_card_number','ship_amt','activation_charge','shipping_charge','type','plans','total','stripe_card'));
        }
        else{
            $card = "";
            $card_number = "";
            $card_cvv = "";
            $card_month = "";
            $card_year = "";
            $show_card_number="";
        }

        if ($plan->term == 'month')
        {
            $expiry = date('d/m/Y', strtotime("+30 days"));
        }
        elseif ($plan->term == 'year')
        {
            $expiry = date('d/m/Y', strtotime("+365 days"));
        }
        $type = 4;
        return view('stripe.paywithstripe',compact('id','user','type','plan','amount','expiry','card','card_number','card_cvv','card_month','card_year','show_card_number'));
    }

    public function StopMlmAutoRenewal($planid,$value){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $id= Auth::user()->company_id;
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        $company = company::whereId($id)->first();
        $customerId = $company->stripe_id;
        $planTable = DB::table('companyAffiliatePlans')->whereId($planid)->first();
        if ($planTable->stripe_subscription_id != '' || !empty($planTable->stripe_subscription_id))
        {
            $subscriptionId = $planTable->stripe_subscription_id;
            if ($value == 0)
            {
                $subscription = $stripe->subscriptions()->cancel($customerId, $subscriptionId,true);
            }
            else
            {
                $subscription = $stripe->subscriptions()->reactivate($customerId, $subscriptionId);
            }
            DB::table('companyAffiliatePlans')->whereId($planid)->update(['auto_renewal' => $value]);
        }
        return "success";
    }
}