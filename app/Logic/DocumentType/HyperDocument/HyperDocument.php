<?php

namespace App\Logic\DocumentType\HyperDocument;


use App\Models\Document;
use Config;

class HyperDocument
{
    protected $type;
    protected $name;
    protected $url;
    protected $uploader;
    protected $timeAt;

    public function __construct(Document $docIns)
    {
        $docTypeNames = Config::get('constants.documentTypeNames');
        $this->type = $docTypeNames[$docIns->type];
        $this->name = $docIns->originalName;
        $this->url = $docIns->getUrl();
        $this->uploader = $docIns->uploader->getTriName();
        $this->timeAt = $docIns->created_at->format('Y-m-d H:i:s');
    }

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
    }
}