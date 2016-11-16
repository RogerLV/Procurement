<?php

namespace App\Logic\Stage;


use Config;
use App\Models\Document;
use App\Models\UpdateLog as Log;

class SelectMode extends AbstractStage
{
    protected $stageID = STAGE_ID_SELECT_MODE;

    protected function instantiateNextStage()
    {
        return $this->project->involveReview
                ? new Pretrial($this->project)
                : new Record($this->project);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/selectmode')
                ->with('title', $this->getStageName())
                ->with('uploadReport', $this->project->involveReview)
                ->with('procurementMethods', Config::get('constants.procurementMethods'));
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        return !is_null($this->project->approach);
    }

    public function operate($para)
    {
        $this->project->approach = $para['procurementMethod'];

        $oldVal = $this->project->getOriginal();
        $this->project->save();
        Log::logUpdate($this->project, $oldVal);

        if ($this->project->involveReview) {
            Document::storeFile(
                $para['procurementMethodReport'],
                $this->project,
                DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION
            );
        }

        $this->logOperation();
    }
}