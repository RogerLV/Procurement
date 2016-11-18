<?php

namespace App\Http\Controllers;


use App\Exceptions\AppException;
use App\Models\DueDiligence;

class DueDiligenceController extends StageController
{
    public function addRequest()
    {
        if (empty($request = trim(request()->input('request')))) {
            throw new AppException('DDCTL001');
        }

        if (ROLE_ID_DUE_DILIGENCE_MEMBER != $this->loginUser->getActiveRole()->roleID) {
            throw new AppException('DDCTL002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $requestIns = new DueDiligence();
        $requestIns->request = $request;
        $this->projectIns->duediligence()->save($requestIns);

        return response()->json([
            'status' => 'good',
            'info' => $requestIns,
        ]);
    }

    public function removeRequest()
    {
        if (empty($requestID = trim(request()->input('requestid')))) {
            throw new AppException('DDCTL003');
        }

        if (ROLE_ID_DUE_DILIGENCE_MEMBER != $this->loginUser->getActiveRole()->roleID) {
            throw new AppException('DDCTL003', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $request = DueDiligence::getIns($requestID);

        if (!is_null($request->answer)) {
            throw new AppException('DDCTL004', 'Answered request could not be removed.');
        }

        $request->delete();

        return response()->json(['status' => 'good']);
    }

    public function answer()
    {
        if (empty($requestID = trim(request()->input('requestid')))
            || empty($answer = trim(request()->input('answer')))) {
            throw new AppException('DDCTL005');
        }

        if (ROLE_ID_DEPT_MAKER != $this->loginUser->getActiveRole()->roleID) {
            throw new AppException('DDCTL006', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $request = DueDiligence::getIns($requestID);

        if (!is_null($request->answer)) {
            throw new AppException('DDCTL007', 'Request is already answered');
        }

        $request->answer = $answer;
        $request->save();

        return response()->json(['status' => 'good']);
    }
}
