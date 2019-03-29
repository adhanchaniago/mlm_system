<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatecategoryCampaignAPIRequest;
use App\Http\Requests\API\UpdatecategoryCampaignAPIRequest;
use App\Models\categoryCampaign;
use App\Repositories\categoryCampaignRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class categoryCampaignController
 * @package App\Http\Controllers\API
 */

class categoryCampaignAPIController extends AppBaseController
{
    /** @var  categoryCampaignRepository */
    private $categoryCampaignRepository;

    public function __construct(categoryCampaignRepository $categoryCampaignRepo)
    {
        $this->categoryCampaignRepository = $categoryCampaignRepo;
    }

    /**
     * Display a listing of the categoryCampaign.
     * GET|HEAD /categoryCampaigns
     *
     * @param Request $request
     * @return Response
     */
//    public function index(Request $request)
//    {
//        $this->categoryCampaignRepository->pushCriteria(new RequestCriteria($request));
//        $this->categoryCampaignRepository->pushCriteria(new LimitOffsetCriteria($request));
//        $categoryCampaigns = $this->categoryCampaignRepository->all();
//
//        return $this->sendResponse($categoryCampaigns->toArray(), 'Category Campaigns retrieved successfully');
//    }

    /**
     * Store a newly created categoryCampaign in storage.
     * POST /categoryCampaigns
     *
     * @param CreatecategoryCampaignAPIRequest $request
     *
     * @return Response
     */
//    public function store(CreatecategoryCampaignAPIRequest $request)
//    {
//        $input = $request->all();
//
//        $categoryCampaigns = $this->categoryCampaignRepository->create($input);
//
//        return $this->sendResponse($categoryCampaigns->toArray(), 'Category Campaign saved successfully');
//    }

    /**
     * Display the specified categoryCampaign.
     * GET|HEAD /categoryCampaigns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
//    public function show($id)
//    {
//        /** @var categoryCampaign $categoryCampaign */
//        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);
//
//        if (empty($categoryCampaign)) {
//            return $this->sendError('Category Campaign not found');
//        }
//
//        return $this->sendResponse($categoryCampaign->toArray(), 'Category Campaign retrieved successfully');
//    }

    /**
     * Update the specified categoryCampaign in storage.
     * PUT/PATCH /categoryCampaigns/{id}
     *
     * @param  int $id
     * @param UpdatecategoryCampaignAPIRequest $request
     *
     * @return Response
     */
//    public function update($id, UpdatecategoryCampaignAPIRequest $request)
//    {
//        $input = $request->all();
//
//        /** @var categoryCampaign $categoryCampaign */
//        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);
//
//        if (empty($categoryCampaign)) {
//            return $this->sendError('Category Campaign not found');
//        }
//
//        $categoryCampaign = $this->categoryCampaignRepository->update($input, $id);
//
//        return $this->sendResponse($categoryCampaign->toArray(), 'categoryCampaign updated successfully');
//    }

    /**
     * Remove the specified categoryCampaign from storage.
     * DELETE /categoryCampaigns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
//    public function destroy($id)
//    {
//        /** @var categoryCampaign $categoryCampaign */
//        $categoryCampaign = $this->categoryCampaignRepository->findWithoutFail($id);
//
//        if (empty($categoryCampaign)) {
//            return $this->sendError('Category Campaign not found');
//        }
//
//        $categoryCampaign->delete();
//
//        return $this->sendResponse($id, 'Category Campaign deleted successfully');
//    }
}
