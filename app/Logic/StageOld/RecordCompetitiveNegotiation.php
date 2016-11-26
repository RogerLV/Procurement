<?php

namespace App\Logic\StageOld;


use App\Logic\DocumentType\ReviewReport;
use App\Logic\DocumentType\VendorInvitation;
use App\Models\Project;

class RecordCompetitiveNegotiation extends Record
{
    protected $toBeFilledUpNegotiations = true;

    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
        $this->mandatoryDocTypes = [
            new VendorInvitation()
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

    public function operate($para)
    {
        $this->logOperation();
    }
}