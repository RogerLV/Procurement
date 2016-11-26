<?php

namespace App\Logic\StageOld;

class Pretrial extends AbstractStage
{
    protected $stageID = STAGE_ID_PRETRIAL;

    protected function instantiateNextStage()
    {
        if ('OpenTender' == $this->project->approach) {
            return new Record($this->project);
        } else {
            return new PassSign($this->project);
        }
    }

    public function renderFunctionArea()
    {
        $document = $this->project->document()
                         ->where('type', DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION)
                         ->orderBy('id', 'desc')
                         ->first();

        return view('project/display/function/pretrial')
                ->with('title', $this->getStageName())
                ->with('project', $this->project)
                ->with('procurementMethodReport', $document);
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        return false;
    }

    public function operate($para)
    {
        $this->approve($para['operation'], $para['comment']);
    }

    protected function getPreviousStage()
    {
        return new SelectMode($this->project);
    }
}