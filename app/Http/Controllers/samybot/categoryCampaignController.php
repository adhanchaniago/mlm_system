<?php

namespace App\Http\Controllers\samybot;

use App\Http\Controllers\Controller;
use App\DataTables\categoryCampaignDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatecategoryCampaignRequest;
use App\Http\Requests\UpdatecategoryCampaignRequest;
use App\Repositories\categoryCampaignRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class categoryCampaignController extends Controller
{
    /** @var  categoryCampaignRepository */
    private $categoryCampaignRepository;

    public function __construct(categoryCampaignRepository $categoryCampaignRepo)
    {
        $this->middleware('auth');
        $this->categoryCampaignRepository = $categoryCampaignRepo;
    }

    /**
     * Display a listing of the categoryCampaign.
     *
     * @param categoryCampaignDataTable $categoryCampaignDataTable
     * @return Response
     */
    public function index(categoryCampaignDataTable $categoryCampaignDataTable)
    {
        return $categoryCampaignDataTable->render('category_campaigns.index');
    }

    /**
     * Show the form for creating a new categoryCampaign.
     *
     * @return Response
     */
    public function create()
    {
        return view('category_campaigns.create');
    }

    /**
     * Store a newly created categoryCampaign in storage.
     *
     * @param CreatecategoryCampaignRequest $request
     *
     * @return Response
     */
    public function store(CreatecategoryCampaignRequest $request)
    {
        $input = $request->all();

        $categoryCampaign = $this->categoryCampaignRepository->create($input);

        Flash::success('Category Campaign saved successfully.');

        return redirect(route('categoryCampaigns.index'));
    }

    /**
     * Display the specified categoryCampaign.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);

        if (empty($categoryCampaign)) {
            Flash::error('Category Campaign not found');

            return redirect(route('categoryCampaigns.index'));
        }

        return view('category_campaigns.show')->with('categoryCampaign', $categoryCampaign);
    }

    /**
     * Show the form for editing the specified categoryCampaign.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);

        if (empty($categoryCampaign)) {
            Flash::error('Category Campaign not found');

            return redirect(route('categoryCampaigns.index'));
        }

        return view('category_campaigns.edit')->with('categoryCampaign', $categoryCampaign);
    }

    /**
     * Update the specified categoryCampaign in storage.
     *
     * @param  int              $id
     * @param UpdatecategoryCampaignRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatecategoryCampaignRequest $request)
    {
        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);

        if (empty($categoryCampaign)) {
            Flash::error('Category Campaign not found');

            return redirect(route('categoryCampaigns.index'));
        }

        $categoryCampaign = $this->categoryCampaignRepository->update($request->all(), $id);

        Flash::success('Category Campaign updated successfully.');

        return redirect(route('categoryCampaigns.index'));
    }

    /**
     * Remove the specified categoryCampaign from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);

        if (empty($categoryCampaign)) {
            Flash::error('Category Campaign not found');

            return redirect(route('categoryCampaigns.index'));
        }

        $this->categoryCampaignRepository->delete($id);

        Flash::success('Category Campaign deleted successfully.');

        return redirect(route('categoryCampaigns.index'));
    }
}
