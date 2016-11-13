<?php

namespace App\Logic\Stage;


use App\Models\Project;

class RecordInviteTender extends Record
{
    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
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