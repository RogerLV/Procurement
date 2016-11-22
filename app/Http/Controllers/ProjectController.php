<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Logic\Role\RoleFactory;
use App\Logic\Stage\StageHandler;
use App\Logic\Stage\Initiate;
use Gate;
use Config;
use App\Models\Document;
use App\Models\Project;
use App\Models\Department;

class ProjectController extends Controller
{
    public function apply()
    {
        if (Gate::forUser($this->loginUser)->denies('apply-project')) {
            throw new AppException('PRJ001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        return view('project/apply')
                ->with('title', PAGE_NAME_PROJECT_APPLY)
                ->with('deptInfo', $this->loginUser->getDepartmentInfo())
                ->with('applicantInfo', $this->loginUser->getUserInfo())
                ->with('procurementScopes', Config::get('constants.procurementScopeNames'));
    }

    public function listPage()
    {
        $roleIns = RoleFactory::create($this->loginUser->getActiveRole()->roleID);

        return view('project/list')
                ->with('title', PAGE_NAME_PROJECT_LIST)
                ->with('projects', $roleIns->listProject())
                ->with('deptInfo', Department::all()->keyBy('dept'));
    }

    public function display($id)
    {
        $projectIns = Project::with('department', 'submitter')->find($id);

        if (is_null($projectIns)) {
            throw new AppException('PRJ006', 'Incorrect Project ID.');
        }

        if (Gate::forUser($this->loginUser)->denies('project-visible', $projectIns)) {
            throw new AppException('PRJ007', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        // By using eloquent relationships, mutiple queries would be generated which can be optimized.
        // But due to resource consuming is acceptable, use this elegant way for time being
        $stageView = StageHandler::renderStageView($projectIns);
        $documents = $projectIns->document()->with('uploader')->orderBy('type')->get();

        // document visibility filter
        if (in_array($projectIns->stage, [STAGE_ID_DUE_DILIGENCE, STAGE_ID_REVIEW])
            && $this->loginUser->getActiveRole()->roleID == ROLE_ID_DEPT_MAKER) {
            $documents = $documents->filter(function ($doc) {
                return $doc->type != DOC_TYPE_DUE_DILIGENCE_REPORT;
            });
        }

        $log = $projectIns->log()->with('operator')->get();
        $conversation = $projectIns->conversation()->with('composer')->get();

        return view('project/display/display')
                ->with('project', $projectIns)
                ->with('stageView', $stageView)
                ->with('documents', $documents)
                ->with('logList', $log)
                ->with('conversation', $conversation)
                ->with('documentTypeNames', Config::get('constants.documentTypeNames'))
                ->with('stageNames', Config::get('constants.stageNames'));
    }

    public function create()
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
        $projectIns = Project::createNew($para);
        $initStage = new Initiate($projectIns);
        $initStage->logOperation();

        // handle uploaded file
        Document::storeFile($signedReport, $projectIns, DOC_TYPE_SIGNED_REPORT);

        return response()->json([
            'status' => 'good',
            'info' => [
                'id' => $projectIns->id,
            ],
        ]);
    }
}
