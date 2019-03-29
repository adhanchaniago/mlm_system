<?php

namespace App\Http\Controllers;

use App\DataTables\stripePaymentDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatestripePaymentRequest;
use App\Http\Requests\UpdatestripePaymentRequest;
use App\Repositories\stripePaymentRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class stripePaymentController extends AppBaseController
{
    /** @var  stripePaymentRepository */
    private $stripePaymentRepository;

    public function __construct(stripePaymentRepository $stripePaymentRepo)
    {
        $this->stripePaymentRepository = $stripePaymentRepo;
    }

    /**
     * Display a listing of the stripePayment.
     *
     * @param stripePaymentDataTable $stripePaymentDataTable
     * @return Response
     */
    public function index(stripePaymentDataTable $stripePaymentDataTable)
    {
        return $stripePaymentDataTable->render('stripe_payments.index');
    }

    /**
     * Show the form for creating a new stripePayment.
     *
     * @return Response
     */
    public function create()
    {
        return view('stripe_payments.create');
    }

    /**
     * Store a newly created stripePayment in storage.
     *
     * @param CreatestripePaymentRequest $request
     *
     * @return Response
     */
    public function store(CreatestripePaymentRequest $request)
    {
        $input = $request->all();

        $stripePayment = $this->stripePaymentRepository->create($input);

        Flash::success('Stripe Payment saved successfully.');

        return redirect(route('stripePayments.index'));
    }

    /**
     * Display the specified stripePayment.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $stripePayment = $this->stripePaymentRepository->findWithoutFail($id);

        if (empty($stripePayment)) {
            Flash::error('Stripe Payment not found');

            return redirect(route('stripePayments.index'));
        }

        return view('stripe_payments.show')->with('stripePayment', $stripePayment);
    }

    /**
     * Show the form for editing the specified stripePayment.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $stripePayment = $this->stripePaymentRepository->findWithoutFail($id);

        if (empty($stripePayment)) {
            Flash::error('Stripe Payment not found');

            return redirect(route('stripePayments.index'));
        }

        return view('stripe_payments.edit')->with('stripePayment', $stripePayment);
    }

    /**
     * Update the specified stripePayment in storage.
     *
     * @param  int              $id
     * @param UpdatestripePaymentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatestripePaymentRequest $request)
    {
        $stripePayment = $this->stripePaymentRepository->findWithoutFail($id);

        if (empty($stripePayment)) {
            Flash::error('Stripe Payment not found');

            return redirect(route('stripePayments.index'));
        }

        $stripePayment = $this->stripePaymentRepository->update($request->all(), $id);

        Flash::success('Stripe Payment updated successfully.');

        return redirect(route('stripePayments.index'));
    }

    /**
     * Remove the specified stripePayment from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $stripePayment = $this->stripePaymentRepository->findWithoutFail($id);

        if (empty($stripePayment)) {
            Flash::error('Stripe Payment not found');

            return redirect(route('stripePayments.index'));
        }

        $this->stripePaymentRepository->delete($id);

        Flash::success('Stripe Payment deleted successfully.');

        return redirect(route('stripePayments.index'));
    }
}
