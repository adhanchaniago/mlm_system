<?php



namespace App\Http\Controllers;



use App\Http\Requests\CreateweeklyfeesRequest;

use App\Http\Requests\UpdateweeklyfeesRequest;

use App\Repositories\weeklyfeesRepository;

use App\Http\Controllers\AppBaseController;

use Illuminate\Http\Request;

use Flash;

use Prettus\Repository\Criteria\RequestCriteria;

use Response;

use App\DataTables\weeklyfeesDataTable;



class weeklyfeesController extends AppBaseController

{

    /** @var  weeklyfeesRepository */

    private $weeklyfeesRepository;



    public function __construct(weeklyfeesRepository $weeklyfeesRepo)

    {

        $this->weeklyfeesRepository = $weeklyfeesRepo;

    }



    /**

     * Display a listing of the weeklyfees.

     *

     * @param Request $request

     * @return Response

     */

    public function index(weeklyfeesDataTable $weeklyfeesDataTable)

    {
        return $weeklyfeesDataTable->render('weeklyfees.index');
    }



    /**

     * Show the form for creating a new weeklyfees.

     *

     * @return Response

     */

    public function create()

    {

        return view('weeklyfees.create');

    }



    /**

     * Store a newly created weeklyfees in storage.

     *

     * @param CreateweeklyfeesRequest $request

     *

     * @return Response

     */

    public function store(CreateweeklyfeesRequest $request)

    {

        $input = $request->all();



        $weeklyfees = $this->weeklyfeesRepository->create($input);



        Flash::success(trans('weekly.saved'));



        return redirect(route('weeklyfees.index'));

    }



    /**

     * Display the specified weeklyfees.

     *

     * @param  int $id

     *

     * @return Response

     */

    public function show($id)

    {

        $weeklyfees = $this->weeklyfeesRepository->findWithoutFail($id);



        if (empty($weeklyfees)) {

            Flash::error(trans('weekly.error'));



            return redirect(route('weeklyfees.index'));

        }



        return view('weeklyfees.show')->with('weeklyfees', $weeklyfees);

    }



    /**

     * Show the form for editing the specified weeklyfees.

     *

     * @param  int $id

     *

     * @return Response

     */

    public function edit($id)

    {

        $weeklyfees = $this->weeklyfeesRepository->findWithoutFail($id);



        if (empty($weeklyfees)) {

            Flash::error(trans('weekly.error'));



            return redirect(route('weeklyfees.index'));

        }



        return view('weeklyfees.edit')->with('weeklyfees', $weeklyfees);

    }



    /**

     * Update the specified weeklyfees in storage.

     *

     * @param  int              $id

     * @param UpdateweeklyfeesRequest $request

     *

     * @return Response

     */

    public function update($id, UpdateweeklyfeesRequest $request)

    {

        $weeklyfees = $this->weeklyfeesRepository->findWithoutFail($id);



        if (empty($weeklyfees)) {

            Flash::error(trans('weekly.error'));



            return redirect(route('weeklyfees.index'));

        }



        $weeklyfees = $this->weeklyfeesRepository->update($request->all(), $id);



        Flash::success(trans('weekly.update'));



        return redirect(route('weeklyfees.index'));

    }



    /**

     * Remove the specified weeklyfees from storage.

     *

     * @param  int $id

     *

     * @return Response

     */

    public function destroy($id)

    {

        $weeklyfees = $this->weeklyfeesRepository->findWithoutFail($id);



        if (empty($weeklyfees)) {

            Flash::error(trans('weekly.error'));



            return redirect(route('weeklyfees.index'));

        }



        $this->weeklyfeesRepository->delete($id);



        Flash::success(trans('weekly.delete'));



        return redirect(route('weeklyfees.index'));

    }

}

