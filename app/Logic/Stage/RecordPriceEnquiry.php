<?php

namespace App\Logic\Stage;


use App\Logic\DocumentType\ProjectInquiry;
use App\Logic\DocumentType\ReviewReport;
use App\Models\Project;

class RecordPriceEnquiry extends Record
{
    protected $toBeScored = false;

    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
        $this->mandatoryDocTypes = [
            new ProjectInquiry()
        ];
        $this->optionalDocTypes = [];
        $this->reviewDocTypes = [
            new ReviewReport()
        ];
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        return true;
    }

    public function operate($para = null)
    {
        $this->logOperation();
    }
}