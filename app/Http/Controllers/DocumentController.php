<?php

namespace App\Http\Controllers;


use App\Logic\DocumentHandler;
use App\Models\Document;
use Gate;
use File;
use Storage;

class DocumentController extends Controller
{
    public function display($id)
    {
        $documentIns = Document::find($id);
        if (is_null($documentIns)) {
            throw new AppException('DOC001', 'Incorrect Document ID.');
        }

        // Check Visibility
        $referenceIns = DocumentHandler::getReferenceIns($documentIns);

        if (Gate::forUser($this->loginUser)->denies('project-visible', $referenceIns)) {
            throw new AppException('DOC002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $fullPath = DocumentHandler::getFullPath($documentIns);
        return response()->file($fullPath);
    }

    public function download($id)
    {

    }
}
