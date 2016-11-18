<?php

namespace App\Logic\Stage;


use App\Logic\DocumentType\DueDiligenceReport;
use App\Logic\LoginUser\LoginUserKeeper;

class DueDiligence extends AbstractStage
{
    protected $stageID = STAGE_ID_DUE_DILIGENCE;

    protected function instantiateNextStage()
    {
        return new Review($this->project);
    }

    public function renderFunctionArea()
    {
        $dueDiligence = $this->project->duediligence;
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
        $ddReport = $this->project->document()->where(
            'type',
            DOC_TYPE_DUE_DILIGENCE_REPORT
        )->first();

        $unAnswered = $this->project->duediligence()->whereNull('answer')->get();

        return !is_null($ddReport) && $unAnswered->isEmpty();
    }

    public function operate($para)
    {
        $this->logOperation();
    }
}