<?php

namespace App\Logic\Stage;


use App\Logic\DocumentType\CallForBids;
use App\Logic\DocumentType\EvaluationReport;
use App\Logic\DocumentType\SignedTenderForm;
use App\Logic\DocumentType\VendorClarification;
use App\Models\Project;

class RecordOpenTender extends Record
{
    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
        $this->mandatoryDocTypes = [
            new CallForBids(),
            new SignedTenderForm(),
            new EvaluationReport()
        ];
        $this->optionalDocTypes = [
            new VendorClarification()
        ];
        $this->reviewDocTypes = [];
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        return true;
    }

    public function operate($para)
    {
        $this->logOperation();
    }
}