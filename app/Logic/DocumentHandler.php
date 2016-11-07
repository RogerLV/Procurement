<?php

namespace App\Logic;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Document;
use App\Models\Project;
use Storage;
use DB;
use File;

class DocumentHandler
{
    public static function storeFile($fileIns, $referenceIns, $fileType)
    {
        $loginUser = LoginUserKeeper::getUser();
        $tempName = 't'.str_random(7);
        $ext = $fileIns->guessExtension();

        $path = $referenceIns->table.'/'.$referenceIns->id;

        $document = new Document();
        $document->referenceTable = $referenceIns->table;
        $document->referenceID = $referenceIns->id;
        $document->type = $fileType;
        $document->originalName = $fileIns->getClientOriginalName();
        $document->subAddress = $path;
        $document->tempName = $tempName;
        $document->ext = $ext;
        $document->lanID = $loginUser->getUserInfo()->lanID;
        $document->mimeType = File::mimeType($fileIns);
        $document->save();

        $path =  env('STORAGE_PATH').'/'.$document->referenceTable.'/'.$document->referenceID;
        $fileIns->move($path, $tempName.'.'.$ext);
    }

    public static function getByReferenceIns($referenceIns)
    {
        return Document::where('referenceTable', $referenceIns->table)
                    ->where('referenceID', $referenceIns->id)
                    ->orderBy('type')
                    ->get();
    }

    public static function getReferenceIns(Document $documentIns)
    {
        switch ($documentIns->referenceTable) {
            case 'Projects':
                return Project::find($documentIns->referenceID);

        }
    }

    public static function getFullPath(Document $documentIns)
    {
        return env('STORAGE_PATH').'/'
                .$documentIns->referenceTable.'/'
                .$documentIns->referenceID.'/'
                .$documentIns->tempName.'.'
                .$documentIns->ext;
    }
}