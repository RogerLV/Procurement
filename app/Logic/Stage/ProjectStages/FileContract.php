<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Logic\DocumentType\ProcurementContract;

class FileContract extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_FILE_CONTRACT;
    protected $executer = [
        ROLE_NAME_PROJECT_LAUNCHER
    ];

    public function getNextStage()
    {
        return new Complete($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/filecontract')
            ->with('title', $this->getStageName())
            ->with('docTypeIns', new ProcurementContract())
            ->with('showFinishButton', $this->canStageUp());
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        $contractCount = $this->referrer->document()->where('type', DOC_TYPE_PROCUREMENT_CONTRACT)->count();
        return $contractCount != 0;
    }
}