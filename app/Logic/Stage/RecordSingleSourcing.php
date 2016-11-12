<?php

namespace App\Logic\Stage;


use App\Models\Project;

class RecordSingleSourcing extends Record
{
    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
    }

    public function renderFunctionArea()
    {
        return null;
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
        return null;
    }
}