<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Gate;
use Config;
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
        return view('project/list')
                ->with('title', PAGE_NAME_PROJECT_LIST)
                ->with('projects', Project::orderBy('id')->get())
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
        $documents = $projectIns->document()->with('uploader')->get();
        $log = $projectIns->log()->with('operator')->get();
        $conversation = $projectIns->conversation()->with('composer')->get();

        return view('project/display/display')
                ->with('project', $projectIns)
                ->with('documents', $documents)
                ->with('logList', $log)
                ->with('conversation', $conversation)
                ->with('documentTypeNames', Config::get('constants.documentTypeNames'))
                ->with('stageNames', Config::get('constants.stageNames'));
    }
}
