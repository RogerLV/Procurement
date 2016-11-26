<?php

namespace App\Logic\Stage\ProjectStages;

use App\Logic\DocumentType\CallForBids;
use App\Logic\DocumentType\SignedTenderForm;
use App\Logic\DocumentType\EvaluationReport;
use App\Logic\DocumentType\VendorClarification;
use App\Models\Project;

class RecordOpenTender extends Record
{
    use TRecordChildMethods;

    public function __construct(Project $projectIns)
    {
        $this->referrer = $projectIns;
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
}