<?php

namespace App\Http\Controllers;

use App\DataTables\emailcontentsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateemailcontentsRequest;
use App\Http\Requests\UpdateemailcontentsRequest;
use App\Repositories\emailcontentsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class emailcontentsController extends AppBaseController
{
    /** @var  emailcontentsRepository */
    private $emailcontentsRepository;

    public function __construct(emailcontentsRepository $emailcontentsRepo)
    {
        $this->emailcontentsRepository = $emailcontentsRepo;
    }

    /**
     * Display a listing of the emailcontents.
     *
     * @param emailcontentsDataTable $emailcontentsDataTable
     * @return Response
     */
    public function index(emailcontentsDataTable $emailcontentsDataTable)
    {
        return $emailcontentsDataTable->render('emailcontents.index');
    }

    /**
     * Show the form for creating a new emailcontents.
     *
     * @return Response
     */
    public function create()
    {
        return view('emailcontents.create');
    }

    /**
     * Store a newly created emailcontents in storage.
     *
     * @param CreateemailcontentsRequest $request
     *
     * @return Response
     */
    public function store(CreateemailcontentsRequest $request)
    {
        $input = $request->all();

        $emailcontents = $this->emailcontentsRepository->create($input);

        Flash::success('Emailcontents saved successfully.');

        return redirect(route('emailcontents.index'));
    }

    /**
     * Display the specified emailcontents.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $emailcontents = $this->emailcontentsRepository->findWithoutFail($id);

        if (empty($emailcontents)) {
            Flash::error('Emailcontents not found');

            return redirect(route('emailcontents.index'));
        }

        return view('emailcontents.show')->with('emailcontents', $emailcontents);
    }

    /**
     * Show the form for editing the specified emailcontents.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $emailcontents = $this->emailcontentsRepository->findWithoutFail($id);

        if (empty($emailcontents)) {
            Flash::error('Emailcontents not found');

            return redirect(route('emailcontents.index'));
        }

        return view('emailcontents.edit')->with('emailcontents', $emailcontents);
    }

    /**
     * Update the specified emailcontents in storage.
     *
     * @param  int              $id
     * @param UpdateemailcontentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateemailcontentsRequest $request)
    {
        $emailcontents = $this->emailcontentsRepository->findWithoutFail($id);

        if (empty($emailcontents)) {
            Flash::error('Emailcontents not found');

            return redirect(route('emailcontents.index'));
        }

        $emailcontents = $this->emailcontentsRepository->update($request->all(), $id);

        Flash::success('Emailcontents updated successfully.');

        return redirect(route('emailcontents.index'));
    }

    /**
     * Remove the specified emailcontents from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $emailcontents = $this->emailcontentsRepository->findWithoutFail($id);

        if (empty($emailcontents)) {
            Flash::error('Emailcontents not found');

            return redirect(route('emailcontents.index'));
        }

        $this->emailcontentsRepository->delete($id);

        Flash::success('Emailcontents deleted successfully.');

        return redirect(route('emailcontents.index'));
    }
}
