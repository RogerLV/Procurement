<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Logic\ConversationHandler;
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

        $documents = DocumentHandler::getByReferenceIns($projectIns);
        $logList = ProjectStageLog::where('projectID', $projectIns->id)->orderBy('id')->get();
        $conversation = ConversationHandler::getByReferenceIns($projectIns);

        $lanIDs = collect([$projectIns->lanID])
                    ->merge($documents->pluck('lanID'))
                    ->merge($logList->pluck('lanID'))
                    ->merge($conversation->pluck('lanID'))
                    ->unique()
                    ->toArray();

        return view('project/display/display')
                ->with('project', $projectIns)
                ->with('deptInfo', $deptInfo)
                ->with('userInfo', User::whereIn('lanID', $lanIDs)->get()->keyBy('lanID'))
                ->with('documents', $documents)
                ->with('documentTypeNames', Config::get('constants.documentTypeNames'))
                ->with('logList', $logList)
                ->with('stageNames', Config::get('constants.stageNames'))
                ->with('conversation', $conversation);
    }
}
