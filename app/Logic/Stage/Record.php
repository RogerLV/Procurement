<?php

namespace App\Logic\Stage;


use App\Models\Project;

class Record extends AbstractStage
{
    protected $stageID = STAGE_ID_RECORD;
    private $instance;

    protected $mandatoryDocTypes = [];
    protected $optionalDocTypes = [];
    protected $reviewDocTypes = [];
    protected $uploadFileTypes = [];
    protected $toBeScore = true;
    protected $toBeFiltNegotiations = false;

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
        $uploadDocTypes = $this->getUploadedDocTypes();
        $stageDocTypes = $this->project->involveReview ?
            array_merge($this->mandatoryDocTypes, $this->reviewDocTypes) :
            $this->mandatoryDocTypes;

        foreach ($stageDocTypes as $docType) {
            if (!in_array($docType->getTypeID(), $uploadDocTypes)) {
                return false;
            }
        }

        return true;
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
            ->with('showFinishButton', $this->canFinish());
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