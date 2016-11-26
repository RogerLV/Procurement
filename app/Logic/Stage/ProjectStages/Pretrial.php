<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TApprove;

class Pretrial extends ProjectStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_PRETRIAL;

    public function getNextStage()
    {
        if ('OpenTender' == $this->referrer->approach) {
            return new Record($this->referrer);
        } else {
            return new PassSign($this->referrer);
        }
    }

    public function renderFunctionArea()
    {
        $document = $this->referrer->document()
            ->where('type', DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION)
            ->orderBy('id', 'desc')
            ->first();

        return view('project/display/function/pretrial')
            ->with('title', $this->getStageName())
            ->with('project', $this->referrer)
            ->with('procurementMethodReport', $document);
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function getPreviousStage()
    {
        return new SelectMode($this->referrer);
    }
}