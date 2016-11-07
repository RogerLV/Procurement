<?php

namespace App\Http\Controllers;


use App\Exceptions\AppException;
use App\Logic\DocumentHandler;
use App\Models\Document;
use App\Models\Project;
use Gate;
use File;
use Storage;
use Config;

class DocumentController extends Controller
{
    public function display($id, $name)
    {
        $documentIns = Document::find($id);
        if (is_null($documentIns)) {
            throw new AppException('DOC001', 'Incorrect Document ID.');
        }

        // Check Visibility
        if (Gate::forUser($this->loginUser)->denies('document-visible', $documentIns)) {
            throw new AppException('DOC002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $fullPath = DocumentHandler::getFullPath($documentIns);

        return response()->file($fullPath);
    }

    public function download($id)
    {

    }

    public function upload()
    {
        if (empty($reference = request()->input('reference'))
            || empty($referenceID = request()->input('id'))
            || empty($fileType = request()->input('filetype'))
            || empty ($fileIns = request()->file('upload-doc'))) {
            throw new AppException('DOC003', 'Data Error');
        }

        switch ($reference) {
            case 'Projects':
                $referenceIns = Project::find($referenceID);
                if (Gate::forUser($this->loginUser)->denies('project-visible', $referenceIns)) {
                    throw new AppException('DOC004', ERROR_MESSAGE_NOT_AUTHORIZED);
                }
                break;
        }

        if (is_null($referenceIns) || !array_key_exists($fileType, Config::get('constants.documentTypeNames'))) {
            throw new AppException('DOC005', 'Incorrect Doc Info.');
        }

        $documentIns = DocumentHandler::storeFile($fileIns, $referenceIns, $fileType);

        return response()->json([
            'status' => 'good',
            'info' => [
                'fileType' => Config::get('constants.documentTypeNames.'.$fileType),
                'documentIns' => $documentIns,
                'userInfo' => $this->loginUser->getUserInfo(),
            ],
        ]);
    }

}
