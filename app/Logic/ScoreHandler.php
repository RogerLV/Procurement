<?php

namespace App\Logic;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Project;

class ScoreHandler
{
    public static function getPhase(Project $project)
    {
        $items = $project->scoreItems()->get();
        if ($items->isEmpty()) {
            return 'ScoreStageEditTemplate';
        }

        $userLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $itemCount = $items->filter(function ($item) {
            return $item->weight != 0;
        })->count();
        $vendorCount = $project->vendors()->count();
        $scoresCount = $project->scores()->where('lanID', $userLanID)->count();

        if ($scoresCount < $itemCount * $vendorCount) {
            return 'ScoreStageMemberScoring';
        }

        return 'ScoreStageMemberScoreComplete';
    }
}