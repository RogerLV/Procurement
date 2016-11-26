<?php

namespace App\Logic\StageOld;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\ScoreHandler;
use App\Models\Project;

class Record extends AbstractStage
{
    protected $stageID = STAGE_ID_RECORD;
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

    public function getRenderDocTypes()
    {
        if ($this->project->involveReview) {
            return array_merge(
                $this->mandatoryDocTypes,
                $this->reviewDocTypes,
                $this->optionalDocTypes
            );
        } else  {
            return array_merge(
                $this->mandatoryDocTypes,
                $this->optionalDocTypes
            );
        }
    }

    public function getUploadedDocTypes()
    {
        if (empty($this->uploadFileTypes)) {

            // cache result to reduce query executions.
            $renderedDocTypes = $this->getRenderDocTypes();
            $docTypeIDs = [];
            foreach ($renderedDocTypes as $docTypeIns) {
                $docTypeIDs[] = $docTypeIns->getTypeID();
            }

            $this->uploadFileTypes = $this->project->document()
                                        ->whereIn('type', $docTypeIDs)->get()
                                        ->pluck('type')->unique()->toArray();
        }

        return $this->uploadFileTypes;
    }

    public function childCanFinish()
    {
        // user role check
        if (LoginUserKeeper::getUser()->getUserInfo()->lanID != $this->project->lanID) {
            return false;
        }

        // uploaded docs check
        $uploadDocTypes = $this->getUploadedDocTypes();
        $stageDocTypes = $this->project->involveReview ?
            array_merge($this->mandatoryDocTypes, $this->reviewDocTypes) :
            $this->mandatoryDocTypes;

        foreach ($stageDocTypes as $docType) {
            if (!in_array($docType->getTypeID(), $uploadDocTypes)) {
                return false;
            }
        }

        // to be scored check
        if ($this->toBeScored) {
            $scoresCount = $this->project->scores->count();
            $itemsCount = $this->project->scoreItems->filter(function ($item) {
                return $item->weight != 0;
            })->count();
            $vendorsCount = $this->project->vendors()->count();
            $membersCount = $this->project->roles->count();

            if ($scoresCount < $itemsCount * $vendorsCount * $membersCount) {
                return false;
            }
        }

        // negotiation check
        if ($this->toBeFilledUpNegotiations) {
            if ($this->project->negotiations()->count() < 2) {
                return false;
            }
        }

        return true;
    }

    public function getScorePhase()
    {
        return $this->toBeScored ? ScoreHandler::getPhase($this->project) : null;
    }

    public function toBeScored()
    {
        return $this->toBeScored;
    }

    public function priceNegotiation()
    {
        return $this->toBeFilledUpNegotiations;
    }

    public function getNegotiations()
    {
        return $this->toBeFilledUpNegotiations ? $this->project->negotiations : [];
    }

    final public function canFinish()
    {
        return $this->instance->childCanFinish();
    }

    protected function instantiateNextStage()
    {
        return new Summarize($this->project);
    }

    final public function renderFunctionArea()
    {
        return view('project/display/function/record')
            ->with('title', $this->getStageName())
            ->with('renderDocType', $this->instance->getRenderDocTypes())
            ->with('uploadedTypes', $this->instance->getUploadedDocTypes())
            ->with('showFinishButton', $this->canFinish())
            ->with('project', $this->project)
            ->with('userLanID', LoginUserKeeper::getUser()->getUserInfo()->lanID)
            ->with('scorePhase', $this->instance->getScorePhase())
            ->with('priceNegotiation', $this->instance->priceNegotiation())
            ->with('negotiations', $this->instance->getNegotiations());
    }

    public function renderInfoArea()
    {
        list($a, $b, $vendorFinalScores) = ScoreHandler::getScoreDetails($this->project);
        return view('project/display/stage/record')
                ->with('toBeScore', $this->instance->toBeScored())
                ->with('vendors', $this->project->vendors)
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
        return $this->instance->operate($para);
    }
}