<?php

namespace App\Http\Controllers;

use App\DataTables\ranksDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateranksRequest;
use App\Http\Requests\UpdateranksRequest;
use App\Repositories\ranksRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ranksController extends AppBaseController
{
    /** @var  ranksRepository */
    private $ranksRepository;

    public function __construct(ranksRepository $ranksRepo)
    {
        $this->ranksRepository = $ranksRepo;
    }

    /**
     * Display a listing of the ranks.
     *
     * @param ranksDataTable $ranksDataTable
     * @return Response
     */
    public function index(ranksDataTable $ranksDataTable)
    {
        return $ranksDataTable->render('ranks.index');
    }

    /**
     * Show the form for creating a new ranks.
     *
     * @return Response
     */
    public function create()
    {
        return view('ranks.create');
    }

    /**
     * Store a newly created ranks in storage.
     *
     * @param CreateranksRequest $request
     *
     * @return Response
     */
    public function store(CreateranksRequest $request)
    {
        $input = $request->all();

        $ranks = $this->ranksRepository->create($input);

        Flash::success('Ranks saved successfully.');

        return redirect(route('ranks.index'));
    }

    /**
     * Display the specified ranks.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ranks = $this->ranksRepository->findWithoutFail($id);

        if (empty($ranks)) {
            Flash::error('Ranks not found');

            return redirect(route('ranks.index'));
        }

        return view('ranks.show')->with('ranks', $ranks);
    }

    /**
     * Show the form for editing the specified ranks.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ranks = $this->ranksRepository->findWithoutFail($id);

        if (empty($ranks)) {
            Flash::error('Ranks not found');

            return redirect(route('ranks.index'));
        }

        return view('ranks.edit')->with('ranks', $ranks);
    }

    /**
     * Update the specified ranks in storage.
     *
     * @param  int              $id
     * @param UpdateranksRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateranksRequest $request)
    {
        $ranks = $this->ranksRepository->findWithoutFail($id);

        if (empty($ranks)) {
            Flash::error('Ranks not found');

            return redirect(route('ranks.index'));
        }

        $ranks = $this->ranksRepository->update($request->all(), $id);

        Flash::success('Ranks updated successfully.');

        return redirect(route('ranks.index'));
    }

    /**
     * Remove the specified ranks from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ranks = $this->ranksRepository->findWithoutFail($id);

        if (empty($ranks)) {
            Flash::error('Ranks not found');

            return redirect(route('ranks.index'));
        }

        $this->ranksRepository->delete($id);

        Flash::success('Ranks deleted successfully.');

        return redirect(route('ranks.index'));
    }
}
