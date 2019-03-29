<?php

namespace App\Http\Controllers;

use App\DataTables\affilatesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateaffilatesRequest;
use App\Http\Requests\UpdateaffilatesRequest;
use App\Repositories\affilatesRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class affilatesController extends AppBaseController
{
    /** @var  affilatesRepository */
    private $affilatesRepository;

    public function __construct(affilatesRepository $affilatesRepo)
    {
        $this->affilatesRepository = $affilatesRepo;
    }

    /**
     * Display a listing of the affilates.
     *
     * @param affilatesDataTable $affilatesDataTable
     * @return Response
     */
    public function index(affilatesDataTable $affilatesDataTable)
    {
        return $affilatesDataTable->render('affilates.index');
    }

    /**
     * Show the form for creating a new affilates.
     *
     * @return Response
     */
    public function create()
    {
        return view('affilates.create');
    }

    /**
     * Store a newly created affilates in storage.
     *
     * @param CreateaffilatesRequest $request
     *
     * @return Response
     */
    public function store(CreateaffilatesRequest $request)
    {
        $input = $request->all();

        $update = new

        $affilates = $this->affilatesRepository->create($input);

        Flash::success('Affilates saved successfully.');

        return redirect(route('affilates.index'));
    }

    /**
     * Display the specified affilates.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $affilates = $this->affilatesRepository->findWithoutFail($id);

        if (empty($affilates)) {
            Flash::error('Affilates not found');

            return redirect(route('affilates.index'));
        }

        return view('affilates.show')->with('affilates', $affilates);
    }

    /**
     * Show the form for editing the specified affilates.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $affilates = $this->affilatesRepository->findWithoutFail($id);

        if (empty($affilates)) {
            Flash::error('Affilates not found');

            return redirect(route('affilates.index'));
        }

        return view('affilates.edit')->with('affilates', $affilates);
    }

    /**
     * Update the specified affilates in storage.
     *
     * @param  int              $id
     * @param UpdateaffilatesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateaffilatesRequest $request)
    {
        $affilates = $this->affilatesRepository->findWithoutFail($id);

        if (empty($affilates)) {
            Flash::error('Affilates not found');

            return redirect(route('affilates.index'));
        }

        $affilates = $this->affilatesRepository->update($request->all(), $id);

        Flash::success('Affilates updated successfully.');

        return redirect(route('affilates.index'));
    }

    /**
     * Remove the specified affilates from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $affilates = $this->affilatesRepository->findWithoutFail($id);

        if (empty($affilates)) {
            Flash::error('Affilates not found');

            return redirect(route('affilates.index'));
        }

        $this->affilatesRepository->delete($id);

        Flash::success('Affilates deleted successfully.');

        return redirect(route('affilates.index'));
    }
}
