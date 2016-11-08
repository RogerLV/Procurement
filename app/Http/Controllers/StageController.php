<?php

namespace App\Http\Controllers;


use Gate;
use App\Exceptions\AppException;
use App\Logic\Stage\Initiate;
use App\Logic\DocumentHandler;

class StageController extends Controller
{
    public function initiate()
    {
        if (Gate::forUser($this->loginUser)->denies('apply-project')) {
            throw new AppException('PRJ002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        if (empty($applicantDept = trim(request()->input('applicant-dept')))
            || empty($applicantLanID = trim(request()->input('applicant-lanid')))
            || empty($para['procurementScope'] = trim(request()->input('procurement-scope')))
            || empty($para['projectName'] = trim(request()->input('project-name')))
            || empty($para['projectBackground'] = trim(request()->input('project-background')))
            || empty($para['projectBudget'] = trim(request()->input('project-budget')))
            || empty($signedReport = request()->file('signed-report'))
            || empty($para['involveReview'] = trim(request()->input('involve-review')))) {
            throw new AppException('PRJ003', 'Data Error.');
        }

        // check applicant lanid and dept info
        if ($applicantLanID != $this->loginUser->getUserInfo()->lanID) {
            throw new AppException('PRJ004', 'Incorrect User Info');
        }
        if ($applicantDept != $this->loginUser->getDepartmentInfo()->dept) {
            throw new AppException('PRJ005', 'Incorrect User Info');
        }

        // create new project
        $initStage = new Initiate();
        $initStage->operate($para);
        $initStage->logOperation();
        $projectIns = $initStage->getProject();


        // handle uploaded file
        DocumentHandler::storeFile($signedReport, $projectIns, DOC_TYPE_SIGNED_REPORT);

        return response()->json([
            'status' => 'good',
            'info' => [
                'id' => $projectIns->id,
            ],
        ]);
    }
}
