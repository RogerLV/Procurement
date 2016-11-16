<?php

namespace App\Http\Controllers;


use App\Logic\Stage\StageHandler;
use App\Exceptions\AppException;
use App\Models\Project;
use Gate;
use Config;
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

        $this->projectIns = Project::getIns($projectID);

        if (Gate::forUser($this->loginUser)->denies('project-operable', $this->projectIns)) {
            throw new AppException('STG003', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $this->stageIns = StageHandler::getStageIns($this->projectIns);
    }

    public function inviteDept()
    {
        if (empty($para['memberCount'] = request()->input('membercount'))) {
            throw new AppException('STG004');
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

    public function selectMode()
    {
        if (empty($para['procurementMethod'] = trim(request()->input('procurement-method')))) {
            throw new AppException('STG005');
        }

        if ($this->projectIns->involveReview
            && empty($para['procurementMethodReport'] = request()->file('procurement-method-report'))) {
            throw new AppException('STG006');
        }

        if (!array_key_exists($para['procurementMethod'], Config::get('constants.procurementMethods'))) {
            throw new AppException('STG007', 'Incorrect Info.');
        }

        $this->stageIns->operate($para);

        return response()->json(['status' => 'good']);
    }

    public function finishRecord()
    {
        if (!$this->stageIns->canFinish()) {
            throw new AppException('STG012', 'Stage finish conditions are not met.');
        }

        $this->stageIns->operate(null);

        return response()->json(['status' => 'good']);
    }

    public function summarize()
    {
        if (empty($para['summary'] = trim(request()->input('summary')))) {
            throw new AppException('STG013');
        }

        $this->stageIns->operate($para);

        return response()->json(['status' => 'good']);
    }

    // for stage pretrail, pass sign, manager approve
    public function approve()
    {
        if (empty($para['operation'] = trim(request()->input('operation')))) {
            throw new AppException('STG014');
        }

        if (!in_array($para['operation'], ['approve', 'reject'])) {
            throw new AppException('STG015');
        }

        $para['comment'] = trim(request()->input('comment'));

        $this->stageIns->operate($para);

        return response()->json(['status' => 'good']);
    }
}
