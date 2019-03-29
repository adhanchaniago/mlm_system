<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreatepayouthistoryRequest;
use App\Http\Requests\UpdatepayouthistoryRequest;
use App\Models\affiliate;
use App\Models\company;
use App\Models\payouthistory;
use App\Models\rank;
use App\Repositories\payouthistoryRepository;
use App\Http\Controllers\AppBaseController;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PayPal\Api\ItemList;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\DataTables\payouthistoryDataTable;
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
class payouthistoryController extends AppBaseController
{
    /** @var  payouthistoryRepository */
    private $payouthistoryRepository;
    public function __construct(payouthistoryRepository $payouthistoryRepo)
    {
        $this->payouthistoryRepository = $payouthistoryRepo;
    }
    /**
     * Display a listing of the payouthistory.
     *
     * @param Request $request
     * @return Response
     */
    public function index(payouthistoryDataTable $payouthistoryDataTable)
    {
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $companyId = Auth::user()->company_id;
        $company = company::whereId($companyId)->first();
        $plansTable = DB::table('companyAffiliatePlans')->where('company_id',$companyId)->orderby('id','desc')->first();
        $months = array(
            '1' => trans('home.jan'),
            '2' => trans('home.feb'),
            '3' => trans('home.mar'),
            '4' => trans('home.apr'),
            '5' => trans('home.may'),
            '6' => trans('home.jun'),
            '7' => trans('home.jul'),
            '8' => trans('home.aug'),
            '9' => trans('home.sep'),
            '10' => trans('home.oct'),
            '11' => trans('home.nov'),
            '12' => trans('home.dec'),
        );
        $this_month = date('m',time());
        $this_year = date('Y',time());
        if ($this_month == '01')
        {
            $prev_month = 12;
            $prev_year = $this_year-1;
        }
        else
        {
            $prev_year = $this_year;
            if ($this_month[0]==0)
            {
                $prev_month = $this_month-1;
                $prev_month = '0'.$prev_month;
            }
            else
            {
                $prev_month = $this_month-1;
            }

        }
        $k = $prev_month+1-1;
        $month_name = $months[$k];
        if ($this_month == '01')
        {
            if (payouthistory::where('company_id', $companyId)->where('month', $prev_month)->where('year', $prev_year)->exists())
            {
                $prev_month_payouts = payouthistory::where('company_id', $companyId)->where('month', $prev_month)->where('year', $prev_year)->get();
                $prev_count = payouthistory::where('company_id', $companyId)->where('month', $prev_month)->where('year', $prev_year)->distinct()->count('affiliate_id');
                $prev_total = 0;
                foreach ($prev_month_payouts as $prev_month_payout)
                {
                    $prev_total +=$prev_month_payout->amount;
                }
            }
            else
            {
                $prev_month_payouts = [];
                $prev_count = 0;
                $prev_total = 0;
            }
        }
        else
        {
            if (payouthistory::where('company_id', $companyId)->where('month', $prev_month)->where('year', $this_year)->exists())
            {
                $prev_month_payouts = payouthistory::where('company_id', $companyId)->where('month', $prev_month)->where('year', $this_year)->get();
                $prev_count = payouthistory::where('company_id', $companyId)->where('month', $prev_month)->where('year', $prev_year)->distinct()->count('affiliate_id');
                $prev_total = 0;
                foreach ($prev_month_payouts as $prev_month_payout)
                {
                    $prev_total +=$prev_month_payout->amount;
                }
            }
            else
            {
                $prev_month_payouts = [];
                $prev_count = 0;
                $prev_total = 0;
            }
        }
        if (DB::table('paypal_credential')->where('company_id',$companyId)->exists())
        {
            $paypal = DB::table('paypal_credential')->where('company_id',$companyId)->first();
        }
        else
        {
            $paypal = "";
        }
        $prev_total = number_format($prev_total);
        if (DB::table('payout_type')->where('company_id',$companyId)->exists())
        {
            $payout_type = DB::table('payout_type')->where('company_id',$companyId)->first();
        }
        if ($plansTable->payment == 0)
        {
            return redirect('stripe');
        }
        elseif (Auth::user()->activated==0)
        {
            return redirect('confirmEmail');
        }
        elseif($company->affiliate_disabled==1)
        {
            return view('frontEnd.disabled');
        }
        elseif (Auth::user()->profile == 0)
        {
            return redirect('myProfile');
        }
        if (affiliate::where('company_id',$companyId)->where('payout',0)->where('rankid','!=',0)->exists())
        {
            $affiliates = affiliate::where('company_id',$companyId)->where('payout',0)->where('rankid','!=',0)->get();
        }
        else
        {
            $affiliates = "";
        }
        return $payouthistoryDataTable->render('payouthistories.index',compact('payouts','this_month_payouts','prev_month_payouts','this_month','this_year','prev_month','prev_year','prev_count','prev_total','months','month_name','payout_type','affiliates','paypal','companyId'));
    }
    /**
     * Show the form for creating a new payouthistory.
     *
     * @return Response
     */
    public function create()
    {
        return view('payouthistories.create');
    }
    /**
     * Store a newly created payouthistory in storage.
     *
     * @param CreatepayouthistoryRequest $request
     *
     * @return Response
     */
    public function store(CreatepayouthistoryRequest $request)
    {
        $input = $request->all();
        $payouthistory = $this->payouthistoryRepository->create($input);
        Flash::success(trans('payout.saved'));
        return redirect(route('payouthistories.index'));
    }
    /**
     * Display the specified payouthistory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $payouthistory = $this->payouthistoryRepository->findWithoutFail($id);
        if (empty($payouthistory)) {
            Flash::error(trans('payout.error'));
            return redirect(route('payouthistories.index'));
        }
        return view('payouthistories.show')->with('payouthistory', $payouthistory);
    }
    /**
     * Show the form for editing the specified payouthistory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $payouthistory = $this->payouthistoryRepository->findWithoutFail($id);
        if (empty($payouthistory)) {
            Flash::error(trans('payout.error'));
            return redirect(route('payouthistories.index'));
        }
        return view('payouthistories.edit')->with('payouthistory', $payouthistory);
    }
    /**
     * Update the specified payouthistory in storage.
     *
     * @param  int              $id
     * @param UpdatepayouthistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatepayouthistoryRequest $request)
    {
        $payouthistory = $this->payouthistoryRepository->findWithoutFail($id);
        if (empty($payouthistory)) {
            Flash::error(trans('payout.error'));
            return redirect(route('payouthistories.index'));
        }
        $payouthistory = $this->payouthistoryRepository->update($request->all(), $id);
        Flash::success(trans('payout.update'));
        return redirect(route('payouthistories.index'));
    }
    /**
     * Remove the specified payouthistory from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $payouthistory = $this->payouthistoryRepository->findWithoutFail($id);
        if (empty($payouthistory)) {
            Flash::error(trans('payout.error'));
            return redirect(route('payouthistories.index'));
        }
        $this->payouthistoryRepository->delete($id);
        Flash::success(trans('payout.delete'));
        return redirect(route('payouthistories.index'));
    }
    public function monthlyBreakdownCsv($month,$year,$monthName)
    {
        $cid = Auth::user()->company_id;
        if (payouthistory::where('company_id', $cid)->where('month', $month)->where('year', $year)->exists())
        {
            $payouts = payouthistory::where('company_id', $cid)->where('month', $month)->where('year', $year)->get();
        }
        else
        {
            $payouts = "";
        }
        $Info = array();
        array_push($Info, ['Name', 'Email', 'Rank', 'Amount']);
        foreach ($payouts as $payout) {
            $affiliate_details = \App\Models\affiliate::whereId($payout->affiliate_id)->first();
            if (\App\Models\rank::where('company_id', $affiliate_details->company_id)->where('rank', $payout->rankid)->exists()) {
                $rank_details = \App\Models\rank::where('company_id', $affiliate_details->company_id)->where('rank', $payout->rankid)->first();
            } else {
                $rank_details = "";
            }
            if ($rank_details != "") {
                array_push($Info, [$affiliate_details->name, $affiliate_details->email, $rank_details->name, $payout->amount]);
            } else {
                array_push($Info, [$affiliate_details->name, $affiliate_details->email, '-', $payout->amount]);
            }
        }
        Excel::create($monthName.'_Payouts_Breakdown', function ($excel) use ($Info) {
            $excel->setTitle('Users');
            $excel->setCreator('milad')->setCompany('Test');
            $excel->setDescription('users file');
            $excel->sheet('sheet1', function ($sheet) use ($Info) {
                $sheet->setRightToLeft(true);
                $sheet->fromArray($Info, null, 'A1', false, false);
            });

        })->download('csv');

    }
    public function monthlyBreakdownPdf($month,$year,$monthName)
    {
        $cid = Auth::user()->company_id;
        if (payouthistory::where('company_id', $cid)->where('month', $month)->where('year', $year)->exists())
        {
            $payouts = payouthistory::where('company_id', $cid)->where('month', $month)->where('year', $year)->get();
        }
        else
        {
            $payouts = "";
        }
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle($monthName.'_Payout_Breakdown');
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
        
                 
                   <table style="width: 100%">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 25%">Name</th>
                            <th style="width: 25%">Email</th>
                            <th style="width: 15%">Rank</th>
                            <th style="width: 10%">Amount</th>
                            <th style="width: 15%">Date</th>
                        </tr>
                    
<br/>
<br/>
EOD;
        if($payouts != "")
        {
            $k=1;
            foreach ($payouts as $payout)
            {
                $date = date('m/d/Y',strtotime($payout->created_at));
                $amount = number_format($payout->amount);
                $affiliate =affiliate::whereId($payout->affiliate_id)->first();
                if (\App\Models\rank::where('company_id', $affiliate->company_id)->where('rank', $payout->rankid)->exists()) {
                    $rank_details = \App\Models\rank::where('company_id', $affiliate->company_id)->where('rank', $payout->rankid)->first();
                } else {
                    $rank_details = "";
                }
                $html .= <<<EOD
                    <tr>
                        <td style="width: 5%">$k</td>
                        <td style="width: 25%">$affiliate->name</td>
                        <td style="width: 25%">$affiliate->email</td>
EOD;
                if ($rank_details != "")
                {
                    $html .= <<<EOD
                        <td style="width: 15%">$rank_details->name</td>      
EOD;
                }
                else
                {
                    $html .= <<<EOD
                    <td style="width: 15%">-</td>
EOD;
                }
                $html .= <<<EOD
                        <td style="width: 10%">$amount</td>
                        <td style="width: 15%">$date</td>
                    </tr>
    

EOD;
                $k++;
            }
        }



        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output($monthName.'_Payout_Breakdown.pdf', 'D');
    }
    public function savePayoutMethod(Request $request)
    {
        $cid = Auth::user()->company_id;
        $input['company_id'] = $cid;
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
        if(DB::table('payout_type')->where('company_id',$cid)->exists())
        {
            DB::table('payout_type')->where('company_id',$cid)->update($input);
        }
        else
        {
            DB::table('payout_type')->insert($input);
        }
        return redirect()->back();
    }
    public function manualPayout()
    {
        $cid = Auth::user()->company_id;
        $affiliates = affiliate::where('company_id',$cid)->where('payout',0)->where('rankid','!=',0)->get();
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle(''.trans('payout.manual_payout').'');
        $pdf->SetSubject(''.trans('payout.affiliates_payout').'');
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
        
                 
                   <table style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 25%">Name</th>
                            <th style="width: 25%">Revenue</th>
                            <th style="width: 25%">Rank</th>
                            <th style="width: 25%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    
<br/>
<br/>
EOD;

        $k=1;
        foreach ($affiliates as $affiliate)
        {
            $affUser = User::where('affiliate_id',$affiliate->id)->first();
            if (DB::table('bot_plans')->where('company_id',$affUser->company_id)->where('payment_status',1)->exists()==0)
            {
                continue;
            }
            $rank = rank::where('company_id',$cid)->where('rank',$affiliate->rankid)->first();
            $revenue = number_format($affiliate->current_revenue);
            $payout = number_format($rank->payout_amount);
            $html .= <<<EOD
                    <tr>
                        <td style="width: 5%">$k</td>
                        <td style="width: 25%">$affiliate->name</td>
                        <td style="width: 25%">$$revenue</td>
                        <td style="width: 25%">$rank->name</td>
                        <td style="width: 25%">$$payout</td>
                    </tr>
    

EOD;
            $k++;
            $update['payout'] = 1;
            $payoutHistory['company_id'] = $cid;
            $payoutHistory['amount'] = $rank->payout_amount;
            $payoutHistory['affiliate_id'] = $affiliate->id;
            $payoutHistory['rankid'] = $rank->rank;
            $payoutHistory['month'] = date('m',time());
            $payoutHistory['year'] = date('Y',time());
            payouthistory::create($payoutHistory);
            affiliate::whereId($affiliate->id)->update($update);
        }
        $html .= <<<EOD
        </tbody>
</table>
EOD;



        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output(''.trans('payout.manual_payout').'.pdf', 'D');
        return redirect()->back();
    }


    public function paypalPayout()
    {
        $cid = Auth::user()->company_id;
        require_once public_path('PayPal-PHP-SDK/autoload.php');

        if (affiliate::where('company_id',$cid)->where('payout',0)->where('rankid','!=',0)->exists())
        {
            $affiliates = affiliate::where('company_id',$cid)->where('payout',0)->where('rankid','!=',0)->get();
        }
        else
        {
            $affiliates = "";
        }
        if (DB::table('paypal_credential')->where('company_id',$cid)->exists())
        {
            $paypal = DB::table('paypal_credential')->where('company_id',$cid)->first();
        }
        else
        {
            $paypal = "";
        }

        if($paypal != "" && $paypal->client_id != "" && $paypal->client_secrete != "")
        {
            if ($affiliates != "")
            {
                $payouts = new \PayPal\Api\Payout();
                $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
                $senderBatchHeader->setSenderBatchId(uniqid().microtime(true))
                    ->setEmailSubject("You have a payment");
                $i=1;
                $affiliate_id=[];
                foreach ($affiliates as $affiliate)
                {
                    if ($affiliate->paypal_email != '' || !empty($affiliate->paypal_email))
                    {
                        $rank = rank::where('company_id', $cid)->where('rank',$affiliate->rankid)->first();
                        return $rank->payout_amount;
                        $senderItem[$i] = new \PayPal\Api\PayoutItem();
                        $senderItem[$i]->setRecipientType('Email')
                            ->setNote('Thanks you.')
                            ->setReceiver($affiliate->paypal_email)
                            ->setSenderItemId("item_1" . uniqid().microtime('true'))
                            ->setAmount(new \PayPal\Api\Currency('{
                        "value":"'.(float)$rank->payout_amount.'",
                        "currency":"USD"
                    }'));
                        $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem[$i]);
                        $details = array(['id'=>$affiliate->id,'amount'=>(float)$rank->payout_amount,'rankid'=>$rank->rank]);
                        array_push($affiliate_id,$details);
                    }
                    $i++;
                }
                $request = clone $payouts;
                $apiContext = new \PayPal\Rest\ApiContext(
                    new \PayPal\Auth\OAuthTokenCredential(
                        $paypal->client_id,
                        $paypal->client_secrete
                    )
                );
                try {
                    $output = $payouts->create(null, $apiContext);
                    $k=0;
                    $update['payout'] = 1;
                    $update['last_payout_batch'] = $output->getBatchHeader()->getPayoutBatchId();
                    foreach ($affiliate_id as $affiliate_ids)
                    {
                        affiliate::whereId($affiliate_ids[0]['id'])->update($update);
                        $payoutHistory['paypal_batchid']=$output->getBatchHeader()->getPayoutBatchId();
                        $payoutHistory['company_id']=$cid;
                        $payoutHistory['affiliate_id']=$affiliate_ids[0]['id'];
                        $payoutHistory['rankid']=$affiliate_ids[0]['rankid'];
                        $payoutHistory['amount']=$affiliate_ids[0]['amount'];
                        $payoutHistory['month']=date('m',time());
                        $payoutHistory['year']=date('Y',time());
                        payouthistory::create($payoutHistory);
                    }
                } catch (Exception $ex) {
//                    return $ex->getMessage();
                    payouthistory::where('paypal_batchid',$output->getBatchHeader()->getPayoutBatchId())->forcedelete();
                    $update['payout'] = 0;
                    affiliate::where('last_payout_batch',$output->getBatchHeader()->getPayoutBatchId())->update($update);
                    Flash::error(trans('payout.payout_error'));
                    return redirect()->back();

                }
                Flash::success(trans('payout.payout_success'));
                return redirect()->back();


            }
            Flash::success(trans('payout.payout_empty'));
            return redirect()->back();
        }
        Flash::error(trans('payout.payout_auth_error'));
        return redirect()->back();


    }

}
