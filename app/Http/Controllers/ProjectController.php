<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Logic\DocumentHandler;
use App\Models\ProjectStageLog;
use Gate;
use Config;
use App\Models\Project;
use App\Models\Department;
use App\Models\User;

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
        $deptInfo = Department::where('dept', $projectIns->dept)->get()->keyBy('dept');

        $basicInfoSegment = view('project/display/basicinfo')
                            ->with('project', $projectIns)
                            ->with('deptInfo', $deptInfo)
                            ->with('userInfo', User::where('lanID', $projectIns->lanID)->get()->keyBy('lanID'));

        $documents = DocumentHandler::getByReferenceIns($projectIns);
        $lanIDs = $documents->pluck('lanID')->toArray();

        $documentsSegment = view('project/display/documents')
                            ->with('documents', $documents)
                            ->with('userInfo', User::whereIn('lanID', $lanIDs)->get()->keyBy('lanID'))
                            ->with('documentTypeNames', Config::get('constants.documentTypeNames'))
                            ->with('project', $projectIns);

        $logList = ProjectStageLog::where('projectID', $projectIns->id)->orderBy('id')->get();
        $lanIDs = $logList->pluck('lanID')->toArray();

        $stageLogSegment = view('project/display/stageloglist')
                            ->with('logList', $logList)
                            ->with('userInfo', User::whereIn('lanID', $lanIDs)->get()->keyBy('lanID'))
                            ->with('stageNames', Config::get('constants.stageNames'));

        return view('project/display/display')
                ->with('title', PAGE_NAME_PROJECT_DISPLAY)
                ->with('basicInfo', $basicInfoSegment)
                ->with('documents', $documentsSegment)
                ->with('stageLogList', $stageLogSegment);
    }
}
