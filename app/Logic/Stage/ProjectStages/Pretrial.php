<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TApprove;

class Pretrial extends ProjectStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_PRETRIAL;
    protected $executer = [
        ROLE_NAME_SECRETARIAT
    ];

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
        // display pass sign result only in pass sign stage
        if (STAGE_ID_PASS_SIGN == $this->referrer->stage) {
            $passSignIns = new PassSign($this->referrer);
            return $passSignIns->renderResult();
        }
    }

    public function getPreviousStage()
    {
        return new SelectMode($this->referrer);
    }
}