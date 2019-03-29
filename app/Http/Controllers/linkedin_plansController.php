<?php

namespace App\Http\Controllers;

use App\DataTables\linkedin_plansDataTable;
use App\Http\Requests;
use App\Http\Requests\Createlinkedin_plansRequest;
use App\Http\Requests\Updatelinkedin_plansRequest;
use App\Repositories\linkedin_plansRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Support\Facades\Validator;

class linkedin_plansController extends AppBaseController
{
    /** @var  linkedin_plansRepository */
    private $linkedinPlansRepository;

    public function __construct(linkedin_plansRepository $linkedinPlansRepo)
    {
        $this->linkedinPlansRepository = $linkedinPlansRepo;
    }

    /**
     * Display a listing of the linkedin_plans.
     *
     * @param linkedin_plansDataTable $linkedinPlansDataTable
     * @return Response
     */
    public function index(linkedin_plansDataTable $linkedinPlansDataTable)
    {
        return $linkedinPlansDataTable->render('linkedin_plans.index');
    }

    /**
     * Show the form for creating a new linkedin_plans.
     *
     * @return Response
     */
    public function create()
    {
        return view('linkedin_plans.create');
    }

    /**
     * Store a newly created linkedin_plans in storage.
     *
     * @param Createlinkedin_plansRequest $request
     *
     * @return Response
     */
    public function store(Createlinkedin_plansRequest $request)
    {
        $input = $request->all();

        $linkedinPlans = $this->linkedinPlansRepository->create($input);

        Flash::success('Linkedin Plans saved successfully.');

        return redirect(route('linkedinPlans.index'));
    }

    /**
     * Display the specified linkedin_plans.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $linkedinPlans = $this->linkedinPlansRepository->findWithoutFail($id);

        if (empty($linkedinPlans)) {
            Flash::error('Linkedin Plans not found');

            return redirect(route('linkedinPlans.index'));
        }

        return view('linkedin_plans.show')->with('linkedinPlans', $linkedinPlans);
    }

    /**
     * Show the form for editing the specified linkedin_plans.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $linkedinPlans = $this->linkedinPlansRepository->findWithoutFail($id);

        if (empty($linkedinPlans)) {
            Flash::error('Linkedin Plans not found');

            return redirect(route('linkedinPlans.index'));
        }

        return view('linkedin_plans.edit')->with('linkedinPlans', $linkedinPlans);
    }

    /**
     * Update the specified linkedin_plans in storage.
     *
     * @param  int              $id
     * @param Updatelinkedin_plansRequest $request
     *
     * @return Response
     */
    public function update($id, Updatelinkedin_plansRequest $request)
    {
        $linkedinPlans = $this->linkedinPlansRepository->findWithoutFail($id);

        if (empty($linkedinPlans)) {
            Flash::error('Linkedin Plans not found');

            return redirect(route('linkedinPlans.index'));
        }

        $linkedinPlans = $this->linkedinPlansRepository->update($request->all(), $id);

        Flash::success('Linkedin Plans updated successfully.');

        return redirect(route('linkedinPlans.index'));
    }

    /**
     * Remove the specified linkedin_plans from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $linkedinPlans = $this->linkedinPlansRepository->findWithoutFail($id);

        if (empty($linkedinPlans)) {
            Flash::error('Linkedin Plans not found');

            return redirect(route('linkedinPlans.index'));
        }

        $this->linkedinPlansRepository->delete($id);

        Flash::success('Linkedin Plans deleted successfully.');

        return redirect(route('linkedinPlans.index'));
    }
}
