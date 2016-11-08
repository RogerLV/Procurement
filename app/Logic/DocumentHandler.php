<?php

namespace App\Logic;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Document;
use File;

class DocumentHandler
{
    public static function storeFile($fileIns, $referenceIns, $fileType)
    {
        $loginUser = LoginUserKeeper::getUser();
        $tempName = 't'.str_random(7);
        $ext = $fileIns->guessExtension();

        $document = new Document();
        $document->type = $fileType;
        $document->originalName = $fileIns->getClientOriginalName();
        $document->subAddress = $referenceIns->table.'/'.$referenceIns->id;
        $document->tempName = $tempName;
        $document->ext = $ext;
        $document->lanID = $loginUser->getUserInfo()->lanID;
        $document->mimeType = File::mimeType($fileIns);
        $referenceIns->document()->save($document);

        $path =  env('STORAGE_PATH').'/'.$document->subAddress;
        $fileIns->move($path, $tempName.'.'.$ext);

        return $document;
    }
}