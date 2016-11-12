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

    public function selectMode()
    {
        if (empty($para['selectFromVendor'] = trim(request()->input('select-from-vendor')))
            || empty($para['procurementMethod'] = trim(request()->input('procurement-method')))) {
            throw new AppException('STG005', 'Data Error');
        }

        if ($this->projectIns->involveReview
            && empty($para['procurementMethodReport'] = request()->file('procurement-method-report'))) {
            throw new AppException('STG006', 'Data Error');
        }

        if (!array_key_exists($para['procurementMethod'], Config::get('constants.procurementMethods'))) {
            throw new AppException('STG007', 'Incorrect Info.');
        }

        $this->stageIns->operate($para);

        return response()->json(['status' => 'good']);
    }

    public function pretrial()
    {
        if (empty($para['operation'] = trim(request()->input('operation')))) {
            throw new AppException('STG008', 'Data Error.');
        }

        if (!in_array($para['operation'], ['approve', 'reject'])) {
            throw new AppException('STG009', 'Data Error.');
        }

        $para['comment'] = trim(request()->input('comment'));

        $this->stageIns->operate($para);

        return response()->json(['status' => 'good']);
    }

    public function passSign()
    {
        if (empty($para['operation'] = trim(request()->input('operation')))) {
            throw new AppException('STG010', 'Data Error');
        }

        if (!in_array($para['operation'], ['approve', 'reject'])) {
            throw new AppException('STG011', 'Data Error.');
        }

        $para['comment'] = trim(request()->input('comment'));

        $this->stageIns->operate($para);

        return response()->json(['status' => 'good']);
    }
}
