<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Models\Project;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\ScoreHandler;

class Record extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_RECORD;
    protected $executer = [
        ROLE_NAME_PROJECT_LAUNCHER,
        ROLE_NAME_DEPT_MAKER
    ];
    private $instance;

    protected $mandatoryDocTypes = [];
    protected $optionalDocTypes = [];
    protected $reviewDocTypes = [];
    protected $uploadFileTypes = [];
    protected $toBeScored = true;
    protected $toBeFilledUpNegotiations = false;

    public function __construct(Project $projectIns)
    {
        parent::__construct($projectIns);

        switch($projectIns->approach) {
            case 'OpenTender':
                $this->instance = new RecordOpenTender($projectIns);
                break;

            case 'InviteTender':
                $this->instance = new RecordInviteTender($projectIns);
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

    public function getNextStage()
    {
        return new Summarize($this->referrer);
    }

    final public function renderFunctionArea()
    {
        return view('project/display/function/record')
            ->with('title', $this->getStageName())
            ->with('renderDocType', $this->instance->getRenderDocTypes())
            ->with('uploadedTypes', $this->instance->getUploadedDocTypes())
            ->with('showFinishButton', $this->canStageUp())
            ->with('project', $this->referrer)
            ->with('userLanID', LoginUserKeeper::getUser()->getUserInfo()->lanID)
            ->with('scorePhase', $this->instance->getScorePhase())
            ->with('priceNegotiation', $this->instance->priceNegotiation())
            ->with('negotiations', $this->instance->getNegotiations());
    }

    final public function renderInfoArea()
    {
        list($a, $b, $vendorFinalScores) = ScoreHandler::getScoreDetails($this->referrer);
        return view('project/display/stage/record')
            ->with('toBeScore', $this->instance->toBeScored())
            ->with('vendors', $this->referrer->vendors)
            ->with('vendorFinalScores', $vendorFinalScores)
            ->with('priceNegotiation', $this->instance->priceNegotiation())
            ->with('negotiations', $this->instance->getNegotiations());
    }

    public function canStageUp()
    {
        return $this->instance->canStageUp();
    }

    public function operate($para = null)
    {
        $this->instance->operate($para);
    }
}