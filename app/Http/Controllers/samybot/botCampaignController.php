<?php

namespace App\Http\Controllers\samybot;

use App\Http\Controllers\Controller;
use App\DataTables\botCampaignDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatebotCampaignRequest;
use App\Http\Requests\UpdatebotCampaignRequest;
use App\Repositories\botCampaignRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class botCampaignController extends Controller
{
    /** @var  botCampaignRepository */
    private $botCampaignRepository;

    public function __construct(botCampaignRepository $botCampaignRepo)
    {
        $this->middleware('auth');
        $this->botCampaignRepository = $botCampaignRepo;
    }

    /**
     * Display a listing of the botCampaign.
     *
     * @param botCampaignDataTable $botCampaignDataTable
     * @return Response
     */
    public function index(botCampaignDataTable $botCampaignDataTable)
    {
        return $botCampaignDataTable->render('bot_campaigns.index');
    }

    /**
     * Show the form for creating a new botCampaign.
     *
     * @return Response
     */
    public function create()
    {
        return view('bot_campaigns.create');
    }

    /**
     * Store a newly created botCampaign in storage.
     *
     * @param CreatebotCampaignRequest $request
     *
     * @return Response
     */
    public function store(CreatebotCampaignRequest $request)
    {
        $input = $request->all();

        $botCampaign = $this->botCampaignRepository->create($input);

        Flash::success('Bot Campaign saved successfully.');

        return redirect(route('botCampaigns.index'));
    }

    /**
     * Display the specified botCampaign.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $botCampaign = $this->botCampaignRepository->findWithoutFail($id);

        if (empty($botCampaign)) {
            Flash::error('Bot Campaign not found');

            return redirect(route('botCampaigns.index'));
        }

        return view('bot_campaigns.show')->with('botCampaign', $botCampaign);
    }

    /**
     * Show the form for editing the specified botCampaign.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $botCampaign = $this->botCampaignRepository->findWithoutFail($id);

        if (empty($botCampaign)) {
            Flash::error('Bot Campaign not found');

            return redirect(route('botCampaigns.index'));
        }

        return view('bot_campaigns.edit')->with('botCampaign', $botCampaign);
    }

    /**
     * Update the specified botCampaign in storage.
     *
     * @param  int              $id
     * @param UpdatebotCampaignRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatebotCampaignRequest $request)
    {
        $botCampaign = $this->botCampaignRepository->findWithoutFail($id);

        if (empty($botCampaign)) {
            Flash::error('Bot Campaign not found');

            return redirect(route('botCampaigns.index'));
        }

        $botCampaign = $this->botCampaignRepository->update($request->all(), $id);

        Flash::success('Bot Campaign updated successfully.');

        return redirect(route('botCampaigns.index'));
    }

    /**
     * Remove the specified botCampaign from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $botCampaign = $this->botCampaignRepository->findWithoutFail($id);

        if (empty($botCampaign)) {
            Flash::error('Bot Campaign not found');

            return redirect(route('botCampaigns.index'));
        }

        $this->botCampaignRepository->delete($id);

        Flash::success('Bot Campaign deleted successfully.');

        return redirect(route('botCampaigns.index'));
    }
    function compress($source, $destination, $quality,$mime) {



// Set a maximum height and width
        $width = 200;
        $height = 200;

// Content type
        header('Content-Type: image/'.$mime);

// Get new dimensions
        list($width_orig, $height_orig) = \getimagesize($source);

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }

// Resample
        $image_p = \imagecreatetruecolor($width, $height);
        $info = \getimagesize($source);

        if ($info['mime'] == 'image/jpg')
            $image = \imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/jpeg')
            $image = \imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = \imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = \imagecreatefrompng($source);


//            $image = \imagecreatefromjpeg($filename);
        \imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
        \imagejpeg($image_p, $destination, $quality);
        return $destination;
    }
}
