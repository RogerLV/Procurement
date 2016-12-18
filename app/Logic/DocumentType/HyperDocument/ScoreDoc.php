<?php

namespace App\Logic\DocumentType\HyperDocument;


use App\Models\Project;

class ScoreDoc extends HyperDocument
{
    protected $type = HYPER_DOC_NAME_SCORE;
    protected $name = HYPER_DOC_NAME_SCORE;

    public function __construct(Project $projectIns)
    {
        $this->url = url('score/overview/'.$projectIns->id);
        $this->uploader = STRING_PROCUREMENT_TEAM;

        // set the last score entry created time as doc time
        $lastEntry = $projectIns->scores()->orderBy('id', 'desc')->first();
        $this->timeAt = $lastEntry->created_at;
    }
}