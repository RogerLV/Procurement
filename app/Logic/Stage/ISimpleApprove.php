<?php

namespace App\Logic\Stage;


interface ISimpleApprove
{
    public function approve($operation, $comment);
    public function getPreviousStage();
}