<?php

namespace App\Http\Controllers;

use App\DataTables\companiesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatecompaniesRequest;
use App\Http\Requests\UpdatecompaniesRequest;
use App\Repositories\companiesRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class companiesController extends AppBaseController
{
    /** @var  companiesRepository */
    private $companiesRepository;

    public function __construct(companiesRepository $companiesRepo)
    {
        $this->companiesRepository = $companiesRepo;
    }

    /**
     * Display a listing of the companies.
     *
     * @param companiesDataTable $companiesDataTable
     * @return Response
     */
    public function index(companiesDataTable $companiesDataTable)
    {
        return $companiesDataTable->render('companies.index');
    }

    /**
     * Show the form for creating a new companies.
     *
     * @return Response
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created companies in storage.
     *
     * @param CreatecompaniesRequest $request
     *
     * @return Response
     */
    public function store(CreatecompaniesRequest $request)
    {
        $input = $request->all();

        $companies = $this->companiesRepository->create($input);

        Flash::success('Companies saved successfully.');

        return redirect(route('companies.index'));
    }

    /**
     * Display the specified companies.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $companies = $this->companiesRepository->findWithoutFail($id);

        if (empty($companies)) {
            Flash::error('Companies not found');

            return redirect(route('companies.index'));
        }

        return view('companies.show')->with('companies', $companies);
    }

    /**
     * Show the form for editing the specified companies.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $companies = $this->companiesRepository->findWithoutFail($id);

        if (empty($companies)) {
            Flash::error('Companies not found');

            return redirect(route('companies.index'));
        }

        return view('companies.edit')->with('companies', $companies);
    }

    /**
     * Update the specified companies in storage.
     *
     * @param  int              $id
     * @param UpdatecompaniesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatecompaniesRequest $request)
    {
        $companies = $this->companiesRepository->findWithoutFail($id);

        if (empty($companies)) {
            Flash::error('Companies not found');

            return redirect(route('companies.index'));
        }

        $companies = $this->companiesRepository->update($request->all(), $id);

        Flash::success('Companies updated successfully.');

        return redirect(route('companies.index'));
    }

    /**
     * Remove the specified companies from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $companies = $this->companiesRepository->findWithoutFail($id);

        if (empty($companies)) {
            Flash::error('Companies not found');

            return redirect(route('companies.index'));
        }

        $this->companiesRepository->delete($id);

        Flash::success('Companies deleted successfully.');

        return redirect(route('companies.index'));
    }
}
