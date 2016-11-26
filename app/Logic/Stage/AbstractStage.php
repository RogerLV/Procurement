<?php

namespace App\Logic\Stage;

use Config;

abstract class AbstractStage
{
    protected $stageID;
    protected $referrer;

    abstract public function renderFunctionArea();
    abstract public function renderInfoArea();
    abstract public function operate($para = null);
    abstract public function getNextStage();

    public function getStageName()
    {
        return Config::get('constants.stageNames.'.$this->stageID);
    }

    public function getStageID()
    {
        return $this->stageID;
    }

    protected function getPreviousStage()
    {
        return null;
    }
}