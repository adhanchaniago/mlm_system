<?php

namespace App\Http\Controllers;

use App\DataTables\timeoutDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatetimeoutRequest;
use App\Http\Requests\UpdatetimeoutRequest;
use App\Repositories\timeoutRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class timeoutController extends AppBaseController
{
    /** @var  timeoutRepository */
    private $timeoutRepository;

    public function __construct(timeoutRepository $timeoutRepo)
    {
        $this->timeoutRepository = $timeoutRepo;
    }

    /**
     * Display a listing of the timeout.
     *
     * @param timeoutDataTable $timeoutDataTable
     * @return Response
     */
    public function index(timeoutDataTable $timeoutDataTable)
    {
        return $timeoutDataTable->render('timeouts.index');
    }

    /**
     * Show the form for creating a new timeout.
     *
     * @return Response
     */
    public function create()
    {
        return view('timeouts.create');
    }

    /**
     * Store a newly created timeout in storage.
     *
     * @param CreatetimeoutRequest $request
     *
     * @return Response
     */
    public function store(CreatetimeoutRequest $request)
    {
        $input = $request->all();

        $timeout = $this->timeoutRepository->create($input);

        Flash::success('Timeout saved successfully.');

        return redirect(route('timeouts.index'));
    }

    /**
     * Display the specified timeout.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $timeout = $this->timeoutRepository->findWithoutFail($id);

        if (empty($timeout)) {
            Flash::error('Timeout not found');

            return redirect(route('timeouts.index'));
        }

        return view('timeouts.show')->with('timeout', $timeout);
    }

    /**
     * Show the form for editing the specified timeout.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timeout = $this->timeoutRepository->findWithoutFail($id);

        if (empty($timeout)) {
            Flash::error('Timeout not found');

            return redirect(route('timeouts.index'));
        }

        return view('timeouts.edit')->with('timeout', $timeout);
    }

    /**
     * Update the specified timeout in storage.
     *
     * @param  int              $id
     * @param UpdatetimeoutRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatetimeoutRequest $request)
    {
        $timeout = $this->timeoutRepository->findWithoutFail($id);

        if (empty($timeout)) {
            Flash::error('Timeout not found');

            return redirect(route('timeouts.index'));
        }

        $timeout = $this->timeoutRepository->update($request->all(), $id);

        Flash::success('Timeout updated successfully.');

        return redirect(route('timeouts.index'));
    }

    /**
     * Remove the specified timeout from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timeout = $this->timeoutRepository->findWithoutFail($id);

        if (empty($timeout)) {
            Flash::error('Timeout not found');

            return redirect(route('timeouts.index'));
        }

        $this->timeoutRepository->delete($id);

        Flash::success('Timeout deleted successfully.');

        return redirect(route('timeouts.index'));
    }
}
