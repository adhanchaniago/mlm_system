<?php
namespace App\Http\Controllers;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\SamyBotPlansRepository;
use App\Http\Controllers\AppBaseController;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\SamyBotPlans;
use Response;
use Flash;

class SamyBotPlansController extends AppBaseController
{
    /** @var  SamyBotPlansRepository */
    public function __construct(SamyBotPlansRepository $SamyBotPlansRepo)
    {
        $this->middleware('auth');
        $this->SamyBotPlansRepository = $SamyBotPlansRepo;
    }
    public function index()
    {
        $SamyBotPlans = SamyBotPlans::get();
        return view('SamyBotPlans.index',compact('SamyBotPlans'));
    }
    public function create()
    {
        return view('SamyBotPlans.create');
    }

    public function store(Request $request)
    {
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        if($request->term == "month"){
            $interval = "Monthly";
        }else{
            $interval = "Yearly";
        }
        if(isset($request->plan_id_1)){
            try {
                $plan = $stripe->plans()->create([
                    'id' => "$request->plan_id_1",
                    'name' => "$request->name .'-'. $interval",
                    'amount' => "$request->amount_1",
                    'currency' => 'USD',
                    'interval' => $request->term,
                ]);
                $input['plan_id_1'] = $plan['id'];
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Plan\'s must be unique.');
                return redirect()->back()->withInput();
            }
        }
        if(isset($request->plan_id_5)){
            try {
            $plan = $stripe->plans()->create([
                'id'                   => "$request->plan_id_5",
                'name'                 => "$request->name .'-'. $interval",
                'amount'               => "$request->amount_5",
                'currency'             => 'USD',
                'interval'             => $request->term,
            ]);
            $input['plan_id_5'] = $plan['id'];
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Plan\'s must be unique.');
                return redirect()->back()->withInput();
            }
        }
        if(isset($request->plan_id_10)){
            try{
            $plan = $stripe->plans()->create([
                'id'                   => "$request->plan_id_10",
                'name'                 => "$request->name .'-'. $interval",
                'amount'               => "$request->amount_10",
                'currency'             => 'USD',
                'interval'             => $request->term,
            ]);
            $input['plan_id_10'] = $plan['id'];
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Plan\'s must be unique.');
                return redirect()->back()->withInput();
            }
        }
        if(isset($request->plan_id_20)){
            try{
            $plan = $stripe->plans()->create([
                'id'                   => "$request->plan_id_20",
                'name'                 => "$request->name .'-'. $interval",
                'amount'               => "$request->amount_20",
                'currency'             => 'USD',
                'interval'             => $request->term,
            ]);
            $input['plan_id_20'] = $plan['id'];
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Plan\'s must be unique.');
                return redirect()->back()->withInput();
            }
        }
        $input = $request->except('image');
        if ($request->hasFile('image'))
        {
            $validator=Validator::make($request->all(), [
                'image' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'image.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes())
            {
                $photoName = rand(1, 777777777) . time() . '.' . $request->image->getClientOriginalExtension();
                $mime = $request->image->getClientOriginalExtension();

                $this->compress($request->image, public_path('avatars') . '/' . $photoName, 100, $mime);
                $input['image'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        SamyBotPlans::create($input);
        Flash::success(trans('plan.saved'));
        return redirect(route('SamyBotPlans.index'));
    }

    public function show($id)
    {
        $SamyBotPlans = SamyBotPlans::whereId($id)->first();
        if (empty($SamyBotPlans)) {
            Flash::error(trans('plan.error'));
            return redirect(route('SamyBotPlans.index'));
        }
        return view('SamyBotPlans.show')->with('SamyBotPlans', $SamyBotPlans);
    }

    public function edit($id)
    {
        $SamyBotPlans = SamyBotPlans::whereId($id)->first();
        if (empty($SamyBotPlans)) {
            Flash::error(trans('plan.error'));
            return redirect(route('SamyBotPlans.index'));
        }
        return view('SamyBotPlans.edit')->with('SamyBotPlans', $SamyBotPlans);
    }

    public function update($id,Request $request)
    {
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        if(isset($request->plan_id_1)){
            try {
                $plan = $stripe->plans()->find($request->plan_id_1);
                $stripe->plans()->update($request->plan_id_1, [
                    'name'                 => "$request->name",
                    'amount'               => "$request->amount_1",
                    'interval'             => $request->term,
                ]);
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Check Stripe Plans.');
                return redirect()->back()->withInput();
            }
        }
        if(isset($request->plan_id_5)){
            try {
                $plan = $stripe->plans()->find($request->plan_id_5);
                $stripe->plans()->update($request->plan_id_5, [
                    'name'                 => "$request->name",
                    'amount'               => "$request->amount_5",
                    'interval'             => $request->term,
                ]);
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Check Stripe Plans.');
                return redirect()->back()->withInput();
            }
        }
        if(isset($request->plan_id_10)){
            try{
                $plan = $stripe->plans()->find($request->plan_id_10);
                $stripe->plans()->update($request->plan_id_10, [
                    'name'                 => "$request->name",
                    'amount'               => "$request->amount_10",
                    'interval'             => $request->term,
                ]);
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Check Stripe Plans.');
                return redirect()->back()->withInput();
            }
        }
        if(isset($request->plan_id_20)){
            try{
                $plan = $stripe->plans()->find($request->plan_id_20);
                $stripe->plans()->update($request->plan_id_20, [
                    'name'                 => "$request->name",
                    'amount'               => "$request->amount_20",
                    'interval'             => $request->term,
                ]);
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Check Stripe Plans.');
                return redirect()->back()->withInput();
            }
        }
        $SamyBotPlans = SamyBotPlans::whereId($id)->first();
        if (empty($SamyBotPlans)) {
            Flash::error(trans('plan.error'));
            return redirect(route('SamyBotPlans.index'));
        }
        $update = $request->except('image','_method','_token');
        if ($request->hasFile('image'))
        {
            $validator=Validator::make($request->all(), [
                'image' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'image.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes())
            {
                $filepath = public_path('avatars' . '/' . $SamyBotPlans->image);
                $this->UnlinkImage($filepath);
                $photoName = rand(1, 777777777) . time() . '.' . $request->image->getClientOriginalExtension();
                $mime = $request->image->getClientOriginalExtension();

                $this->compress($request->image, public_path('avatars') . '/' . $photoName, 100, $mime);
                $update['image'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $SamyBotPlans = SamyBotPlans::whereId($id)->update($update);;
        Flash::success(trans('plan.update'));
        return redirect(route('SamyBotPlans.index'));
    }
    public function destroy($id)
    {
        $SamyBotPlans = SamyBotPlans::whereId($id)->first();
        if (empty($SamyBotPlans)) {
            Flash::error(trans('plan.error'));
            return redirect(route('SamyBotPlans.index'));
        }
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        $stripe->plans()->delete($SamyBotPlans->plan_id_1);
        $stripe->plans()->delete($SamyBotPlans->plan_id_5);
        $stripe->plans()->delete($SamyBotPlans->plan_id_10);
        $stripe->plans()->delete($SamyBotPlans->plan_id_20);
        SamyBotPlans::whereId($id)->delete();
        Flash::success(trans('plan.delete'));
        return redirect(route('SamyBotPlans.index'));
    }
    public function changeBotPlanStatus($id,$value)
    {
        $update['status'] = $value;
        SamyBotPlans::whereId($id)->update($update);
        return "Success";
    }
    function UnlinkImage($filepath)
    {
        $old_image = $filepath;
        if (file_exists($old_image)) {
            @unlink($old_image);
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
}