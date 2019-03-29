<?php
namespace App\Http\Controllers;
use App\Models\affiliate;
use App\Models\company;
use App\Models\plantable;
use App\Repositories\rankRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Response;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Api\Subscriptions;
use Cartalyst\Stripe\Exception\NotFoundException;
use Stripe\Error\Card;
require_once public_path('TCPDF-master/examples/tcpdf_include.php');
require_once public_path('TCPDF-master/tcpdf.php');

class MYPDF extends \TCPDF {
    // Page footer
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
//        $this->Ln();
        // Page number
//        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
//        $this->Ln();
    }
}
class cronController extends Controller
{
    /** @var  rankRepository */

    public function __construct()
    {

    }
    /**
     * Display a listing of the rank.
     *
     * @param Request $request
     * @return Response
     */
    public function planExpireCron()
    {
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        $companies = company::get();
        foreach ($companies as $company)
        {
            $user = User::where('company_id', $company->id)->first();
            if ($user->special_user != 1)
            {
                if (DB::table('companyAffiliatePlans')->where('company_id',$company->id)->exists())
                {
                    $mlmPlan = DB::table('companyAffiliatePlans')->where('company_id',$company->id)->orderby('id','desc')->first();
                    $plan = plantable::whereId($mlmPlan->planid)->first();
                    $expire = strtotime(str_replace('/','-',$mlmPlan->plan_end));

                    if (($mlmPlan->stripe_subscription_id == '' || $mlmPlan->stripe_subscription_id == null || empty($mlmPlan->stripe_subscription_id)) && $mlmPlan->auto_renewal == 0)
                    {
                        if(time() > $expire){
                            DB::table('companyAffiliatePlans')->whereId($mlmPlan->id)->update(['status' => 0,'payment' => 0]);
                            $disable['affiliate_disabled'] = 1;
                            if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '0')
                            {
                                $disable['affiliate_disabled_reason'] = '0';
                            }
                            else
                            {
                                $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'0';
                            }
                            company::whereId($company->id)->update($disable);
                            $array['name'] = $company->fname.' '.$company->lname;
                            $array['email'] =  $company->email;
                            $array['plan_name'] = $plan->type;
                            $array['planAmount'] = $plan->amount;
                            $array['planEnd'] = $mlmPlan->plan_end;
                            Mail::send('email.disabled', ['array' => $array], function ($message) use ($array) {
                                $message->to($array['email'], $array['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                            });
                        }
                        elseif (($mlmPlan->stripe_subscription_id != '' || $mlmPlan->stripe_subscription_id != null || !empty($mlmPlan->stripe_subscription_id)) && $mlmPlan->auto_renewal == 0)
                        {
                            if(time() > $expire){
                                DB::table('companyAffiliatePlans')->whereId($mlmPlan->id)->update(['status' => 0,'payment' => 0]);
                                $disable['affiliate_disabled'] = 1;
                                if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '0')
                                {
                                    $disable['affiliate_disabled_reason'] = '0';
                                }
                                else
                                {
                                    $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'0';
                                }
                                company::whereId($company->id)->update($disable);
                                $array['name'] = $company->fname.' '.$company->lname;
                                $array['email'] =  $company->email;
                                $array['plan_name'] = $plan->type;
                                $array['planAmount'] = $plan->amount;
                                $array['planEnd'] = $mlmPlan->plan_end;
                                Mail::send('email.disabled', ['array' => $array], function ($message) use ($array) {
                                    $message->to($array['email'], $array['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                                });
                            }
                        }
                        elseif (($mlmPlan->stripe_subscription_id != '' || $mlmPlan->stripe_subscription_id != null || !empty($mlmPlan->stripe_subscription_id)) && $mlmPlan->auto_renewal == 1)
                        {
                            $subscription_id = $mlmPlan->stripe_subscription_id;
                            $stripe = Stripe::make(env('STRIPE_SECRET'));
                            $subscription = $stripe->subscriptions()->find($company->stripe_id, $subscription_id);
                            if($subscription['status'] != "active" && $subscription['current_period_end'] < time())
                            {
                                DB::table('companyAffiliatePlans')->whereId($mlmPlan->id)->update(['status' => 0,'payment' => 0]);
                                $disable['affiliate_disabled'] = 1;
                                if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '0')
                                {
                                    $disable['affiliate_disabled_reason'] = '0';
                                }
                                else
                                {
                                    $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'0';
                                }
                                company::whereId($company->id)->update($disable);
                                $array['name'] = $company->fname.' '.$company->lname;
                                $array['email'] =  $company->email;
                                $array['plan_name'] = $plan->type;
                                $array['planAmount'] = $plan->amount;
                                $array['planEnd'] = $mlmPlan->plan_end;
                                Mail::send('email.disabled', ['array' => $array], function ($message) use ($array) {
                                    $message->to($array['email'], $array['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                                });
                            }
                        }

                    }

                }
            }
        }
    }

    public function autoChargeCommission()
    {
        $lastDate  = Carbon::now()->startOfMonth();
        $companies = company::get();
        foreach($companies as $company)
        {
            if (DB::table('paypal_credential')->where('company_id',$company->id)->exists())
            {

                $paypal = DB::table('paypal_credential')->where('company_id', $company->id)->first();
                if (($paypal->client_id != "" || !empty($paypal->client_id)) && ($paypal->client_secrete != "" || !empty($paypal->client_id)))
                {

                    $user = User::where('company_id', $company->id)->first();
                    if ($user->special_user != '1')
                    {
                        if (DB::table('commission')->where('company_id', $company->id)->where('payment', 0)->where('created_at', '<', $lastDate)->where('amount', '!=', '0')->exists())
                        {

                            $commissions = DB::table('commission')->where('company_id', $company->id)->where('created_at', '<', $lastDate)->where('payment', 0)->get();
                            $total = 0;
                            foreach ($commissions as $commission)
                            {
                                $total += (float)$commission->amount;
                            }
//                            return redirect('autoPay' . '/' . $company->id . '/' . $total);
                            $id = $company->id;
                            require_once public_path('PayPal-PHP-SDK/autoload.php');
                            if (DB::table('paypal_credential')->where('company_id',$company->id)->exists()) {
                                $paypal = DB::table('paypal_credential')->where('company_id', $company->id)->first();
                                if (($paypal->client_id != "" || !empty($paypal->client_id)) && ($paypal->client_secrete != "" || !empty($paypal->client_id)))
                                {
                                    $user = User::where('status', '0')->first();
                                    $paypal_email = $user->paypal_email;

                                    $payouts = new \PayPal\Api\Payout();
                                    $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
                                    $senderBatchHeader->setSenderBatchId(uniqid() . microtime(true))
                                        ->setEmailSubject("You have a payment");
                                    $senderItem = new \PayPal\Api\PayoutItem();
                                    $senderItem->setRecipientType('Email')
                                        ->setNote('Thanks you.')
                                        ->setReceiver($paypal_email)
                                        ->setSenderItemId("item_1" . uniqid() . microtime('true'))
                                        ->setAmount(new \PayPal\Api\Currency('{
                                            "value":"' . (float)$total . '",
                                             "currency":"USD"
                                             }'));
                                    $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);

                                    $request = clone $payouts;
                                    $apiContext = new \PayPal\Rest\ApiContext(
                                        new \PayPal\Auth\OAuthTokenCredential(
                                            $paypal->client_id,
                                            $paypal->client_secrete
                                        )
                                    );
                                    try {
                                        $output = $payouts->create(null, $apiContext);

                                    } catch (\PayPal\Exception\PayPalMissingCredentialException $ex) {
                                        if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '1')
                                        {
                                            $disable['affiliate_disabled_reason'] = '1';
                                        }
                                        else
                                        {
                                            $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'1';
                                        }
                                        $disable['affiliate_disabled'] = 1;
                                        company::whereId($id)->update($disable);
                                        $data['name'] = $company->fname.' '.$company->lname;
                                        $data['email'] =  $company->email;
                                        $data['total'] = $total;
                                        Mail::send('email.disabled', ['data' => $data], function ($message) use ($data) {
                                            $message->to($data['email'], $data['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                                        });
                                        continue;

                                    }catch (\PayPal\Exception\PayPalInvalidCredentialException $ex) {
                                        if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '1')
                                        {
                                            $disable['affiliate_disabled_reason'] = '1';
                                        }
                                        else
                                        {
                                            $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'1';
                                        }
                                        company::whereId($id)->update($disable);
                                        $data['name'] = $company->fname.' '.$company->lname;
                                        $data['email'] =  $company->email;
                                        $data['total'] = $total;
                                        Mail::send('email.disabled', ['data' => $data], function ($message) use ($data) {
                                            $message->to($data['email'], $data['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                                        });
                                        continue;

                                    }catch (\PayPal\Exception\PayPalConnectionException $ex) {
                                        if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '1')
                                        {
                                            $disable['affiliate_disabled_reason'] = '1';
                                        }
                                        else
                                        {
                                            $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'1';
                                        }
                                        $disable['affiliate_disabled'] = 1;
                                        company::whereId($id)->update($disable);
                                        $data['name'] = $company->fname.' '.$company->lname;
                                        $data['email'] =  $company->email;
                                        $data['total'] = $total;
                                        Mail::send('email.disabled', ['data' => $data], function ($message) use ($data) {
                                            $message->to($data['email'], $data['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                                        });
                                        continue;

                                    }catch (\PayPal\Exception\PayPalConfigurationException $ex) {
                                        if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '1')
                                        {
                                            $disable['affiliate_disabled_reason'] = '1';
                                        }
                                        else
                                        {
                                            $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'1';
                                        }
                                        $disable['affiliate_disabled'] = 1;
                                        company::whereId($id)->update($disable);
                                        $data['name'] = $company->fname.' '.$company->lname;
                                        $data['email'] =  $company->email;
                                        $data['total'] = $total;
                                        Mail::send('email.disabled', ['data' => $data], function ($message) use ($data) {
                                            $message->to($data['email'], $data['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                                        });
                                        continue;

                                    }
                                    $this->autoChargeSuccess($id,$total);
                                    if ($company->affiliate_disabled == 1 && $company->affiliate_disabled_reason == '1')
                                    {
                                        company::whereId($company->id)->update(['affiliate_disabled'=>0,'affiliate_disabled_reason'=>null]);
                                    }
                                    elseif ($company->affiliate_disabled == 1 && ($company->affiliate_disabled_reason == '1,0'|| $company->affiliate_disabled_reason == '0,1'))
                                    {
                                        company::whereId($company->id)->update(['affiliate_disabled'=>1,'affiliate_disabled_reason'=>'0']);
                                    }
                                }
                                else
                                {
                                    if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '1')
                                    {
                                        $disable['affiliate_disabled_reason'] = '1';
                                    }
                                    else
                                    {
                                        $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'1';
                                    }
                                    $disable['affiliate_disabled'] = 1;
                                    company::whereId($id)->update($disable);
                                    $data['name'] = $company->fname.' '.$company->lname;
                                    $data['email'] =  $company->email;
                                    $data['total'] = $total;
                                    Mail::send('email.disabled', ['data' => $data], function ($message) use ($data) {
                                        $message->to($data['email'], $data['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                                    });
                                    continue;
                                }
                            }
                        }
                    }
                }
                elseif (DB::table('commission')->where('company_id', $company->id)->where('payment', 0)->where('created_at', '<', $lastDate)->where('amount', '!=', '0')->exists())
                {
                    $commissions = DB::table('commission')->where('company_id', $company->id)->where('created_at', '<', $lastDate)->where('payment', 0)->get();
                    $total = 0;
                    foreach ($commissions as $commission)
                    {
                        $total += (float)$commission->amount;
                    }
                    $user = User::where('company_id',$company->id)->first();
                    if ($user->special_user != 1)
                    {
                        if ($company->affiliate_disabled_reason == '' || empty($company->affiliate_disabled_reason) || $company->affiliate_disabled_reason == '1')
                        {
                            $disable['affiliate_disabled_reason'] = '1';
                        }
                        else
                        {
                            $disable['affiliate_disabled_reason'] = $company->affiliate_disabled_reason.','.'1';
                        }
                        $disable['affiliate_disabled'] = 1;
                        company::whereId($company->id)->update($disable);
                        $data['name'] = $company->fname.' '.$company->lname;
                        $data['email'] =  $company->email;
                        $data['total'] = $total;
                        Mail::send('email.disabled', ['data' => $data], function ($message) use ($data) {
                            $message->to($data['email'], $data['name'])->from(env('MAIL_USERNAME'), 'Samy Affiliate')->subject(trans('mail.disabled'));
                        });
                        continue;
                    }
                }
            }
        }
    }


    function autoChargeSuccess($id,$total)
    {
        $company = company::whereId($id)->first();
        $lastDate  = Carbon::now()->startOfMonth();
        $orderid = time().rand(1,5685336).'_'.$id;
        $date = date('m/d/Y',time());
        $commissions = DB::table('commission')->where('company_id',$id)->where('payment',0)->where('created_at','<',$lastDate)->get();

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle($orderid);
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
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">#$orderid</td>
                </tr>
                <br/>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;font-size: 18px;"><b>Prepared for</b></td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">$company->first_name $company->last_name</td>
                </tr>
                <tr>
                    <td style="width: 100%;font-weight: lighter;font-style: normal;">$company->address</td>
                </tr>
                <tr>
                    <td style="width: 50%;font-weight: lighter;font-style: normal;">$company->city  $company->zip</td>
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
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white"><b>ITEM</b></th>
                        <th style="width: 30%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Purchase Amount</th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Purchase Date</th>
                        <th style="width: 20%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Commission</th>
                        <th style="width: 10%;font-family: docs-Merriweather;border: 1px solid #f3f3f3;color: white;text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>             
EOD;
        foreach ($commissions as $commission) {
            $amount = (float)$commission->amount;
            $price = (float)$commission->price;
            $purchase_date = date('m/d/Y', strtotime($commission->created_at));
            $update['payment'] = 1;
            DB::table('commission')->whereId($commission->id)->update($update);
            if ($amount != 0) {
                $html .= <<<EOD
                    <tr style="height: 40px;line-height: 40px;">
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;">Commission</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$$price</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 30%;text-align: right">$purchase_date</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 20%;text-align: right">$commission->commission%</td>
                        <td style="color: #666666; border:1px solid #f3f3f3; width: 10%;text-align: right">$$commission->amount</td>
                    </tr>
                   
EOD;
            }
        }
        $html .= <<<EOD
                    <tr style="height: 40px;line-height: 40px">
                        <td colspan="5" style="width: 100%;background-color: #f3f3f3;text-align: right;font-size: 24px"><b>$$total</b></td>
                    </tr>
                    <br/><br/>
                    <tr style="height: 40px;line-height: 40px">
                        <td colspan="5" style="font-size: 20px;">Thank you for your business!</td>
                    </tr>
</tbody>
            </table>
EOD;

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output(public_path('pdf/' . $orderid . '.pdf'), 'F');
//        $pdf->output();
        $array['name'] = $company->fname.' '.$company->lname;
        $array['total'] = $total;
        $array['email'] = $company->email;
        $array['pathToFile']=asset('public/pdf'.'/'.$orderid.'.pdf');
        Mail::send('email.autopay', ['array' => $array], function ($message) use($array)
        {
            $attachment=$message->attach($array['pathToFile'],array(
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf'));
//            $attachment->setFilename('invoice.pdf');
//            $message->to($array['email'], $array['name'])->subject('Commission Auto Charge');
            $message->to($array['email'], $array['name'])->subject(trans('mail.commission_charge'));
        });
    }


    public function resetStats()
    {
        $affiliates = affiliate::get();
        $reset['current_revenue'] = 0;
        $reset['payout'] = 0;
        $update['current_revenue'] = 0;
        $reset['rankid'] = 0;
        foreach ($affiliates as $affiliate)
        {
            User::where('affiliate_id',$affiliate->id)->update($update);
            affiliate::whereId($affiliate->id)->update($reset);
        }
    }

}
