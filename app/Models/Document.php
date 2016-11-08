<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Logic\LoginUser\LoginUserKeeper;
use File;

class Document extends Model
{
    public $table = 'Documents';

    public function documentable()
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'lanID');
    }

    public function getFullPath()
    {
        return env('STORAGE_PATH').'/'
            .$this->subAddress.'/'
            .$this->tempName.'.'
            .$this->ext;
    }

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
