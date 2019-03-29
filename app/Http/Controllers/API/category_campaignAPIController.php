<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Createcategory_campaignAPIRequest;
use App\Http\Requests\API\Updatecategory_campaignAPIRequest;
use App\Models\category_campaign;
use App\Repositories\category_campaignRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class category_campaignController
 * @package App\Http\Controllers\API
 */

class category_campaignAPIController extends AppBaseController
{
    /** @var  category_campaignRepository */
    private $categoryCampaignRepository;

    public function __construct(category_campaignRepository $categoryCampaignRepo)
    {
        $this->categoryCampaignRepository = $categoryCampaignRepo;
    }

    /**
     * Display a listing of the category_campaign.
     * GET|HEAD /categoryCampaigns
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->categoryCampaignRepository->pushCriteria(new RequestCriteria($request));
        $this->categoryCampaignRepository->pushCriteria(new LimitOffsetCriteria($request));
        $categoryCampaigns = $this->categoryCampaignRepository->all();

        return $this->sendResponse($categoryCampaigns->toArray(), 'Category Campaigns retrieved successfully');
    }

    /**
     * Store a newly created category_campaign in storage.
     * POST /categoryCampaigns
     *
     * @param Createcategory_campaignAPIRequest $request
     *
     * @return Response
     */
    public function store(Createcategory_campaignAPIRequest $request)
    {
        $input = $request->all();

        $categoryCampaigns = $this->categoryCampaignRepository->create($input);

        return $this->sendResponse($categoryCampaigns->toArray(), 'Category Campaign saved successfully');
    }

    /**
     * Display the specified category_campaign.
     * GET|HEAD /categoryCampaigns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var category_campaign $categoryCampaign */
        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);

        if (empty($categoryCampaign)) {
            return $this->sendError('Category Campaign not found');
        }

        return $this->sendResponse($categoryCampaign->toArray(), 'Category Campaign retrieved successfully');
    }

    /**
     * Update the specified category_campaign in storage.
     * PUT/PATCH /categoryCampaigns/{id}
     *
     * @param  int $id
     * @param Updatecategory_campaignAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Updatecategory_campaignAPIRequest $request)
    {
        $input = $request->all();

        /** @var category_campaign $categoryCampaign */
        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);

        if (empty($categoryCampaign)) {
            return $this->sendError('Category Campaign not found');
        }

        $categoryCampaign = $this->categoryCampaignRepository->update($input, $id);

        return $this->sendResponse($categoryCampaign->toArray(), 'category_campaign updated successfully');
    }

    /**
     * Remove the specified category_campaign from storage.
     * DELETE /categoryCampaigns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var category_campaign $categoryCampaign */
        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);

        if (empty($categoryCampaign)) {
            return $this->sendError('Category Campaign not found');
        }

        $categoryCampaign->delete();

        return $this->sendResponse($id, 'Category Campaign deleted successfully');
    }
}
