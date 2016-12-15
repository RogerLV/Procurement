<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\DocumentType\DueDiligenceReport;

class DueDiligence extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_DUE_DILIGENCE;
    protected $executer = [
        ROLE_NAME_DUE_DILIGENCE_MEMBER,
        ROLE_NAME_PROJECT_LAUNCHER
    ];

    public function getNextStage()
    {
        return new Review($this->referrer);
    }

    public function renderFunctionArea()
    {
        $dueDiligence = $this->referrer->duediligence;
        switch (LoginUserKeeper::getUser()->getActiveRole()->roleID) {
            case ROLE_ID_DEPT_MAKER:
                return view('project/display/function/duediligenceanswer')
                    ->with('title', $this->getStageName())
                    ->with('dueDiligence', $dueDiligence);

            case ROLE_ID_DUE_DILIGENCE_MEMBER:
                return view('project/display/function/duediligencerequest')
                    ->with('title', $this->getStageName())
                    ->with('dueDiligence', $dueDiligence)
                    ->with('docTypeIns', new DueDiligenceReport())
                    ->with('showFinishButton', $this->canStageUp());
        }
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        $ddReport = $this->referrer->document()->where(
            'type',
            DOC_TYPE_DUE_DILIGENCE_REPORT
        )->first();

        $unAnswered = $this->referrer->duediligence()->whereNull('answer')->get();

        return !is_null($ddReport) && $unAnswered->isEmpty();
    }
}