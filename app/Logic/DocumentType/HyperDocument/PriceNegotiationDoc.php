<?php

namespace App\Logic\DocumentType\HyperDocument;


use App\Models\Project;

class PriceNegotiationDoc extends HyperDocument
{
    protected $type = HYPER_DOC_NAME_PRICE_NEGOTIATION;
    protected $name = HYPER_DOC_NAME_PRICE_NEGOTIATION;

    public function __construct(Project $projectIns)
    {
        $this->url = url('negotiation/show/'.$projectIns->id);

        $lastNegotiation = $projectIns->negotiations->sortByDesc('id')->first();
        $this->uploader = $lastNegotiation->user->getTriName();
        $this->timeAt = $lastNegotiation->created_at;
    }
}