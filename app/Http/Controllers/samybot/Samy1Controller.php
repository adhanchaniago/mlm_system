<?php

namespace App\Http\Controllers\samybot;

use App\Http\Controllers\AppBaseController;
use App\Models\affiliate;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SamyBotPlans;
use Illuminate\Http\Request;
use App\Models\temp_user;
use App\Models\company;
use Response;
use App\User;
use Session;
use Flash;
use Mail;
use Illuminate\Support\Facades\Cookie;

class Samy1Controller  extends Controller
{
    public function __construct()
    {

    }
    public function samybot_plan(){
        if(isset($_REQUEST['affiliate_id'])) {
            $id = $_REQUEST['affiliate_id'];
            $old_cookie = Cookie::get('samybot_affiliate_id');
            if ($id == $old_cookie)
            {
                if (affiliate::whereId($id)->exists())
                {
                    Cookie::queue('samybot_affiliate_id',$id);
                }
            }
            else
            {

                if(affiliate::whereId($old_cookie)->exists() == 0)
                {
                    if (affiliate::whereId($id)->exists())
                    {
                        Cookie::queue('samybot_affiliate_id',$id);
                    }
                }
                else
                {
                    if (affiliate::whereId($old_cookie)->exists())
                    {
                        Cookie::queue('samybot_affiliate_id',$old_cookie);
                    }
                }
            }
//            return Cookie::get('samybot_affiliate_id');
//            Cookie::queue('samybot_affiliate_id',$id);
        }
        $monthly_plans = SamyBotPlans::where('term','month')->where('status',1)->get();
        $yearly_plans = SamyBotPlans::where('term','year')->where('status',1)->get();
        if (DB::table('activateCharge')->exists())
        {
            $activation = DB::table('activateCharge')->first();
        }
        $rowCount = $monthly_plans->count() + $yearly_plans->count();
        return view('samybot.plans',compact('monthly_plans','yearly_plans','activation','rowCount'));
    }

    public function samybot_register(Request $request){
        if(User::whereEmail($request->email)->exists() && !Auth::check() || Auth::check() == 0){
            $this->middleware('auth');
        }
        if(User::whereEmail($request->email)->exists()){
            $user = User::whereEmail($request->email)->first(); //fetch stored user data as a current user
            $transaction_id = time().rand(1,999999).'_'.$user->company_id;
            for($i = 1;$i<=$request->count;$i++){
                if(isset($request['selected_qty'.$i]) && $request['selected_qty'.$i] != "" && $request['selected_qty'.$i] != 0){
                    $Input = [
                        'company_id' => $user->company_id,
                        'plan' => $request['selected_plan'.$i],
                        'stripe_paln_id' => $request['stripe_plan'.$i],
                        'quantity' => $request['selected_qty'.$i],
                        'price' => $request['selected_price'.$i],
                        'unit' => $request['selected_pack'.$i],
                        'plan_total' => $request['grandTotal'],
                        'transaction_id' => $transaction_id,
                        'shipping_charge' =>$request['shipping_charge'],
                        'activation_charge' => $request['activation_charge'],
                        'payment_status' => 0,
                        'date' => date('d-m-Y'),
                        'shipping_address1' =>$request->shipping_address1,
                        'shipping_address2' =>$request->shipping_address2,
                        'shipping_city' =>$request->shipping_city,
                        'shipping_state' =>$request->shipping_state,
                        'shipping_zip' =>$request->shipping_zip,
                        'shipping_country' =>$request->shipping_country
                    ];
                    DB::table('bot_plans')->insert($Input);
                }
            }
            company::whereId($user->company_id)->update(['samy_bot_transaction_id'=>$transaction_id]);
            User::whereId($user->id)->update(['samy_bot' => 1]);
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect('samybot/payment');
            }
            else
            {
                return redirect('login');
            }
        }

        $hash = bcrypt(time() . rand(0,99999999));
        $hash = str_replace('/', '', $hash);
        $array['email'] = $request->email;
        $array['name'] = $request->first_name.' '.$request->last_name;
        $array['hash'] = $hash;
        Mail::send('email.welcome', ['array' => $array], function ($message) use ($array) {
            $message->to($array['email'], $array['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.welcome'));
        });
        if (isset($request->invitee) && ($request->invitee != '' || !empty($request->invitee)))
        {
            $inputNew = [
                'fname' =>$request->first_name,
                'lname' =>$request->last_name,
                'email' =>$request->email,
                'phno' =>$request->phno,
                'address' =>$request->bill_address,
                'address2' =>$request->address2,
                'city' =>$request->city,
                'state' =>$request->state,
                'zip' =>$request->zip,
                'password' =>$request->password,
                'invitee' => $request->invitee,
                'country' =>$request->country,
                'status' =>4,
            ];
            $temp_user = temp_user::create($inputNew);
            $transaction_id = time().rand(1,999999).'_'.$temp_user->id;
            for($i = 1;$i<=$request->count;$i++){
                if(isset($request['selected_qty'.$i]) && $request['selected_qty'.$i] != "" && $request['selected_qty'.$i] != 0){
                    $Input = [
                        'temp_user_id' => $temp_user->id,
                        'plan' => $request['selected_plan'.$i],
                        'quantity' => $request['selected_qty'.$i],
                        'stripe_plan_id' => $request['stripe_plan'.$i],
                        'price' => $request['selected_price'.$i],
                        'unit' => $request['selected_pack'.$i],
                        'plan_total' => $request['grandTotal'],
                        'transaction_id' => $transaction_id,
                        'shipping_charge' =>$request['shipping_charge'],
                        'activation_charge' => $request['activation_charge'],
                        'date' => date('d-m-Y'),
                        'shipping_address1' =>$request->shipping_address1,
                        'shipping_address2' =>$request->shipping_address2,
                        'shipping_city' =>$request->shipping_city,
                        'shipping_state' =>$request->shipping_state,
                        'shipping_zip' =>$request->shipping_zip,
                        'shipping_country' =>$request->shipping_country
                    ];
                    DB::table('temp_bot_plans')->insert($Input);
                }
            }
            Cookie::queue('special_temp_user',$temp_user->id);
            return redirect('samybot/payment');
        }
        else
        {
            $input = [
                'fname' =>$request->first_name,
                'lname' =>$request->last_name,
                'email' =>$request->email,
                'phno' =>$request->phno,
                'address' =>$request->bill_address,
                'address2' =>$request->address2,
                'city' =>$request->city,
                'state' =>$request->state,
                'zip' =>$request->zip,
                'country' =>$request->country,
                'status' =>1
            ];
            $status = 1;
            $company = company::create($input);//inserting data to company table
            $usr = User::create([
                'fname' => $request->first_name,
                'lname' => $request->last_name,
                'name' => $request->first_name.' '.$request->last_name,
                'email' => $request->email,
                'phone' => $request->phno,
                'password' => bcrypt($request->password),
                'company_id' => $company->id,
                'status' => $status,
                'samy_bot' => 1,
                'activation_hash' => $hash,
            ]);
            $transaction_id = time().rand(1,999999).'_'.$company->id;
            for($i = 1;$i<=$request->count;$i++){
                if(isset($request['selected_qty'.$i]) && $request['selected_qty'.$i] != "" && $request['selected_qty'.$i] != 0){
                    $Input = [
                        'company_id' => $company->id,
                        'plan' => $request['selected_plan'.$i],
                        'quantity' => $request['selected_qty'.$i],
                        'stripe_paln_id' => $request['stripe_plan'.$i],
                        'price' => $request['selected_price'.$i],
                        'unit' => $request['selected_pack'.$i],
                        'plan_total' => $request['grandTotal'],
                        'transaction_id' => $transaction_id,
                        'shipping_charge' =>$request['shipping_charge'],
                        'activation_charge' => $request['activation_charge'],
                        'date' => date('d-m-Y'),
                        'shipping_address1' =>$request->shipping_address1,
                        'shipping_address2' =>$request->shipping_address2,
                        'shipping_city' =>$request->shipping_city,
                        'shipping_state' =>$request->shipping_state,
                        'shipping_zip' =>$request->shipping_zip,
                        'shipping_country' =>$request->shipping_country
                    ];
                    DB::table('bot_plans')->insert($Input);
                }
            }
            company::whereId($company->id)->update(['samy_bot_transaction_id'=>$transaction_id]);
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect('samybot/payment');
            }
            else
            {
                return redirect('login');
            }
        }
    }
}
