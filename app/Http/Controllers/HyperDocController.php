<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Gate;
use Config;
use App\Exceptions\AppException;
use App\Logic\Stage\ProjectStages\PassSign;

class HyperDocController extends Controller
{
    public function dueDiligence($id)
    {
        $projectIns = Project::getIns($id);

        if (Gate::forUser($this->loginUser)->denies('due-diligence-record-visible', $projectIns)) {
            throw new AppException('HPDCTL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        return view('hyperdocs.duediligence')
            ->with('title', $projectIns->name.': '.PAGE_NAME_DUE_DILIGENCE_RECORD)
            ->with('records', $projectIns->duediligence);
    }

    public function passSign($id)
    {
        $projectIns = Project::getIns($id);

        if ($projectIns->stage <= STAGE_ID_PASS_SIGN) {
            throw new AppException('HPDCTL002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $passSignStage = new PassSign($projectIns);
        $logs = $passSignStage->getCurrentRoundLogs();

        return view('hyperdocs.passsigntable')
            ->with('title', $projectIns->name.': '.PAGE_NAME_PASS_SIGN_TABLE)
            ->with('memberLogs', $logs->whereLoose('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER))
            ->with('viceDirectorLogs', $logs->whereLoose('roleID', ROLE_ID_REVIEW_VICE_DIRECTOR))
            ->with('directorLogs', $logs->whereLoose('roleID', ROLE_ID_REVIEW_DIRECTOR))
            ->with('passSignValues', Config::get('constants.passSignValues'));
    }
}
