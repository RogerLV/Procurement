<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Negotiation;
use App\Models\Project;

class PriceNegotiationController extends Controller
{
    public function add()
    {
        if (empty($projectID = trim(request()->input('projectid')))
            || empty($time = trim(request()->input('time')))
            || empty($venue = trim(request()->input('venue')))
            || empty($participants = trim(request()->input('participants')))
            || empty($content = trim(request()->input('content')))) {
            throw new AppException('NGTCLT001');
        }

        $project = Project::getIns($projectID);

        $userLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        if ($project->lanID != $userLanID || STAGE_ID_RECORD != $project->stage) {
            throw new AppException('NGTCTL002', 'Cannot add negotiation record at current phase.');
        }

        $negotiationRecord = new Negotiation();
        $negotiationRecord->roundNo = $project->negotiations->max('roundNo')+1;
        $negotiationRecord->time = $time;
        $negotiationRecord->venue = $venue;
        $negotiationRecord->participants = $participants;
        $negotiationRecord->content = nl2br(htmlentities($content));
        $project->negotiations()->save($negotiationRecord);

        return response()->json(['status' => 'good']);
    }
}
