<?php

namespace App\Logic\DocumentType\HyperDocument;


use App\Models\Project;

class PassSignDoc extends HyperDocument
{
    protected $type = HYPER_DOC_NAME_PASS_SIGN;
    protected $name = HYPER_DOC_NAME_PASS_SIGN;

    public function __construct(Project $projectIns)
    {
        $this->url = url('pass/sign/show/in/page/'.$projectIns->id);
        $this->uploader = STRING_REVIEW_MEETING_MEMBERS;

        // get last sign time point

        $this->timeAt = $projectIns->log
            ->whereLoose('fromStage', STAGE_ID_PASS_SIGN)
            ->sortByDesc('id')
            ->first()
            ->timeAt;
    }
}