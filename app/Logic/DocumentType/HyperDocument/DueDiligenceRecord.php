<?php

namespace App\Logic\DocumentType\HyperDocument;


use App\Models\Project;

class DueDiligenceRecord extends HyperDocument
{
    protected $type = HYPER_DOC_NAME_DUE_DILIGENCE_RECORD;
    protected $name = HYPER_DOC_NAME_DUE_DILIGENCE_RECORD;

    public function __construct(Project $projectIns)
    {
        $this->url = url('hyper/doc/due/diligence/'.$projectIns->id);
        $this->timeAt = $projectIns->duediligence->sortByDesc('id')->first()->updated_at;
    }
}