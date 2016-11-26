<?php

namespace App\Logic\StageOld;


use App\Logic\DocumentType\TenderInvitation;
use App\Logic\DocumentType\EvaluationReport;
use App\Logic\DocumentType\SignedTenderForm;
use App\Logic\DocumentType\VendorClarification;
use App\Models\Project;

class RecordInviteTender extends Record
{
    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
        $this->mandatoryDocTypes = [
            new TenderInvitation(),
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