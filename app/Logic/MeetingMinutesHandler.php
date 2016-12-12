<?php

namespace App\Logic;


class MeetingMinutesHandler
{
    public static function renderReviewMeeting ($reviewIns, $print = false)
    {
        $topics = $reviewIns->topics()->with('topicable', 'meetingMinutesContent')->get();
        $printUrl = url('meetingMinutes/reviewMeeting/'.$reviewIns->id);
        return self::render($reviewIns, $topics, $printUrl, $print);
    }

    public static function renderTopic ($topicIns, $print = false)
    {
        // pre load related models from outside
        $topics = collect([$topicIns]);
        $printUrl = url('meetingMinutes/topic/'.$topicIns->id);
        return self::render($topicIns->reviewMeeting, $topics, $printUrl, $print);
    }


    private static function render($reviewIns, $topics, $printUrl, $print)
    {
        $participants = $reviewIns->participants()->with('user.department')->get();

        $committeeMembers = $participants->whereLoose('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER);
        $committeeMemberNames = [];
        foreach ($committeeMembers as $member) {
            $committeeMemberNames[] = $member->user->getDualName();
        }

        $specialInvitees = $participants->whereLoose('roleID', ROLE_ID_SPECIAL_INVITE);
        $specialInviteeNames = [];
        foreach ($specialInvitees as $entry) {
            $specialInviteeNames[] = $entry->user->getDualName()." (".$entry->user->department->deptCnName.")";
        }

        $reviewLog = $reviewIns->log->first(function ($key, $ins) {
            return $ins->fromStage == STAGE_ID_REVIEW_MEETING_SECRETARIAT_LEADER_APPROVE
            && $ins->toStage == STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE;
        });

        $releaseLog = $reviewIns->log->first(function ($key, $ins) {
            return $ins->fromStage == STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE
            && $ins->toStage > STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE;
        });

        $view = $print ? 'meetingminutes.printpage' : 'meetingminutes.infosection';

        return view($view)
            ->with('reviewIns', $reviewIns)
            ->with('metaInfo', $reviewIns->metaInfo)
            ->with('topics', $topics)
            ->with('memberNames', $committeeMemberNames)
            ->with('inviteeNames', $specialInviteeNames)
            ->with('reviewLog', $reviewLog)
            ->with('releaseLog', $releaseLog)
            ->with('printUrl', $printUrl);
    }
}