<?php

namespace App\Logic\Stage\ProjectStages;

use App\Logic\DocumentType\ProjectInquiry;
use App\Logic\DocumentType\ReviewReport;
use App\Models\Project;

class RecordPriceEnquiry extends Record
{
    use TRecordChildMethods;

    protected $toBeScored = false;

    public function __construct(Project $projectIns)
    {
        $this->referrer = $projectIns;
        $this->mandatoryDocTypes = [
            new ProjectInquiry()
        ];
        $this->optionalDocTypes = [];
        $this->reviewDocTypes = [
            new ReviewReport()
        ];
    }
}