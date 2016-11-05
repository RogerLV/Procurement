<?php

namespace App\Logic\Document;


use App\Models\Document;

class SignedReport extends AbstractDocument
{
    protected $type = DOC_TYPE_SIGNED_REPORT;
    protected $docName = DOC_NAME_SIGNED_REPORT;

    public function __construct($projectIns, $fileIns)
    {
        $subAddr = $projectIns->id.'/signed-report.'.$fileIns->guessExtension();

        $this->document = new Document();
        $this->document->referenceTable = 'Projects';
        $this->document->referenceID = $projectIns->id;
        $this->document->type = $this->type;
        $this->document->name = $fileIns->getClientOriginalName();
        $this->document->subAddress = $subAddr;

        $this->document->save();

        $this->moveFile($subAddr, $fileIns);
    }
}