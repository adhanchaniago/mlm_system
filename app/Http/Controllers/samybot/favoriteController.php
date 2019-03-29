<?php

namespace App\Http\Controllers\samybot;

use App\Http\Controllers\Controller;
use App\DataTables\favoriteDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatefavoriteRequest;
use App\Http\Requests\UpdatefavoriteRequest;
use App\Repositories\favoriteRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class favoriteController extends Controller
{
    /** @var  favoriteRepository */
    private $favoriteRepository;

    public function __construct(favoriteRepository $favoriteRepo)
    {
        $this->middleware('auth');
        $this->favoriteRepository = $favoriteRepo;
    }

    /**
     * Display a listing of the favorite.
     *
     * @param favoriteDataTable $favoriteDataTable
     * @return Response
     */
    public function index(favoriteDataTable $favoriteDataTable)
    {
        return $favoriteDataTable->render('favorites.index');
    }

    /**
     * Show the form for creating a new favorite.
     *
     * @return Response
     */
    public function create()
    {
        return view('favorites.create');
    }

    /**
     * Store a newly created favorite in storage.
     *
     * @param CreatefavoriteRequest $request
     *
     * @return Response
     */
    public function store(CreatefavoriteRequest $request)
    {
        $input = $request->all();

        $favorite = $this->favoriteRepository->create($input);

        Flash::success('Favorite saved successfully.');

        return redirect(route('favorites.index'));
    }

    /**
     * Display the specified favorite.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $favorite = $this->favoriteRepository->findWithoutFail($id);

        if (empty($favorite)) {
            Flash::error('Favorite not found');

            return redirect(route('favorites.index'));
        }

        return view('favorites.show')->with('favorite', $favorite);
    }

    /**
     * Show the form for editing the specified favorite.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $favorite = $this->favoriteRepository->findWithoutFail($id);

        if (empty($favorite)) {
            Flash::error('Favorite not found');

            return redirect(route('favorites.index'));
        }

        return view('favorites.edit')->with('favorite', $favorite);
    }

    /**
     * Update the specified favorite in storage.
     *
     * @param  int              $id
     * @param UpdatefavoriteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatefavoriteRequest $request)
    {
        $favorite = $this->favoriteRepository->findWithoutFail($id);

        if (empty($favorite)) {
            Flash::error('Favorite not found');

            return redirect(route('favorites.index'));
        }

        $favorite = $this->favoriteRepository->update($request->all(), $id);

        Flash::success('Favorite updated successfully.');

        return redirect(route('favorites.index'));
    }

    /**
     * Remove the specified favorite from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $favorite = $this->favoriteRepository->findWithoutFail($id);

        if (empty($favorite)) {
            Flash::error('Favorite not found');

            return redirect(route('favorites.index'));
        }

        $this->favoriteRepository->delete($id);

        Flash::success('Favorite deleted successfully.');

        return redirect(route('favorites.index'));
    }
}
