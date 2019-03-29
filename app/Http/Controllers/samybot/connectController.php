<?php

namespace App\Http\Controllers\samybot;

use App\Http\Controllers\Controller;
use App\DataTables\connectDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateconnectRequest;
use App\Http\Requests\UpdateconnectRequest;
use App\Repositories\connectRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class connectController extends Controller
{
    /** @var  connectRepository */
    private $connectRepository;

    public function __construct(connectRepository $connectRepo)
    {
        $this->middleware('auth');
        $this->connectRepository = $connectRepo;
    }

    /**
     * Display a listing of the connect.
     *
     * @param connectDataTable $connectDataTable
     * @return Response
     */
    public function index(connectDataTable $connectDataTable)
    {
        return $connectDataTable->render('connects.index');
    }

    /**
     * Show the form for creating a new connect.
     *
     * @return Response
     */
    public function create()
    {
        return view('connects.create');
    }

    /**
     * Store a newly created connect in storage.
     *
     * @param CreateconnectRequest $request
     *
     * @return Response
     */
    public function store(CreateconnectRequest $request)
    {
        $input = $request->all();

        $connect = $this->connectRepository->create($input);

        Flash::success('Connect saved successfully.');

        return redirect(route('connects.index'));
    }

    /**
     * Display the specified connect.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $connect = $this->connectRepository->findWithoutFail($id);

        if (empty($connect)) {
            Flash::error('Connect not found');

            return redirect(route('connects.index'));
        }

        return view('connects.show')->with('connect', $connect);
    }

    /**
     * Show the form for editing the specified connect.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $connect = $this->connectRepository->findWithoutFail($id);

        if (empty($connect)) {
            Flash::error('Connect not found');

            return redirect(route('connects.index'));
        }

        return view('connects.edit')->with('connect', $connect);
    }

    /**
     * Update the specified connect in storage.
     *
     * @param  int              $id
     * @param UpdateconnectRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateconnectRequest $request)
    {
        $connect = $this->connectRepository->findWithoutFail($id);

        if (empty($connect)) {
            Flash::error('Connect not found');

            return redirect(route('connects.index'));
        }

        $connect = $this->connectRepository->update($request->all(), $id);

        Flash::success('Connect updated successfully.');

        return redirect(route('connects.index'));
    }

    /**
     * Remove the specified connect from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $connect = $this->connectRepository->findWithoutFail($id);

        if (empty($connect)) {
            Flash::error('Connect not found');

            return redirect(route('connects.index'));
        }

        $this->connectRepository->delete($id);

        Flash::success('Connect deleted successfully.');

        return redirect(route('connects.index'));
    }
}
