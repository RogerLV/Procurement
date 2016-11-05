<?php

namespace App\Logic\Stage;


abstract class AbstractStage
{
    protected $project;

    public function getProject()
    {
        return $this->project;
    }
}