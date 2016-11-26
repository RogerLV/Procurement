<?php

namespace App\Logic\Stage\ProjectStages;

use App\Logic\DocumentType\VendorInvitation;
use App\Logic\DocumentType\ReviewReport;
use App\Models\Project;

class RecordSingleSourcing extends Record
{
    use TRecordChildMethods;

    protected $toBeFilledUpNegotiations = true;

    public function __construct(Project $projectIns)
    {
        $this->referrer = $projectIns;
        $this->mandatoryDocTypes = [
            new VendorInvitation()
        ];
        $this->optionalDocTypes = [];
        $this->reviewDocTypes = [
            new ReviewReport()
        ];
    }
}