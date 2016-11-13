<?php

namespace App\Logic\DocumentType;


abstract class AbstractDocumentType
{
    protected $cnName;
    protected $engName;
    protected $typeID;

    public function getCnName()
    {
        return $this->cnName;
    }

    public function getEngName()
    {
        return $this->engName;
    }

    public function getTypeID()
    {
        return $this->typeID;
    }
}