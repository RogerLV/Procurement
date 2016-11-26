<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\ScoreHandler;

trait TRecordChildMethods
{
    protected $mandatoryDocTypes = [];
    protected $optionalDocTypes = [];
    protected $reviewDocTypes = [];
    protected $uploadFileTypes = [];
    protected $toBeScored = true;
    protected $toBeFilledUpNegotiations = false;

    public function getScorePhase()
    {
        return $this->toBeScored ? ScoreHandler::getPhase($this->referrer) : null;
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
        return $this->toBeFilledUpNegotiations ? $this->referrer->negotiations : [];
    }

    public function getRenderDocTypes()
    {
        if ($this->referrer->involveReview) {
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

            $this->uploadFileTypes = $this->referrer->document()
                ->whereIn('type', $docTypeIDs)->get()
                ->pluck('type')->unique()->toArray();
        }

        return $this->uploadFileTypes;
    }
    public function canStageUp()
    {
        // user role check
        if (LoginUserKeeper::getUser()->getUserInfo()->lanID != $this->referrer->lanID) {
            return false;
        }

        // uploaded docs check
        $uploadDocTypes = $this->getUploadedDocTypes();
        $stageDocTypes = $this->referrer->involveReview ?
            array_merge($this->mandatoryDocTypes, $this->reviewDocTypes) :
            $this->mandatoryDocTypes;

        foreach ($stageDocTypes as $docType) {
            if (!in_array($docType->getTypeID(), $uploadDocTypes)) {
                return false;
            }
        }

        // to be scored check
        if ($this->toBeScored) {
            $scoresCount = $this->referrer->scores->count();
            $itemsCount = $this->referrer->scoreItems->filter(function ($item) {
                return $item->weight != 0;
            })->count();
            $vendorsCount = $this->referrer->vendors()->count();
            $membersCount = $this->referrer->roles->count();

            if ($scoresCount < $itemsCount * $vendorsCount * $membersCount) {
                return false;
            }
        }

        // negotiation check
        if ($this->toBeFilledUpNegotiations) {
            if ($this->referrer->negotiations()->count() < 2) {
                return false;
            }
        }

        return true;
    }

    public function operate($para = null)
    {
        $this->logOperation();
    }

}