<?php

namespace App\Logic\Stage;


use App\Logic\DocumentType\ProcurementContract;

class FileContract extends AbstractStage
{
    protected $stageID = STAGE_ID_FILE_CONTRACT;

    protected function instantiateNextStage()
    {
        return new Complete($this->project);
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
        $contractCount = $this->project->document()->where('type', DOC_TYPE_PROCUREMENT_CONTRACT)->count();
        return $contractCount != 0;
    }

    public function operate($para)
    {
        $this->logOperation();
    }
}