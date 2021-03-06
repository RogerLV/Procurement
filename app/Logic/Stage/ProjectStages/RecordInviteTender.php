<?php

namespace App\Logic\Stage\ProjectStages;

use App\Logic\DocumentType\TenderInvitation;
use App\Logic\DocumentType\SignedTenderForm;
use App\Logic\DocumentType\EvaluationReport;
use App\Logic\DocumentType\VendorClarification;
use App\Models\Project;

class RecordInviteTender extends Record
{
    use TRecordChildMethods;

    public function __construct(Project $projectIns)
    {
        $this->referrer = $projectIns;
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
}