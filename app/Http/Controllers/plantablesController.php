<?php

namespace App\Http\Controllers;

use App\DataTables\plantablesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateplantablesRequest;
use App\Http\Requests\UpdateplantablesRequest;
use App\Repositories\plantablesRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class plantablesController extends AppBaseController
{
    /** @var  plantablesRepository */
    private $plantablesRepository;

    public function __construct(plantablesRepository $plantablesRepo)
    {
        $this->plantablesRepository = $plantablesRepo;
    }

    /**
     * Display a listing of the plantables.
     *
     * @param plantablesDataTable $plantablesDataTable
     * @return Response
     */
    public function index(plantablesDataTable $plantablesDataTable)
    {
        return $plantablesDataTable->render('plantables.index');
    }

    /**
     * Show the form for creating a new plantables.
     *
     * @return Response
     */
    public function create()
    {
        return view('plantables.create');
    }

    /**
     * Store a newly created plantables in storage.
     *
     * @param CreateplantablesRequest $request
     *
     * @return Response
     */
    public function store(CreateplantablesRequest $request)
    {
        $input = $request->all();

        $plantables = $this->plantablesRepository->create($input);

        Flash::success('Plantables saved successfully.');

        return redirect(route('plantables.index'));
    }

    /**
     * Display the specified plantables.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $plantables = $this->plantablesRepository->findWithoutFail($id);

        if (empty($plantables)) {
            Flash::error('Plantables not found');

            return redirect(route('plantables.index'));
        }

        return view('plantables.show')->with('plantables', $plantables);
    }

    /**
     * Show the form for editing the specified plantables.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $plantables = $this->plantablesRepository->findWithoutFail($id);

        if (empty($plantables)) {
            Flash::error('Plantables not found');

            return redirect(route('plantables.index'));
        }

        return view('plantables.edit')->with('plantables', $plantables);
    }

    /**
     * Update the specified plantables in storage.
     *
     * @param  int              $id
     * @param UpdateplantablesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateplantablesRequest $request)
    {
        $plantables = $this->plantablesRepository->findWithoutFail($id);

        if (empty($plantables)) {
            Flash::error('Plantables not found');

            return redirect(route('plantables.index'));
        }

        $plantables = $this->plantablesRepository->update($request->all(), $id);

        Flash::success('Plantables updated successfully.');

        return redirect(route('plantables.index'));
    }

    /**
     * Remove the specified plantables from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $plantables = $this->plantablesRepository->findWithoutFail($id);

        if (empty($plantables)) {
            Flash::error('Plantables not found');

            return redirect(route('plantables.index'));
        }

        $this->plantablesRepository->delete($id);

        Flash::success('Plantables deleted successfully.');

        return redirect(route('plantables.index'));
    }
}
