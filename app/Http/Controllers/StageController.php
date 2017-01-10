<?php

namespace App\Http\Controllers;


use App\Logic\Stage\ProjectStages\StageHandler as ProjectStageHandler;
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

        $this->stageIns = ProjectStageHandler::getProjectStageIns($this->projectIns);
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
        if (!$this->stageIns->canStageUp()) {
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

    // for stage pretrial reject, pass sign, manager approve
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

    public function complete()
    {
        if (!$this->stageIns->canStageUp()) {
            switch ($this->stageIns->getStageID()) {
                case STAGE_ID_INVITE_DEPT:
                    throw new AppException('STG016', ERROR_MESSAGE_LAUNCHING_DEPT_MISSING);

                default:
                    throw new AppException('STG017', 'Stage finish conditions are not met.');
            }
        }

        $this->stageIns->operate(null);

        return response()->json(['status' => 'good']);
    }

    public function assignComplete()
    {
        if (!$this->stageIns->canLog()) {
            throw new AppException('STG018', ERROR_MESSAGE_MAKER_NOT_ASSIGNED);
        }

        $this->stageIns->operate(null);

        return response()->json(['status' => 'good']);
    }

    public function pretrial()
    {
        if (empty($skipPassSign = trim(request()->input('skipPassSign')))) {
            throw new AppException('STG019');
        }

        $skipPassSign = $skipPassSign == 'true';
        $comment = trim(request()->input('comment'));
        if ($skipPassSign && empty($comment)) {
            throw new AppException('STG020', ERROR_MESSAGE_SKIP_REASON_MANDATORY);
        }

        $this->stageIns->trialPass($skipPassSign, $comment);

        return response()->json(['status' => 'good']);
    }
}
