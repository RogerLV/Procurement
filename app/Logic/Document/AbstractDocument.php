<?php

namespace App\Logic\Document;


use Storage;

abstract class AbstractDocument
{
    protected $type;
    protected $docName;
    protected $document;

    protected function moveFile($subAddr, $fileIns)
    {
        Storage::put($subAddr, $fileIns);
    }
}