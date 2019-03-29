<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreaterevenuehistoryRequest;
use App\Http\Requests\UpdaterevenuehistoryRequest;
use App\Repositories\revenuehistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\DataTables\revenuehistoryDataTable;
class revenuehistoryController extends AppBaseController
{
    /** @var  revenuehistoryRepository */
    private $revenuehistoryRepository;
    public function __construct(revenuehistoryRepository $revenuehistoryRepo)
    {
	    $this->middleware('auth');
        $this->revenuehistoryRepository = $revenuehistoryRepo;
    }
    /**
     * Display a listing of the revenuehistory.
     *
     * @param Request $request
     * @return Response
     */
    public function index(revenuehistoryDataTable $revenuehistoryDataTable)
    {
        return $revenuehistoryDataTable->render('revenuehistories.index');
    }
    /**
     * Show the form for creating a new revenuehistory.
     *
     * @return Response
     */
    public function create()
    {
        return view('revenuehistories.create');
    }
    /**
     * Store a newly created revenuehistory in storage.
     *
     * @param CreaterevenuehistoryRequest $request
     *
     * @return Response
     */
    public function store(CreaterevenuehistoryRequest $request)
    {
        $input = $request->all();
        $revenuehistory = $this->revenuehistoryRepository->create($input);
        Flash::success(trans('revenue.saved'));
        return redirect(route('revenuehistories.index'));
    }
    /**
     * Display the specified revenuehistory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $revenuehistory = $this->revenuehistoryRepository->findWithoutFail($id);
        if (empty($revenuehistory)) {
            Flash::error(trans('revenue.error'));
            return redirect(route('revenuehistories.index'));
        }
        return view('revenuehistories.show')->with('revenuehistory', $revenuehistory);
    }
    /**
     * Show the form for editing the specified revenuehistory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $revenuehistory = $this->revenuehistoryRepository->findWithoutFail($id);
        if (empty($revenuehistory)) {
            Flash::error(trans('revenue.error'));
            return redirect(route('revenuehistories.index'));
        }
        return view('revenuehistories.edit')->with('revenuehistory', $revenuehistory);
    }
    /**
     * Update the specified revenuehistory in storage.
     *
     * @param  int              $id
     * @param UpdaterevenuehistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdaterevenuehistoryRequest $request)
    {
        $revenuehistory = $this->revenuehistoryRepository->findWithoutFail($id);
        if (empty($revenuehistory)) {
            Flash::error(trans('revenue.error'));
            return redirect(route('revenuehistories.index'));
        }
        $revenuehistory = $this->revenuehistoryRepository->update($request->all(), $id);
        Flash::success(trans('revenue.update'));
        return redirect(route('revenuehistories.index'));
    }
    /**
     * Remove the specified revenuehistory from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $revenuehistory = $this->revenuehistoryRepository->findWithoutFail($id);
        if (empty($revenuehistory)) {
            Flash::error(trans('revenue.error'));
            return redirect(route('revenuehistories.index'));
        }
        $this->revenuehistoryRepository->delete($id);
        Flash::success(trans('revenue.delete'));
        return redirect(route('revenuehistories.index'));
    }
}
