<?php
namespace App\Http\Controllers\samybot;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\SamyBotPlans;
use App\Models\affiliate;
use App\Models\company;
use Carbon\Carbon;
use Response;
use App\User;
use Flash;

class cronController extends Controller
{
    /** @var  rankRepository */

    public function __construct()
    {

    }

    public function samybotCron()
    {

//        $val =  Parent::getaccess('hi' , 'hello');
        $companies = company::get();
        foreach ($companies as $company) {
            $user = User::where('company_id', $company->id)->first();
            if ($user->special_user != 1) {
                if (DB::table('bot_plans')->where('company_id',$company->id)->exists())
                {
                    $bot_plans = DB::table('bot_plans')->where('company_id',$company->id)->get();
                    foreach ($bot_plans as $bot_plan){
                        if($bot_plan->subscription_id == "" && $bot_plan->auto_renewal == 0){
                            $samyBotPlan = SamyBotPlans::whereId($bot_plan->plan)->first();
                            if($samyBotPlan->term == "month"){
                                $expiry_date =  strtotime($bot_plan->date.'+30 days');
                            }
                            else{
                                $expiry_date = strtotime($bot_plan->date.'+1 years');
                            }
                            $days_left = date('d',($expiry_date - time()));
                            if($days_left == 7){
                                //send mail
                                try {
                                    Mail::send('email.samybot_reminder', ['company' => $company,'bot_plan' => $bot_plan,'days_left'=>$days_left], function ($message) use ($company,$days_left,$bot_plan) {
                                        $message->to($company->email)
                                            ->subject('SamyBot Plan Renewal');
                                    });
                                }
                                catch (\Swift_TransportException $ex) {
                                }
                            }
                            elseif ($days_left == 3){
                                //send mail
                                try {
                                    Mail::send('email.samybot_reminder', ['company' => $company,'bot_plan' => $bot_plan,'days_left'=>$days_left], function ($message) use ($company,$days_left,$bot_plan) {
                                        $message->to($company->email)
                                            ->subject('SamyBot Plan Renewal');
                                    });
                                }
                                catch (\Swift_TransportException $ex) {
                                }
                            }
                            elseif ($days_left == 1 || $expiry_date == time()){
                                try {
                                    Mail::send('email.samybot_reminder', ['company' => $company,'bot_plan' => $bot_plan,'days_left'=>$days_left], function ($message) use ($company,$days_left,$bot_plan) {
                                        $message->to($company->email)
                                            ->subject('SamyBot Plan Renewal');
                                    });
                                }
                                catch (\Swift_TransportException $ex) {
                                }
                            }
                            elseif(time() > $expiry_date){
                                DB::table('bot_plans')->whereId($bot_plan->id)->update(['status' => 0,'payment_status' => 0]);
                                try {
                                    Mail::send('email.samybot_reminder', ['last_day'=>'1','company' => $company,'bot_plan' => $bot_plan,'days_left'=>$days_left], function ($message) use ($company) {
                                        $message->to($company->email)
                                            ->subject('SamyBot Plan Renewal');
                                    });
                                }
                                catch (\Swift_TransportException $ex) {
                                }
                            }
                        }
                        elseif(!empty($bot_plan->subscription_id) && $bot_plan->auto_renewal == 1){
                            $subscription_id = $bot_plan->subscription_id;
                            $stripe = Stripe::make(env('STRIPE_SECRET'));
                            $subscription = $stripe->subscriptions()->find($company->stripe_id, $subscription_id);
                            if($subscription['status'] != "active" && $subscription['current_period_end'] < time()){
                                DB::table('bot_plans')->whereId($bot_plan->id)->update(['status' => 0,'payment_status' => 0]);
                            }
                        }
                    }
                    if(DB::table('bot_plans')->where('company_id',$company->id)->where('status','1')->where('payment_status','1')->exists() == 0){
                        company::whereId($company->id)->update(['bot_disabled' => 1]);
                    }
                }
            }
        }
    }

    public function TestMail(){
//        $company = company::whereId(39)->first();
//        $bot_plan = DB::table('bot_plans')->where('company_id',$company->id)->first();
//        $days_left = 7;
//        try {
//            Mail::send('email.samybot_reminder', ['last_day'=>'1','company' => $company,'bot_plan' => $bot_plan,'days_left'=>$days_left], function ($message) use ($company) {
//                $message->to('sowjanya.bajaragisoft@gmail.com')
//                    ->subject('SamyBot Plan Renewal');
//            });
//        }
//        catch (\Swift_TransportException $ex) {
//            return $ex;
//        }
    }
}