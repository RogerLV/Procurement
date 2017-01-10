<?php

namespace App\Logic;


use App\Logic\DocumentType\HyperDocument\DueDiligenceRecord;
use App\Logic\DocumentType\HyperDocument\HyperDocument;
use App\Logic\DocumentType\HyperDocument\MeetingMinutesDoc;
use App\Logic\DocumentType\HyperDocument\PassSignDoc;
use App\Logic\DocumentType\HyperDocument\PriceNegotiationDoc;
use App\Logic\DocumentType\HyperDocument\ScoreDoc;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\Stage\ProjectStages\PassSign;
use App\Models\Project;
use Gate;

class DocumentHandler
{
    public static function getHyperDocList(Project $projectIns)
    {
        $documents = $projectIns->document()->with('uploader', 'documentable')->get();
        $hyperDocAry = collect([]);
        $otherDocs = collect([]);
        $loginUser = LoginUserKeeper::getUser();
        foreach ($documents as $doc) {
            if (Gate::forUser($loginUser)->denies('document-visible', $doc)) {
                continue;
            }

            $hyperDoc = new HyperDocument($doc);

            DOC_TYPE_OTHER_DOCS == $doc->type ? $otherDocs->push($hyperDoc) : $hyperDocAry->push($hyperDoc) ;
        }

        // add pass sign hyper link
        $passSignStage = new PassSign($projectIns);
        $passSignRecords = $passSignStage->getCurrentRoundLogs()->whereIn('roleID', [
            ROLE_ID_REVIEW_COMMITTEE_MEMBER,
            ROLE_ID_REVIEW_VICE_DIRECTOR,
            ROLE_ID_REVIEW_DIRECTOR
        ]);

        if (!$passSignRecords->isEmpty()) {
            $hyperDocAry->push(new PassSignDoc($projectIns));
        }

        // add price negotiation hyper link
        if (!$projectIns->negotiations->isEmpty()) {
            $hyperDocAry->push(new PriceNegotiationDoc($projectIns));
        }

        // add score hyper link
        if (!$projectIns->scores->isEmpty()) {
            $hyperDocAry->push(new ScoreDoc($projectIns));
        }

        // add meeting minutes hyper link
        foreach ($projectIns->topics as $topicIns) {
            if ($topicIns->reviewMeeting->stage > STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE) {
                $hyperDocAry->push(new MeetingMinutesDoc($topicIns));
            }
        }

        if (Gate::forUser($loginUser)->allows('due-diligence-record-visible', $projectIns)) {
            $hyperDocAry->push(new DueDiligenceRecord($projectIns));
        }

        // sort and merge hyper doc array
        return $hyperDocAry->sortBy(function ($entry) {
            return strtotime($entry->timeAt);
        })->merge($otherDocs);
    }
}