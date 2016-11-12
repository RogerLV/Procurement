<?php

namespace App\Logic\Stage;


use App\Models\Project;

class Record extends AbstractStage
{
    protected $stageID = STAGE_ID_RECORD;
    private $instance;

    public function __construct(Project $projectIns)
    {
        parent::__construct($projectIns);

        switch($projectIns->approach) {
            case 'OpenTender':
            case 'InviteTender':
                $this->instance = new RecordTender($projectIns);
                break;

            case 'CompetitiveNegotiation':
                $this->instance = new RecordCompetitiveNegotiation($projectIns);
                break;

            case 'PriceEnquiry':
                $this->instance = new RecordPriceEnquiry($projectIns);
                break;

            case 'SingleSourcing':
                $this->instance = new RecordSingleSourcing($projectIns);
                break;
        }
    }

    protected function instantiateNextStage()
    {
        return new Summarize($this->project);
    }

    public function renderFunctionArea()
    {
        return $this->instance->renderFunctionArea();
    }

    public function renderInfoArea()
    {
        return $this->instance->renderInfoArea();
    }

    public function canStageUp()
    {
        return $this->instance->canStageUp();
    }

    public function operate($para = null)
    {
        return $this->instance->operate($para);
    }
}