<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateconnectAPIRequest;
use App\Http\Requests\API\UpdateconnectAPIRequest;
use App\Models\connect;
use App\Repositories\connectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class connectController
 * @package App\Http\Controllers\API
 */

class connectAPIController extends AppBaseController
{
    /** @var  connectRepository */
    private $connectRepository;

    public function __construct(connectRepository $connectRepo)
    {
        $this->connectRepository = $connectRepo;
    }

    public function make_connect(Request $request){
        if(!empty($request->user_id) && !empty($request->company_id)) {
            $Input = [
                'user_id' => $request->user_id,
                'company_id' => $request->company_id
            ];
            $connect = connect::create($Input);
            if ($connect) {
                $data['suceess'] = true;
                $data['data'] = $connect;
                $data['message'] = "success";
            } else {
                $data['suceess'] = false;
                $data['data'] = "";
                $data['message'] = "Something went wrong";
            }
        }else{
            $data['suceess'] = false;
            $data['data'] = "";
            $data['message'] = "Required both user_id and company_id.";
        }
        return $data;
    }
    /**
     * Display a listing of the connect.
     * GET|HEAD /connects
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->connectRepository->pushCriteria(new RequestCriteria($request));
        $this->connectRepository->pushCriteria(new LimitOffsetCriteria($request));
        $connects = $this->connectRepository->all();

        return $this->sendResponse($connects->toArray(), 'Connects retrieved successfully');
    }

    /**
     * Store a newly created connect in storage.
     * POST /connects
     *
     * @param CreateconnectAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateconnectAPIRequest $request)
    {
        $input = $request->all();

        $connects = $this->connectRepository->create($input);

        return $this->sendResponse($connects->toArray(), 'Connect saved successfully');
    }

    /**
     * Display the specified connect.
     * GET|HEAD /connects/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var connect $connect */
        $connect = $this->connectRepository->findWithoutFail($id);

        if (empty($connect)) {
            return $this->sendError('Connect not found');
        }

        return $this->sendResponse($connect->toArray(), 'Connect retrieved successfully');
    }

    /**
     * Update the specified connect in storage.
     * PUT/PATCH /connects/{id}
     *
     * @param  int $id
     * @param UpdateconnectAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateconnectAPIRequest $request)
    {
        $input = $request->all();

        /** @var connect $connect */
        $connect = $this->connectRepository->findWithoutFail($id);

        if (empty($connect)) {
            return $this->sendError('Connect not found');
        }

        $connect = $this->connectRepository->update($input, $id);

        return $this->sendResponse($connect->toArray(), 'connect updated successfully');
    }

    /**
     * Remove the specified connect from storage.
     * DELETE /connects/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var connect $connect */
        $connect = $this->connectRepository->findWithoutFail($id);

        if (empty($connect)) {
            return $this->sendError('Connect not found');
        }

        $connect->delete();

        return $this->sendResponse($id, 'Connect deleted successfully');
    }
}
