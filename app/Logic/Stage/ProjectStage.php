<?php

namespace App\Logic\Stage;


use App\Models\Project;

abstract class ProjectStage extends AbstractStage
{
    public function __construct(Project $project)
    {
        $this->referrer = $project;
    }

    public function getProject()
    {
        return $this->referrer;
    }
}