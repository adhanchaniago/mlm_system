<?php

namespace App\Http\Controllers;

use App\DataTables\frontPageDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatefrontPageRequest;
use App\Http\Requests\UpdatefrontPageRequest;
use App\Models\sliderImages;
use App\Repositories\frontPageRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Input;

use Response;

class frontPageController extends AppBaseController
{
    /** @var  frontPageRepository */
    private $frontPageRepository;

    public function __construct(frontPageRepository $frontPageRepo)
    {
        $this->frontPageRepository = $frontPageRepo;
    }

    /**
     * Display a listing of the frontPage.
     *
     * @param frontPageDataTable $frontPageDataTable
     * @return Response
     */
    public function index(frontPageDataTable $frontPageDataTable)
    {
        return $frontPageDataTable->render('front_pages.index');
    }

    /**
     * Show the form for creating a new frontPage.
     *
     * @return Response
     */
    public function create()
    {
        return view('front_pages.create');
    }

    /**
     * Store a newly created frontPage in storage.
     *
     * @param CreatefrontPageRequest $request
     *
     * @return Response
     */
    public function store(CreatefrontPageRequest $request)
    {
//        $allInput=$request->all();
//        return $allInput;
        $input = $request->except('slider_image','slider_text','aboutUs_image');
        if ($request->hasFile('aboutUs_image'))
        {
            $image=$request->file('aboutUs_image');
            $photoName1 = rand(1,777777777).time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('avatars'), $photoName1);
            $input['aboutUs_image'] = $photoName1;
        }

        $frontPage = $this->frontPageRepository->create($input);

        if( $request->hasFile('slider_image')) {
            if (!isset($request->slider_image))
            {
                \Session::flash('error', trans('frontPage.image_error'));
                return Redirect()->back()->withInput(Input::all());
            }
            $sliderText=$request->slider_text;
            $sliderHeading=$request->slider_heading;
            $count=sizeof($sliderText);
            foreach ($request->slider_image as $docs) {
                $filename = time().rand(1,5698742).'.'.$docs->getClientOriginalExtension();
                $docs->move(public_path('avatars'), $filename);
                $fileInput[] = $filename;
                $parentId=$frontPage->id;
            }

            for($i=0;$i<$count;$i++){
                $sliderInput[]=['image'=>$fileInput[$i], 'parent_id'=>$parentId,'heading'=>$sliderHeading[$i],'text'=>$sliderText[$i]];
            }
            foreach ($sliderInput as $slideInput){
                sliderImages::create($slideInput);
            }
        }
        Flash::success('Front Page saved successfully.');
        return redirect(route('frontPages.index'));
    }

    /**
     * Display the specified frontPage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $frontPage = $this->frontPageRepository->findWithoutFail($id);

        if (empty($frontPage)) {
            Flash::error('Front Page not found');

            return redirect(route('frontPages.index'));
        }

        return view('front_pages.show')->with('frontPage', $frontPage);
    }

    /**
     * Show the form for editing the specified frontPage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $frontPage = $this->frontPageRepository->findWithoutFail($id);

        if (empty($frontPage)) {
            Flash::error('Front Page not found');

            return redirect(route('frontPages.index'));
        }

        return view('front_pages.edit')->with('frontPage', $frontPage);
    }

    /**
     * Update the specified frontPage in storage.
     *
     * @param  int              $id
     * @param UpdatefrontPageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatefrontPageRequest $request)
    {
        $frontPage = $this->frontPageRepository->findWithoutFail($id);

        if (empty($frontPage)) {
            Flash::error('Front Page not found');

            return redirect(route('frontPages.index'));
        }
        $frontPageUpdate=$request->except('slider_image','slider_text','aboutUs_image', 'updated_heading','updated_text','sliderId');
        $frontPage = $this->frontPageRepository->update($frontPageUpdate, $id);

        $updateSliderText=$request->updated_text;
        $updateSliderHeading=$request->updated_heading;
        $updateSliderId=$request->sliderId;
        $updateCount=sizeof($updateSliderId);
        for ($i=0; $i<$updateCount; $i++){
            $sliderUpdate[]=['id'=>$updateSliderId[$i], 'heading'=>$updateSliderHeading[$i],'text'=>$updateSliderText[$i]];
        }

        foreach ($sliderUpdate as $updateSlider){
            sliderImages::whereId($updateSlider['id'])->update($updateSlider);
        }


        if( $request->hasFile('slider_image')) {
            if (!isset($request->slider_image))
            {
                \Session::flash('error', trans('frontPage.image_error'));
                return Redirect()->back()->withInput(Input::all());
            }
            $sliderText=$request->slider_text;
            $sliderHeading=$request->slider_heading;
            $count=sizeof($sliderText);
            foreach ($request->slider_image as $docs) {
                $filename = time().rand(1,5698742).'.'.$docs->getClientOriginalExtension();
                $docs->move(public_path('avatars'), $filename);
                $fileInput[] = $filename;
                $parentId=$id;
            }

            for($i=0;$i<$count;$i++){
                $sliderInput[]=['image'=>$fileInput[$i], 'parent_id'=>$parentId,'heading'=>$sliderHeading[$i],'text'=>$sliderText[$i]];
            }
            foreach ($sliderInput as $slideInput){
                sliderImages::create($slideInput);
            }
        }

        Flash::success('Front Page updated successfully.');

        return redirect(route('frontPages.index'));
    }

    /**
     * Remove the specified frontPage from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $frontPage = $this->frontPageRepository->findWithoutFail($id);

        if (empty($frontPage)) {
            Flash::error('Front Page not found');

            return redirect(route('frontPages.index'));
        }

        $this->frontPageRepository->delete($id);

        Flash::success('Front Page deleted successfully.');

        return redirect(route('frontPages.index'));
    }

    public function deleteSliderData($id){
        sliderImages::where('id',$id)->delete();
        return $id;
    }
}
