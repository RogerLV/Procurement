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

    public static function getScoreDetails(Project $project)
    {
        $scoreItems = $project->scoreItems->keyBy('id');
        $scoreMatrix = [];
        $sumScoreMatrix = [];
        foreach ($project->scores as $detail) {
            $scoreMatrix[$detail->vendorID][$detail->itemID][$detail->lanID] = $detail->score;

            $weighted = $detail->score * $scoreItems->get($detail->itemID)->weight / 100;

            if (isset($sumScoreMatrix[$detail->vendorID][$detail->lanID])) {
                $sumScoreMatrix[$detail->vendorID][$detail->lanID] += $weighted;
            } else {
                $sumScoreMatrix[$detail->vendorID][$detail->lanID] = $weighted;
            }
        }

        $finalScores = [];
        foreach ($sumScoreMatrix as $vendorID => $vendorScores) {
            $finalScores[$vendorID] = array_sum($vendorScores) / count($vendorScores);
        }

        return [$scoreMatrix, $sumScoreMatrix, $finalScores];
    }
}