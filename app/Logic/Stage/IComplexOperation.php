<?php

namespace App\Logic\Stage;


interface IComplexOperation
{
    public function logOperation($comment = null);
    public function canStageUp();
}