<?php

namespace App\Http\Controllers;

use App\Logic\Stage\ProjectStages\PassSign;
use App\Models\Project;
use Gate;
use Config;

class PassSignController extends Controller
{
    public function showInPage($id)
    {
        $projectIns = Project::getIns($id);

        // check visibility
        if (Gate::forUser($this->loginUser)->denies('project-visible', $projectIns)) {
            throw new AppException('PSSCTL01', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        if ($projectIns->stage <= STAGE_ID_PASS_SIGN) {
            throw new AppException('PSSCTL02', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $passSignStage = new PassSign($projectIns);
        $logs = $passSignStage->getCurrentRoundLogs();

        return view('passsigntable')
                ->with('memberLogs', $logs->whereLoose('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER))
                ->with('viceDirectorLogs', $logs->whereLoose('roleID', ROLE_ID_REVIEW_VICE_DIRECTOR))
                ->with('directorLogs', $logs->whereLoose('roleID', ROLE_ID_REVIEW_DIRECTOR))
                ->with('passSignValues', Config::get('constants.passSignValues'));
    }
}
