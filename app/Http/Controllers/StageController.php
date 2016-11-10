<?php

namespace App\Http\Controllers;


use App\Logic\Stage\StageHandler;
use Gate;
use App\Exceptions\AppException;
use App\Models\Project;

use DB;

class StageController extends Controller
{
    protected $projectIns;
    protected $stageIns;

    public function __construct()
    {
        parent::__construct();

        if (empty($projectID = request()->input('projectid'))) {
            throw new AppException('STG001', 'Incorrect project info.');
        }

        $this->projectIns = Project::find($projectID);

        if (is_null($this->projectIns)) {
            throw new AppException('STG002', 'Incorrect project info.');
        }

        if (Gate::forUser($this->loginUser)->denies('project-operable', $this->projectIns)) {
            throw new AppException('STG003', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $this->stageIns = StageHandler::getStageIns($this->projectIns);
    }

    public function inviteDept()
    {
        if (empty($para['memberCount'] = request()->input('membercount'))) {
            throw new AppException('STG004', 'Data Error.');
        }

        $para['invitedDepts'] = request()->input('inviteddepts');
        $this->stageIns->operate($para);

        return response()->json(['status' => 'good']);
    }

    public function assignMaker()
    {
        $this->stageIns->operate();

        return response()->json(['status' => 'good']);
    }

}
