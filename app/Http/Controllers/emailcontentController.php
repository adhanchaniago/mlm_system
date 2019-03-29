<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreateemailcontentRequest;
use App\Http\Requests\UpdateemailcontentRequest;
use App\Models\emailcontent;
use App\Repositories\emailcontentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\company;
use App\User;
use App\DataTables\emailcontentDataTable;
use DB;
use Validator;

class emailcontentController extends AppBaseController
{
    /** @var  emailcontentRepository */
    private $emailcontentRepository;
    public function __construct(emailcontentRepository $emailcontentRepo)
    {
	    $this->middleware('auth');
        $this->emailcontentRepository = $emailcontentRepo;
    }
    /**
     * Display a listing of the emailcontent.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $id = Auth::user()->company_id;
        $emailcontent = emailcontent::where('company_id',$id)->first();
        return view('emailcontents.create',compact('emailcontent','id'));
    }
    /**
     * Show the form for creating a new emailcontent.
     *
     * @return Response
     */
    public function create()
    {
        return view('emailcontents.create');
    }
    /**
     * Store a newly created emailcontent in storage.
     *
     * @param CreateemailcontentRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $id = Auth::user()->company_id;
        $input = $request->except('_token');
        if (emailcontent::where('company_id',$id)->exists())
        {
            $email = emailcontent::where('company_id',$id)->first();
            emailcontent::whereId($email->id)->update($input);
        }
        else
        {
            emailcontent::create($input);
        }
        return redirect(route('emailcontents.index'));
    }
    /**
     * Display the specified emailcontent.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $emailcontent = $this->emailcontentRepository->findWithoutFail($id);
        if (empty($emailcontent)) {
            Flash::error(trans('emailcontent.error'));
            return redirect(route('emailcontents.index'));
        }
        return view('emailcontents.show')->with('emailcontent', $emailcontent);
    }
    /**
     * Show the form for editing the specified emailcontent.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit()
    {
        $id = Auth::user()->company_id;
        $emailcontent = emailcontent::where('company_id',$id)->first();
        return view('emailcontents.edit')->with('emailcontent', $emailcontent);
    }
    /**
     * Update the specified emailcontent in storage.
     *
     * @param  int              $id
     * @param UpdateemailcontentRequest $request
     *
     * @return Response
     */
    public function update($id,Request $request)
    {
        $companyId = Auth::user()->company_id;
        $input = $request->except('_token');
        if (emailcontent::where('company_id',$companyId)->exists())
        {
            $email = emailcontent::where('company_id',$companyId)->first();
            emailcontent::whereId($email->id)->update($input);
        }
        else
        {
            emailcontent::create($input);
        }
        return redirect('emailcontentsedit');
    }
    /**
     * Remove the specified emailcontent from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $emailcontent = $this->emailcontentRepository->findWithoutFail($id);
        if (empty($emailcontent)) {
            Flash::error(trans('emailcontent.error'));
            return redirect(route('emailcontents.index'));
        }
        $this->emailcontentRepository->delete($id);
        Flash::success(trans('emailcontent.delete'));
        return redirect(route('emailcontents.index'));
    }

    public function mailchimpCreate(Request $request){
        $companyId=Auth::user()->company_id;
        $apikey      = $request->api_key; // replace with your API key
        $data_center = $request->data_center; // replace with your API key
        $data = array(
            'apikey' => $apikey,
        );
        $ch = curl_init( 'https://'.$data_center.'.api.mailchimp.com/3.0/lists' );
        $auth = base64_encode( 'user:'.$apikey );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$auth));
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
        $response = json_decode(curl_exec( $ch ),true);
        echo "<pre>";
        $array = $response['lists'];
        if(empty($array)){
            Flash::success('No lists Found. Check Your Api Key or Create Lists.');
            return redirect('mailchimp/index');
        }
        foreach ($array as $arr){
            $lists[] = $arr['id'];
            $Input = [
                'api_key'       => $apikey,
                'data_center'   => $data_center,
                'list_id'       => $arr['id'],
                'name'          => $arr['name'],
                'web_id'        => $arr['web_id'],
                'company_id'    => $companyId,
                'date'          => date('d-m-Y')
            ];
            DB::table('company_mailchimp_list')->insert($Input);
        }
        curl_close( $ch );
        Flash::success(trans('Created Successfully'));
        return redirect('mailchimp/index');
    }
    public function mailchimp(){
        $companyId=Auth::user()->company_id;
        $mailChimp = DB::table('company_mailchimp_list')->where('company_id',$companyId)->get();
        $apiData = DB::table('company_mailchimp_list')->where('company_id',$companyId)->first();
        if(empty($mailChimp) || $mailChimp->count() <= 0){
            return view('frontEnd.mailchimp');
        }else{
            return view('frontEnd.mailchimp',compact('mailChimp','apiData'));
        }
    }
    public function mailchimpUpdate(Request $request){
        $companyId=Auth::user()->company_id;
        $apikey      = $request->api_key;
        $data_center = $request->data_center;
        $data = array(
            'apikey' => $apikey,
        );
        $ch = curl_init( 'https://'.$data_center.'.api.mailchimp.com/3.0/lists' );
        $auth = base64_encode( 'user:'.$apikey );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$auth));
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
        $response = json_decode(curl_exec( $ch ),true);
        $array = $response['lists'];
        foreach ($array as $arr){
            $lists[] = $arr['id'];
            $Input = [
                'api_key'       => $apikey,
                'data_center'   => $data_center,
                'list_id'       => $arr['id'],
                'name'          => $arr['name'],
                'web_id'        => $arr['web_id'],
                'company_id'    => $companyId,
                'date'          => date('d-m-Y')
            ];
            $apiData = DB::table('company_mailchimp_list')->where('company_id',$companyId)->where('list_id',$arr['id'])->first();
            if(empty($apiData)){
                DB::table('company_mailchimp_list')->insert($Input);
            }
        }
        curl_close( $ch );
        $Input = [
            'api_key'       => $apikey,
            'data_center'   => $data_center,
        ];
        DB::table('company_mailchimp_list')->update($Input);

        $fav_list  = $request->fav_list;
        $pros_list = $request->pros_list;
        if(!empty($fav_list)){
            DB::table('company_mailchimp_list')->where('company_id',$companyId)->update(['Is_favorite' => 0]);
            DB::table('company_mailchimp_list')->where('company_id',$companyId)->where('list_id',$fav_list)->update(['Is_favorite' => 1]);
        }
        if(!empty($pros_list)) {
            DB::table('company_mailchimp_list')->where('company_id', $companyId)->update(['Is_Prospect' => 0]);
            DB::table('company_mailchimp_list')->where('company_id', $companyId)->where('list_id', $pros_list)->update(['Is_Prospect' => 1]);
        }
        Flash::success(trans('Updated Successfully'));
        return redirect('mailchimp/index');
    }
    public function mailList_Creation(Request $request){
        $email_type_option = $request->email_type_option == '1' ? true : false;
        $company  = company::whereId($request->company_id)->first();

        $apikey = 'c46af8b150965e5f1e19466c032f4ca4-us7'; // replace with your API key
        $data = array( // the information for your new list--not all is required
            "name"      => $request->list_name,
            "contact"   => array(
                "company"   => $company->admin_name,
                "address1"  => $company->bill_address,
                "address2"  => $company->address2,
                "city"      => $company->city,
                "state"     => $company->state,
                "zip"       => $company->zip,
                "country"   => $company->country,
                "phone"     => $company->phno
            ),
            "permission_reminder"   => $request->permission_reminder,
            "use_archive_bar"       => true,
            "campaign_defaults"     => array(
                "from_name"         => $request->from_name,
                "from_email"        => $request->from_email,
                "subject"           => $request->subject,
                "language"          => "en"
            ),
            "notify_on_subscribe"   => $request->notify_on_subscribe,
            "notify_on_unsubscribe" => $request->notify_on_unsubscribe,
            "email_type_option"     => $email_type_option,
            "visibility"            => $request->visibility
        );
        $ch = curl_init('https://us7.api.mailchimp.com/3.0/lists');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization: apikey '.$apikey,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS =>  json_encode($data)
        ));
        // Send the request
        $response = json_decode(curl_exec($ch),true);
        $Input = [
          'list_id' => $response['id'],
          'name' => $response['name'],
          'web_id' => $response['web_id'],
          'company_id' => $company->id,
          'date' => date('d-m-Y'),
          'visibility' => $request->visibility
        ];
        DB::table('company_mailchimp_list')->insert($Input);
        curl_close($ch);
        return redirect('mailchimp/index');
    }
}
