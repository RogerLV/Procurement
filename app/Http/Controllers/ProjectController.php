<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Logic\DocumentHandler;
use App\Logic\Stage\Initialization;
use App\Models\User;
use Gate;
use Config;
use File;
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
        $initStage = new Initialization($para);
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

    public function listPage()
    {
        return view('project/list')
                ->with('title', PAGE_NAME_PROJECT_LIST)
                ->with('projects', Project::orderBy('id')->get())
                ->with('deptInfo', Department::all()->keyBy('dept'));
    }

    public function display($id)
    {
        $projectIns = Project::find($id);
        if (is_null($projectIns)) {
            throw new AppException('PRJ006', 'Incorrect Project ID.');
        }

        if (Gate::forUser($this->loginUser)->denies('project-visible', $projectIns)) {
            throw new AppException('PRJ007', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $basicInfoSegment = view('project/display/basicinfo')
                            ->with('project', $projectIns)
                            ->with('deptInfo', Department::where('dept', $projectIns->dept)->get()->keyBy('dept'))
                            ->with('userInfo', User::where('lanID', $projectIns->lanID)->get()->keyBy('lanID'));

        $documents = DocumentHandler::getByReferenceIns($projectIns);
        $lanIDs = $documents->pluck('lanID')->toArray();

        $documentsSegment = view('project/display/documents')
                            ->with('documents', $documents)
                            ->with('userInfo', User::whereIn('lanID', $lanIDs)->get()->keyBy('lanID'))
                            ->with('documentTypeNames', Config::get('constants.documentTypeNames'))
                            ->with('project', $projectIns);

        return view('project/display/display')
                ->with('title', PAGE_NAME_PROJECT_DISPLAY)
                ->with('basicInfo', $basicInfoSegment)
                ->with('documents', $documentsSegment);
    }
}
