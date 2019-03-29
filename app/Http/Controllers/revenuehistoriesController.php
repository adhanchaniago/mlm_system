<?php

namespace App\Http\Controllers;

use App\DataTables\revenuehistoriesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreaterevenuehistoriesRequest;
use App\Http\Requests\UpdaterevenuehistoriesRequest;
use App\Repositories\revenuehistoriesRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class revenuehistoriesController extends AppBaseController
{
    /** @var  revenuehistoriesRepository */
    private $revenuehistoriesRepository;

    public function __construct(revenuehistoriesRepository $revenuehistoriesRepo)
    {
        $this->revenuehistoriesRepository = $revenuehistoriesRepo;
    }

    /**
     * Display a listing of the revenuehistories.
     *
     * @param revenuehistoriesDataTable $revenuehistoriesDataTable
     * @return Response
     */
    public function index(revenuehistoriesDataTable $revenuehistoriesDataTable)
    {
        return $revenuehistoriesDataTable->render('revenuehistories.index');
    }

    /**
     * Show the form for creating a new revenuehistories.
     *
     * @return Response
     */
    public function create()
    {
        return view('revenuehistories.create');
    }

    /**
     * Store a newly created revenuehistories in storage.
     *
     * @param CreaterevenuehistoriesRequest $request
     *
     * @return Response
     */
    public function store(CreaterevenuehistoriesRequest $request)
    {
        $input = $request->all();

        $revenuehistories = $this->revenuehistoriesRepository->create($input);

        Flash::success('Revenuehistories saved successfully.');

        return redirect(route('revenuehistories.index'));
    }

    /**
     * Display the specified revenuehistories.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $revenuehistories = $this->revenuehistoriesRepository->findWithoutFail($id);

        if (empty($revenuehistories)) {
            Flash::error('Revenuehistories not found');

            return redirect(route('revenuehistories.index'));
        }

        return view('revenuehistories.show')->with('revenuehistories', $revenuehistories);
    }

    /**
     * Show the form for editing the specified revenuehistories.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $revenuehistories = $this->revenuehistoriesRepository->findWithoutFail($id);

        if (empty($revenuehistories)) {
            Flash::error('Revenuehistories not found');

            return redirect(route('revenuehistories.index'));
        }

        return view('revenuehistories.edit')->with('revenuehistories', $revenuehistories);
    }

    /**
     * Update the specified revenuehistories in storage.
     *
     * @param  int              $id
     * @param UpdaterevenuehistoriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdaterevenuehistoriesRequest $request)
    {
        $revenuehistories = $this->revenuehistoriesRepository->findWithoutFail($id);

        if (empty($revenuehistories)) {
            Flash::error('Revenuehistories not found');

            return redirect(route('revenuehistories.index'));
        }

        $revenuehistories = $this->revenuehistoriesRepository->update($request->all(), $id);

        Flash::success('Revenuehistories updated successfully.');

        return redirect(route('revenuehistories.index'));
    }

    /**
     * Remove the specified revenuehistories from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $revenuehistories = $this->revenuehistoriesRepository->findWithoutFail($id);

        if (empty($revenuehistories)) {
            Flash::error('Revenuehistories not found');

            return redirect(route('revenuehistories.index'));
        }

        $this->revenuehistoriesRepository->delete($id);

        Flash::success('Revenuehistories deleted successfully.');

        return redirect(route('revenuehistories.index'));
    }
}
