<?php
namespace App\Http\Controllers;
use App\DataTables\plantableDataTable;
use App\Http\Requests\CreateplantableRequest;
use App\Http\Requests\UpdateplantableRequest;
use App\Repositories\plantableRepository;
use App\Http\Controllers\AppBaseController;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\Validator;
class plantableController extends AppBaseController
{
    /** @var  plantableRepository */
    private $plantableRepository;
    public function __construct(plantableRepository $plantableRepo)
    {
	    $this->middleware('auth');
        $this->plantableRepository = $plantableRepo;
    }
    /**     * Display a listing of the plantable.     *     * @param Request $request * @return Response */
    public function index(plantableDataTable $plantableDataTable)
    {
        return $plantableDataTable->render('plantables.index');
        $this->plantableRepository->pushCriteria(new RequestCriteria($request));
        $plantables = $this->plantableRepository->all();
        return view('plantables.index')->with('plantables', $plantables);
    }
    /**     * Show the form for creating a new plantable.     *
     * @return Response
     */
    public function create()
    {
        return view('plantables.create');
    }
    /**     * Store a newly created plantable in storage.
     *     * @param CreateplantableRequest $request
     *     * @return Response
     */
    public function store(CreateplantableRequest $request)
    {
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
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        if(isset($request->stripe_plan_id)){
            try {
                $plan = $stripe->plans()->create([
                    'id' => "$request->stripe_plan_id",
                    'name' => "$request->name .'-'. $request->type",
                    'amount' => "$request->amount",
                    'currency' => 'USD',
                    'interval' => $request->term,
                ]);
                $input['stripe_plan_id'] = $plan['id'];
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Check Stripe Plans.');
                return redirect()->back()->withInput();
            }
        }
        $plantable = $this->plantableRepository->create($input);
        Flash::success(trans('plan.saved'));
        return redirect(route('plantables.index'));
    }
    /**     * Display the specified plantable.     *
     * @param  int $id *
     * @return Response
     */
    public function show($id)
    {
        $plantable = $this->plantableRepository->findWithoutFail($id);
        if (empty($plantable)) {
            Flash::error(trans('plan.error'));
            return redirect(route('plantables.index'));
        }
        return view('plantables.show')->with('plantable', $plantable);
    }
    /**     * Show the form for editing the specified plantable.     *
     *     * @param  int $id *
     * * @return Response
     */
    public function edit($id)
    {
        $plantable = $this->plantableRepository->findWithoutFail($id);
        if (empty($plantable)) {
            Flash::error(trans('plan.error'));
            return redirect(route('plantables.index'));
        }
        return view('plantables.edit')->with('plantable', $plantable);
    }
    /**     * Update the specified plantable in storage.     *
     * @param  int
     * $id
     * @param UpdateplantableRequest $request
     *     * @return Response
     */
    public function update($id, UpdateplantableRequest $request)
    {
        $plantable = $this->plantableRepository->findWithoutFail($id);
        if (empty($plantable)) {
            Flash::error(trans('plan.error'));
            return redirect(route('plantables.index'));
        }
        $update = $request->except('image');
        if ($request->hasFile('image')) {
            $validator=Validator::make($request->all(), [
                'image' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'image.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes())
            {
                $filepath = public_path('avatars' . '/' . $plantable->image);
                $this->UnlinkImage($filepath);
                $photoName = rand(1, 777777777) . time() . '.' . $request->image->getClientOriginalExtension();
                $mime = $request->image->getClientOriginalExtension();

                $this->compress($request->image, public_path('avatars') . '/' . $photoName, 100, $mime);
                $update['image'] = $photoName;
            }
            else
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        if(isset($request->stripe_plan_id)){
            try {
                $plan = $stripe->plans()->find($request->stripe_plan_id);
                $stripe->plans()->update($request->stripe_plan_id, [
                    'name'                 => "$request->name $request->type",
                    'amount'               => "$request->amount",
                    'interval'             => $request->term,
                ]);
            }catch (\Exception $exception){
                Flash::error('Something went wrong! Check Stripe Plans.');
                return redirect()->back()->withInput();
            }
        }
        $plantable = $this->plantableRepository->update($update, $id);
        Flash::success(trans('plan.update'));
        return redirect(route('plantables.index'));
    }
    /**     * Remove the specified plantable from storage.     *
     * @param  int $id *     * @return Response
     * */
    public function destroy($id)
    {
        $plantable = $this->plantableRepository->findWithoutFail($id);
        if (empty($plantable)) {
            Flash::error(trans('plan.error'));
            return redirect(route('plantables.index'));
        }
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        $plan = $stripe->plans()->delete($plantable->stripe_id);
        $this->plantableRepository->delete($id);
        Flash::success(trans('plan.delete'));
        return redirect(route('plantables.index'));
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