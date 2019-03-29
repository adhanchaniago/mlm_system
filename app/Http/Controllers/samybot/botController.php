<?php

namespace App\Http\Controllers\samybot;

use App\Http\Controllers\Controller;
use App\DataTables\botDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatebotRequest;
use App\Http\Requests\UpdatebotRequest;
use App\Repositories\botRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class botController extends Controller
{
    /** @var  botRepository */
    private $botRepository;

    public function __construct(botRepository $botRepo)
    {
        $this->middleware('auth');
        $this->botRepository = $botRepo;
    }

    /**
     * Display a listing of the bot.
     *
     * @param botDataTable $botDataTable
     * @return Response
     */
    public function index(botDataTable $botDataTable)
    {
        return $botDataTable->render('bots.index');
    }

    /**
     * Show the form for creating a new bot.
     *
     * @return Response
     */
    public function create()
    {
        return view('bots.create');
    }

    /**
     * Store a newly created bot in storage.
     *
     * @param CreatebotRequest $request
     *
     * @return Response
     */
    public function store(CreatebotRequest $request)
    {
        $input = $request->all();

        $bot = $this->botRepository->create($input);

        Flash::success('Bot saved successfully.');

        return redirect(route('bots.index'));
    }

    /**
     * Display the specified bot.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bot = $this->botRepository->findWithoutFail($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        return view('bots.show')->with('bot', $bot);
    }

    /**
     * Show the form for editing the specified bot.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bot = $this->botRepository->findWithoutFail($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        return view('bots.edit')->with('bot', $bot);
    }

    /**
     * Update the specified bot in storage.
     *
     * @param  int              $id
     * @param UpdatebotRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatebotRequest $request)
    {
        $bot = $this->botRepository->findWithoutFail($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        $bot = $this->botRepository->update($request->all(), $id);

        Flash::success('Bot updated successfully.');

        return redirect(route('bots.index'));
    }

    /**
     * Remove the specified bot from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bot = $this->botRepository->findWithoutFail($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        $this->botRepository->delete($id);

        Flash::success('Bot deleted successfully.');

        return redirect(route('bots.index'));
    }
}
