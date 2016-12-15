<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Models\Document;
use App\Models\UpdateLog as Log;
use Config;

class SelectMode extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_SELECT_MODE;
    protected $executer = [
        ROLE_NAME_PROJECT_LAUNCHER
    ];

    public function getNextStage()
    {
        return $this->referrer->involveReview
            ? new Pretrial($this->referrer)
            : new Record($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/selectmode')
            ->with('title', $this->getStageName())
            ->with('uploadReport', $this->referrer->involveReview)
            ->with('procurementMethods', Config::get('constants.procurementMethods'));
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        return !is_null($this->referrer->approach);
    }

    public function operate($para = null)
    {
        $this->referrer->approach = $para['procurementMethod'];

        $oldVal = $this->referrer->getOriginal();
        $this->referrer->save();
        Log::logUpdate($this->referrer, $oldVal);

        if ($this->referrer->involveReview) {
            Document::storeFile(
                $para['procurementMethodReport'],
                $this->referrer,
                DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION
            );
        }

        $this->logOperation();
    }
}