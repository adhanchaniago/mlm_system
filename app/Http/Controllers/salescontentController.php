<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreatesalescontentRequest;
use App\Http\Requests\UpdatesalescontentRequest;
use App\Repositories\salescontentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\DataTables\salescontentDataTable;
use Illuminate\Contracts\Validation\Validator;
class salescontentController extends AppBaseController
{
    /** @var  salescontentRepository */
    private $salescontentRepository;
    public function __construct(salescontentRepository $salescontentRepo)
    {
	    $this->middleware('auth');
        $this->salescontentRepository = $salescontentRepo;
    }
    /**
     * Display a listing of the salescontent.
     *
     * @param Request $request
     * @return Response
     */
    public function index(salescontentDataTable $salescontentDataTable)
    {
        return $salescontentDataTable->render('salescontents.index');
    }
    /**
     * Show the form for creating a new salescontent.
     *
     * @return Response
     */
    public function create()
    {
        return view('salescontents.create');
    }
    /**
     * Store a newly created salescontent in storage.
     *
     * @param CreatesalescontentRequest $request
     *
     * @return Response
     */
    public function store(CreatesalescontentRequest $request)
    {
        $input = $request->all();
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
                $request->image->move(public_path('salesContents'), $photoName);
                $input['image'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
	    }
        $salescontent = $this->salescontentRepository->create($input);
        Flash::success(trans('sales.saved'));
        return redirect(route('salescontents.index'));
    }
    /**
     * Display the specified salescontent.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $salescontent = $this->salescontentRepository->findWithoutFail($id);
        if (empty($salescontent)) {
            Flash::error(trans('sales.error'));
            return redirect(route('salescontents.index'));
        }
        return view('salescontents.show')->with('salescontent', $salescontent);
    }
    /**
     * Show the form for editing the specified salescontent.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $salescontent = $this->salescontentRepository->findWithoutFail($id);
        if (empty($salescontent)) {
            Flash::error(trans('sales.error'));
            return redirect(route('salescontents.index'));
        }
        return view('salescontents.edit')->with('salescontent', $salescontent);
    }
    /**
     * Update the specified salescontent in storage.
     *
     * @param  int              $id
     * @param UpdatesalescontentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatesalescontentRequest $request)
    {
        $salescontent = $this->salescontentRepository->findWithoutFail($id);
        if (empty($salescontent)) {
            Flash::error(trans('sales.error'));
            return redirect(route('salescontents.index'));
        }
        $input=$request->all();

	    if(isset($request->video) && $request->video !=""){
		    $input['image']="";
	    }
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
                $filepath = public_path('avatars' . '/' . $salescontent->image);
                $this->UnlinkImage($filepath);
                $photoName = rand(1, 777777777) . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('salesContents'), $photoName);
                $input['image'] = $photoName;
                $input['video'] = "";
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
	    }
        $salescontent = $this->salescontentRepository->update($input, $id);
        Flash::success(trans('sales.update'));
        return redirect(route('salescontents.index'));
    }
    /**
     * Remove the specified salescontent from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $salescontent = $this->salescontentRepository->findWithoutFail($id);
        if (empty($salescontent)) {
            Flash::error(trans('sales.error'));
            return redirect(route('salescontents.index'));
        }
        $this->salescontentRepository->delete($id);
        Flash::success(trans('sales.delete'));
        return redirect(route('salescontents.index'));
    }
    function UnlinkImage($filepath)
    {
        $old_image = $filepath;
        if (file_exists($old_image)) {
            @unlink($old_image);
        }
    }
}
