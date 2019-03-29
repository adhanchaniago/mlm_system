<?php

namespace App\Http\Controllers;

use App\DataTables\payouthistoriesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatepayouthistoriesRequest;
use App\Http\Requests\UpdatepayouthistoriesRequest;
use App\Repositories\payouthistoriesRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class payouthistoriesController extends AppBaseController
{
    /** @var  payouthistoriesRepository */
    private $payouthistoriesRepository;

    public function __construct(payouthistoriesRepository $payouthistoriesRepo)
    {
        $this->payouthistoriesRepository = $payouthistoriesRepo;
    }

    /**
     * Display a listing of the payouthistories.
     *
     * @param payouthistoriesDataTable $payouthistoriesDataTable
     * @return Response
     */
    public function index(payouthistoriesDataTable $payouthistoriesDataTable)
    {
        return $payouthistoriesDataTable->render('payouthistories.index');
    }

    /**
     * Show the form for creating a new payouthistories.
     *
     * @return Response
     */
    public function create()
    {
        return view('payouthistories.create');
    }

    /**
     * Store a newly created payouthistories in storage.
     *
     * @param CreatepayouthistoriesRequest $request
     *
     * @return Response
     */
    public function store(CreatepayouthistoriesRequest $request)
    {
        $input = $request->all();

        $payouthistories = $this->payouthistoriesRepository->create($input);

        Flash::success('Payouthistories saved successfully.');

        return redirect(route('payouthistories.index'));
    }

    /**
     * Display the specified payouthistories.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $payouthistories = $this->payouthistoriesRepository->findWithoutFail($id);

        if (empty($payouthistories)) {
            Flash::error('Payouthistories not found');

            return redirect(route('payouthistories.index'));
        }

        return view('payouthistories.show')->with('payouthistories', $payouthistories);
    }

    /**
     * Show the form for editing the specified payouthistories.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $payouthistories = $this->payouthistoriesRepository->findWithoutFail($id);

        if (empty($payouthistories)) {
            Flash::error('Payouthistories not found');

            return redirect(route('payouthistories.index'));
        }

        return view('payouthistories.edit')->with('payouthistories', $payouthistories);
    }

    /**
     * Update the specified payouthistories in storage.
     *
     * @param  int              $id
     * @param UpdatepayouthistoriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatepayouthistoriesRequest $request)
    {
        $payouthistories = $this->payouthistoriesRepository->findWithoutFail($id);

        if (empty($payouthistories)) {
            Flash::error('Payouthistories not found');

            return redirect(route('payouthistories.index'));
        }

        $payouthistories = $this->payouthistoriesRepository->update($request->all(), $id);

        Flash::success('Payouthistories updated successfully.');

        return redirect(route('payouthistories.index'));
    }

    /**
     * Remove the specified payouthistories from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $payouthistories = $this->payouthistoriesRepository->findWithoutFail($id);

        if (empty($payouthistories)) {
            Flash::error('Payouthistories not found');

            return redirect(route('payouthistories.index'));
        }

        $this->payouthistoriesRepository->delete($id);

        Flash::success('Payouthistories deleted successfully.');

        return redirect(route('payouthistories.index'));
    }
}
