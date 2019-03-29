<?php

namespace App\Http\Controllers\samybot;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SamyBotPlans;
use Illuminate\Http\Request;
use App\Models\company;
use App\Models\AppUsers;
use App\Models\bot;
use Response;
use App\User;
use Cookie;
use Flash;
use Illuminate\Support\Facades\Mail;

use App\Models\botCampaign;
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

        $this->Cell(0, 0, 'https://samybot.com', 0, 0, 'L');
        $this->Cell(0, 0, 'Tech made easy', 0, 0, 'R');
        $this->Ln();
    }
}
class SamyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['fetch_plan_data','proceed_to_order']]);
    }

    public function generateAndSendInvoice($trans_id){
        $bots = DB::table('bot_plans')->where('transaction_id',$trans_id)->get();
        $companyId= Auth::user()->company_id;
        $company = company::whereId($companyId)->first();
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle($trans_id);
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
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">#$trans_id</td>
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
                    <td style="width: 50%;font-weight: lighter;text-align: right;font-style: normal;"></td>
                </tr>
            </table>
            <br/> <br/> <br/>
            <table style="width: 100%">
                <thead>
                    <tr style="height: 40px;line-height: 40px;background-color: #e73247">
                        <th style="width: 40%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white"><b>ITEM</b></th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Unit Price</th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Qty</th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Price</th>
                    </tr>
                </thead>
                <tbody>
EOD;
        $totalQty=0;
        foreach ($bots as $bot) {
            $samyBot = SamyBotPlans::whereId($bot->plan)->first();
            $totalPrice= $bot->price * $bot -> quantity;
            $totalQuantity = $bot->unit * $bot -> quantity;
            $html .= <<<EOD
                    <tr style="height: 30px;line-height: 30px;">
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 40%;">$samyBot->name</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">($$bot->price/$bot->unit devices) </td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$totalQuantity</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$$totalPrice</td>
                    </tr>  
EOD;
            $totalQty=$totalQty+$totalQuantity;

        }
        if (DB::table('activateCharge')->exists())
        {
            $activation = DB::table('activateCharge')->first();
        }
        if (DB::table('shipping')->exists())
        {
            $shipping = DB::table('shipping')->first();
            if($company->country == "United States"){
                $shipCharge= $shipping->usa;
            }
            else {
                $shipCharge = $shipping->other;
            }
        }

        $html .= <<<EOD
                    <tr style="height: 30px;line-height: 30px;">
                        <td>Activation Charge</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$activation->amount</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$totalQty</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$ $bot->activation_charge </td>
                    </tr>
                    <tr style="height: 30px;line-height: 30px;">
                        <td>Shipping Charge</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$shipCharge</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$totalQty</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$ $bot->shipping_charge</td>
                    </tr>
                    <tr style="height: 30px;line-height: 30px;">	
                        <td colspan="3" style="background-color: #f3f3f3;font-size: 24px; color: #666666; border:1px solid #f3f3f3;">Total</td>
                        <td style="background-color: #f3f3f3;text-align: right;font-size: 24px; color: #666666; border:1px solid #f3f3f3; width: 20%;">$ $bot->plan_total</td>
                    </tr> 
                    <br/><br/>
                    <tr style="height: 40px;line-height: 40px">
                        <td colspan="4" style="font-size: 20px;">Thank you for your business!</td>
                    </tr>
                </tbody>
            </table>
EOD;

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output(public_path('orders/' . $trans_id . '.pdf'), 'F');
        try {
            Mail::send('email.order', ['company' => $company], function ($message) use ($company,$trans_id) {
                $message->to($company->email)
                    ->subject('Thanks for purchasing on SamyBot')
                    ->cc('orders@samybot.com')
                    ->replyTo('orders@samybot.com')
                    ->attach(public_path().'/orders/'.$trans_id.'.pdf', [
                        'as' => $trans_id.'.pdf',
                        'mime' => 'application/pdf'
                    ]);
            });
        }
        catch (\Swift_TransportException $ex) {
            return $ex->getMessage();
        }
        if (Auth::user()->activated != 1) {
            return redirect('confirmEmail');
        }
        else{
            return redirect( 'samybot/campaigns' );
        }
    }

    public function generateInvoice($trans_id){
        $bots = DB::table('bot_plans')->where('transaction_id',$trans_id)->get();
        $companyId = Auth::user()->company_id;
        $company = company::whereId($companyId)->first();
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle($trans_id);
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
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">#$trans_id</td>
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
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">$company->city  $company->zip</td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">$company->country</td>
                    <td style="width: 50%;font-weight: lighter;text-align: right;font-style: normal;"></td>
                </tr>
            </table>
            <br/> <br/> <br/>
            <table style="width: 100%">
                <thead>
                    <tr style="height: 40px;line-height: 40px;background-color: #e73247">
                        <th style="width: 40%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white"><b>ITEM</b></th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Unit</th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Qty</th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Price</th>
                    </tr>
                </thead>
                <tbody>
EOD;
        foreach ($bots as $bot) {
            $samyBot = SamyBotPlans::whereId($bot->plan)->first();
            $html .= <<<EOD
                    <tr style="height: 30px;line-height: 30px;">
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 40%;">$samyBot->name</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$$bot->unit</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$bot->quantity</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$$bot->price</td>
                    </tr>  
EOD;
        }
        $html .= <<<EOD
                    <tr style="height: 30px;line-height: 30px;">
                        <td colspan="3">Activation Charge</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$ $bot->activation_charge</td>
                    </tr>
                    <tr style="height: 30px;line-height: 30px;">
                        <td colspan="3">Shipping Charge</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$ $bot->shipping_charge</td>
                    </tr>
                    <tr style="height: 30px;line-height: 30px;">
                        <td colspan="3">Total</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$ $bot->plan_total</td>
                    </tr> 
                    <br/><br/>
                    <tr style="height: 40px;line-height: 40px">
                        <td colspan="4" style="font-size: 20px;">Thank you for your business!</td>
                    </tr>
                </tbody>
            </table>
EOD;

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output($trans_id.'.pdf', 'D');
    }

    public function favoriteCsv($company){
        $company_details = company::whereId($company)->first();
        $favorites = DB::table('fav_user_list')->where('company_id',$company)->get();
        $Info = array();
        array_push($Info, ['Name', 'Email', 'Phone Number','MemberShip Id', 'Date']);
        foreach ($favorites as $fav) {
            $user =AppUsers::whereId($fav->user_id)->first();
            if(!empty($user)){
                if(!empty($fav->membership_id)){
                    array_push($Info,[$user->name, $user->email, $company_details->phone, $fav->membership_id, $fav->date]);
                }else{
                    array_push($Info,[$user->name, $user->email, $company_details->phone, '-', $fav->date]);
                }
            }
        }
        Excel::create('Favorite_Users', function ($excel) use ($Info) {
            $excel->setTitle('favorite Users');
            $excel->setCreator('milad')->setCompany('test');
            $excel->setDescription('My Favorites User List File');
            $excel->sheet('sheet1', function ($sheet) use ($Info) {
                $sheet->setRightToLeft(true);
                $sheet->fromArray($Info, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function prospectsCsv(){
        $company = Auth::user()->company_id;
        $company_details = company::whereId($company)->first();
        $prospects = DB::table('company_user_list')->where('company_id',$company)->get();
        $Info = array();
        array_push($Info, ['Name', 'Email', 'Phone Number','MemberShip Id','Date']);
        foreach ($prospects as $prospect) {
            $user = AppUsers::whereId($prospect->user_id)->first();
            if(!empty($user)){
                if(!empty($prospect->membership_id)){
                    array_push($Info,[$user->name, $user->email, $company_details->phone, $prospect->membership_id, $prospect->date]);
                }else{
                    array_push($Info,[$user->name, $user->email, $company_details->phone, '-', $prospect->date]);
                }
            }
        }
        Excel::create('Prospects_List', function ($excel) use ($Info) {
            $excel->setTitle('Prospects Users');
            $excel->setCreator('milad')->setCompany('test');
            $excel->setDescription('My Prospects User List File');
            $excel->sheet('sheet1', function ($sheet) use ($Info) {
                $sheet->setRightToLeft(true);
                $sheet->fromArray($Info, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function syncMailchimp($userId,$company,$listId,$type) {
        $user = User::whereId($userId)->first();
        $apiKey = 'c46af8b150965e5f1e19466c032f4ca4-us7';
        $postData = array(
            "email_address" => "$user->email",
            "status"        => "subscribed",// "subscribed","unsubscribed","cleaned","pending"
            "merge_fields"  => array(
                "NAME"          => "$user->name",
                "PHONE"         => "$user->phone"
            )
        );
        $ch = curl_init('https://us7.api.mailchimp.com/3.0/lists/'.$listId.'/members/');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization: apikey '.$apiKey,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));
        $response = json_decode(curl_exec($ch),true);
        $input = [
            'membership_id' => $response['id'],
            'list_id'       => $listId,
            'IsSynced'      => 1,
        ];
        if($type == "favorite"){
            DB::table('fav_user_list')->where('user_id',$userId)->where('company_id',$company)->update($input);
        }elseif ($type == "prospect"){
            DB::table('company_user_list')->where('user_id',$userId)->where('company_id',$company)->update($input);
        }
        curl_close($ch);
        return "success";
    }

    public function samybot_plan(){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
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

    public function register_update(Request $request){
        $companyId= Auth::user()->company_id;
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
            'country' =>$request->country
        ];
        company::whereId($companyId)->update($input);
        $transaction_id = time().rand(1,999999).'_'.$companyId;
        company::whereId($companyId)->update(['samy_bot_transaction_id'=>$transaction_id]);
        User::whereId(Auth::user()->id)->update(['samy_bot' => 1]);
        for($i = 1;$i<=$request->count;$i++){
            if(isset($request['selected_qty'.$i]) && $request['selected_qty'.$i] != "" && $request['selected_qty'.$i] != 0){
                $Input = [
                    'company_id' => $companyId,
                    'stripe_paln_id' => $request['stripe_plan'.$i],
                    'plan' => $request['selected_plan'.$i],
                    'quantity' => $request['selected_qty'.$i],
                    'price' => $request['selected_price'.$i],
                    'unit' => $request['selected_pack'.$i],
                    'plan_total' => $request['grandTotal'],
                    'transaction_id' => $transaction_id,
                    'payment_status' => 0,
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
        return redirect('samybot/payment');
    }

    public function fetch_plan_data($planId,$pack){

        $plan = SamyBotPlans::whereId($planId)->first();
        if($pack == 1){
            $amount['amount'] = $plan->amount_1;
            $amount['stripe_paln_id'] = $plan->plan_id_1;
        }else if($pack == 5){
            $amount['amount'] = $plan->amount_5;
            $amount['stripe_paln_id'] = $plan->plan_id_5;
        }else if($pack == 10){
            $amount['amount'] = $plan->amount_10;
            $amount['stripe_paln_id'] = $plan->plan_id_10;
        }else if($pack == 20){
            $amount['amount'] = $plan->amount_20;
            $amount['stripe_paln_id'] = $plan->plan_id_20;
        }
        $amount['plan'] = $plan->id;
        $amount['term'] = $plan->term;
        return $amount;
    }

    public function proceed_to_order(Request $request){
        if (Cookie::get('special_type') != '' || !empty(Cookie::get('special_type')))
        {
            $special = Cookie::get('special_type');
        }
        if (Cookie::get('special_email') != '' || !empty(Cookie::get('special_email')))
        {
            $specialEmail = Cookie::get('special_email');
        }
        $count = $request->rowCount;
        for($i = 1;$i<=$request->rowCount;$i++){
            if(isset($request['selected_qty'.$i]) && $request['selected_qty'.$i] != "" && $request['selected_qty'.$i] != 0){
                $PlansInput['stripe_plan'.$i] = $request['stripe_plan'.$i];
                $PlansInput['selected_plan'.$i] = $request['selected_plan'.$i];
                $PlansInput['selected_qty'.$i] = $request['selected_qty'.$i];
                $PlansInput['selected_price'.$i] = $request['selected_price'.$i];
                $PlansInput['selected_pack'.$i] = $request['selected_pack'.$i];
                $PlansInput['selected_plan_total'.$i] = $request['selected_plan_total'.$i];
            }
        }
        $PlansInput['number_of_devices'] = $request->number_of_devices;
        $PlansInput['grand_activation_charge'] = $request->grand_activation_charge;
        $PlansInput['final_grand_total'] = $request->final_grand_total;
        $countries = array("AF" => "Afghanistan",
            "AX" => "�land Islands",
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
        sort($countries);
        $ip =  $_SERVER['REMOTE_ADDR'];
        $json       = file_get_contents("http://ipinfo.io/{$ip}");
        $details    = json_decode($json);
        $country = $details->country;
        $code = $countryArray[$country]['code'];
        if (DB::table('activateCharge')->exists())
        {
            $activation = DB::table('activateCharge')->first();
        }
        if (DB::table('shipping')->exists())
        {
            $shipping = DB::table('shipping')->first();
        }
        return view('samybot.checkout',compact('countries','PlansInput','count','activation','shipping','code','special','specialEmail'));
    }


    public function my_prospects(){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $company_id = Auth::user()->company_id;
        if(DB::table('company_user_list')->where('company_id',$company_id)->exists())
        {
            $prospects = DB::table('company_user_list')->where('company_id',$company_id)->get();
        }
        return view('samybot.my_prospects',compact('prospects'));
    }

    public function favorite_users(){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $company_id= Auth::user()->company_id;
        if (DB::table('fav_user_list')->where('company_id',$company_id)->exists())
        {
            $favorites = DB::table('fav_user_list')->where('company_id',$company_id)->get();
        }
        return view('samybot.favorite_users',compact('favorites'));
    }

    public function searchUser($value){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $company_id = Auth::user()->company_id;
        $prospects = DB::table('company_user_list')->where('company_id', $company_id)->pluck('user_id');
        if(!empty($value) && !empty($prospects)) {
            if (AppUsers::where('name', 'like', '%' . $value . '%')->exists()) {
                $users = AppUsers::where('name', 'like', '%' . $value . '%')->whereIn('id', $prospects)->get();
            }
            if (AppUsers::where('email', 'like', '%' . $value . '%')->exists()) {
                $users = AppUsers::where('email', 'like', '%' . $value . '%')->whereIn('id', $prospects)->get();
            }
            if (!empty($users)) {
                $html = "";
                foreach ($users as $user) {
                    if (DB::table('company_user_list')->where('user_id', $user->id)->exists()) {
                        if (isset($user->photo)) {
                            $image = asset('public/avatars') . '/' . $user->photo;
                        } else {
                            $image = asset('public/avatars/default.jpg');
                        }
                        $date = date("d/m/Y", strtotime($user->created_at));
                        $html .= "<div class=\"col-md-6 col-xs-12\">
                            <div class=\"col-md-12 samy_border affiliate-container\">
                                <div class=\"col-md-4\">
                                    <img class=\"prospect_img img-circle\" src='$image'>
                                </div>
                                <div class=\"col-md-8\">
                                    <h4>$user->name</h4>
                                    <span class=\"affiliate-other-details\">$user->email</span> <br/>
                                    <span class=\"affiliate-other-details\">$date</span> <br/>
                                </div>
                            </div>
                        </div>";
                    }
                }
                return $html;
            }
        }
    }

    public function searchFavorites($value){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $company_id= Auth::user()->company_id;
        $favorites = DB::table('fav_user_list')->where('company_id',$company_id)->pluck('user_id');
        if(!empty($value) && !empty($favorites)){
            if(AppUsers::where('name','like','%'.$value.'%')->exists()){
                $users = AppUsers::where('name', 'like', '%' . $value . '%')->whereIn('id', $favorites)->get();
            }
            if(AppUsers::where('email','like','%'.$value.'%')->exists()){
                $users = AppUsers::where('email','like','%'.$value.'%')->whereIn('id', $favorites)->get();
            }
            if(!empty($users)) {
                $html = "";
                foreach ($users as $user) {
                    if (DB::table('fav_user_list')->where('user_id', $user->id)->exists()) {
                        if (isset($user->photo)) {
                            $image = asset('public/avatars') . '/' . $user->photo;
                        } else {
                            $image = asset('public/avatars/default.jpg');
                        }
                        $date = date("d/m/Y",strtotime($user->created_at));
                        $html .= "<div class=\"col-md-6 col-xs-12\">
                            <div class=\"col-md-12 samy_border affiliate-container\">
                                <div class=\"col-md-4\">
                                    <img class=\"prospect_img img-circle\" src='$image'>
                                </div>
                                <div class=\"col-md-8\">
                                    <h4>$user->name</h4>
                                    <span class=\"affiliate-other-details\">$user->email</span> <br/>
                                    <span class=\"affiliate-other-details\">$date</span> <br/>
                                </div>
                            </div>
                        </div>";
                    }
                }
                return $html;
            }
        }
    }

    public function samyBotRelease($botId,$campId){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $bot_camp = botCampaign::where('bot_id', $botId)->where('campaign_id' , $campId)->get();
        if(!empty($bot_camp)){
            botCampaign::where('bot_id', $botId)->where('campaign_id' , $campId)->delete();
        }
        bot::where('bot_id',$botId)->update(['bot_type'=>'idle']);

        return redirect('samybot/samy_bots');
    }
}
