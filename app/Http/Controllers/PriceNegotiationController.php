<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Models\Negotiation;
use App\Models\Project;
use Gate;

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

        // only submitter and app admin can operate at record stage
        if (STAGE_ID_RECORD != $project->stage
            || (!ROLE_ID_APP_ADMIN == $this->loginUser->getActiveRole()->roleID
            && $project->lanID != $this->loginUser->getUserInfo()->lanID)) {
            throw new AppException('NGTCTL002', 'Cannot add negotiation record at current phase.');
        }

        $negotiationRecord = new Negotiation();
        $negotiationRecord->roundNo = $project->negotiations->max('roundNo')+1;
        $negotiationRecord->time = $time;
        $negotiationRecord->venue = $venue;
        $negotiationRecord->participants = $participants;
        $negotiationRecord->content = nl2br(htmlentities($content));
        $negotiationRecord->lanID = $this->loginUser->getUserInfo()->lanID;
        $project->negotiations()->save($negotiationRecord);

        return response()->json(['status' => 'good']);
    }

    public function showInPage($id)
    {
        $projectIns = Project::getIns($id);

        // check project visibility
        if (Gate::forUser($this->loginUser)->denies('project-visible', $projectIns)) {
            throw new AppException('NGTCTL003', ERROR_MESSAGE_NOT_AUTHORIZED_OPERATE);
        }

        $negotiations = $projectIns->negotiations;
        if ($negotiations->isEmpty()) {
            throw new AppException('NGTCTL004', ERROR_MESSAGE_NOT_AUTHORIZED_OPERATE);
        }

        return view('negotiation')
                ->with('title', PAGE_NAME_NEGOTIATION)
                ->with('negotiations', $negotiations);
    }
}
