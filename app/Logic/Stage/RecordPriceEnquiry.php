<?php

namespace App\Logic\Stage;


use App\Models\Project;

class RecordPriceEnquiry extends Record
{
    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
    }

    public function renderFunctionArea()
    {
        $uploadedTypes = $this->project->document()->whereIn('type', [
            DOC_TYPE_PROJECT_INQUIRY,
            DOC_TYPE_REVIEW_REPORT
        ])->get()->pluck('type')->unique()->toArray();

        return view('project/display/function/recordpriceenquiry')
            ->with('title', $this->getStageName())
            ->with('uploadReviewReport', $this->project->involveReview)
            ->with('uploadedTypes', $uploadedTypes)
            ->with('showFinishButton', ($this->project->involveReview ? 2 : 1) == count($uploadedTypes));
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