<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatebotCampaignAPIRequest;
use App\Http\Requests\API\UpdatebotCampaignAPIRequest;
use App\Models\botCampaign;
use App\Models\bot;
use App\Repositories\botCampaignRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class botCampaignController
 * @package App\Http\Controllers\API
 */

class botCampaignAPIController extends AppBaseController
{
    /** @var  botCampaignRepository */
    private $botCampaignRepository;

    public function __construct(botCampaignRepository $botCampaignRepo)
    {
        $this->botCampaignRepository = $botCampaignRepo;
    }

    /**
     * Display a listing of the botCampaign.
     * GET|HEAD /botCampaigns
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->botCampaignRepository->pushCriteria(new RequestCriteria($request));
        $this->botCampaignRepository->pushCriteria(new LimitOffsetCriteria($request));
        $botCampaigns = $this->botCampaignRepository->all();

        return $this->sendResponse($botCampaigns->toArray(), 'Bot Campaigns retrieved successfully');
    }

    /**
     * Store a newly created botCampaign in storage.
     * POST /botCampaigns
     *
     * @param CreatebotCampaignAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatebotCampaignAPIRequest $request)
    {
        $input = $request->all();

        $botCampaigns = $this->botCampaignRepository->create($input);

        return $this->sendResponse($botCampaigns->toArray(), 'Bot Campaign saved successfully');
    }

    /**
     * Display the specified botCampaign.
     * GET|HEAD /botCampaigns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var botCampaign $botCampaign */
        $botCampaign = $this->botCampaignRepository->findWithoutFail($id);

        if (empty($botCampaign)) {
            return $this->sendError('Bot Campaign not found');
        }

        return $this->sendResponse($botCampaign->toArray(), 'Bot Campaign retrieved successfully');
    }

    /**
     * Update the specified botCampaign in storage.
     * PUT/PATCH /botCampaigns/{id}
     *
     * @param  int $id
     * @param UpdatebotCampaignAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatebotCampaignAPIRequest $request)
    {
        $input = $request->all();

        /** @var botCampaign $botCampaign */
        $botCampaign = $this->botCampaignRepository->findWithoutFail($id);

        if (empty($botCampaign)) {
            return $this->sendError('Bot Campaign not found');
        }

        $botCampaign = $this->botCampaignRepository->update($input, $id);

        return $this->sendResponse($botCampaign->toArray(), 'botCampaign updated successfully');
    }

    /**
     * Remove the specified botCampaign from storage.
     * DELETE /botCampaigns/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var botCampaign $botCampaign */
        $botCampaign = $this->botCampaignRepository->findWithoutFail($id);

        if (empty($botCampaign)) {
            return $this->sendError('Bot Campaign not found');
        }

        $botCampaign->delete();

        return $this->sendResponse($id, 'Bot Campaign deleted successfully');
    }
}
