<?php

namespace App\Http\Controllers;


use App\Exceptions\AppException;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\ScoreHandler;
use App\Models\Project;
use App\Models\Score;
use App\Models\ScoreItem;
use App\Models\ScoreTemplate;
use App\Models\Vendor;
use Gate;

class ScoreController extends Controller
{
    public function editTemplate($projectID)
    {
        $project = Project::getIns($projectID);

        if (Gate::forUser($this->loginUser)->denies('score-template-editable', $project)) {
            throw new AppException('SCRCTL001', 'Cannot edit template at current phase.');
        }

        $options = ScoreTemplate::where('type', $project->scope)->distinct()->get(['name', 'nameID']);
        $vendorOptions = Vendor::where('type', $project->scope)->get()->pluck('name');

        return view('score.edittemplate')
                ->with('title', PAGE_NAME_SCORE_EDIT_TEMPLATE.": ".$project->name)
                ->with('options', $options)
                ->with('scoreTemplates', ScoreTemplate::where([
                    ['type', '=', $project->scope],
                    ['nameID', '=', 1]
                ])->get())
                ->with('project', $project)
                ->with('vendors', $project->vendors)
                ->with('vendorOptions', $vendorOptions->toJson());
    }

    public function selectTemplate()
    {
        if (empty($nameID = request()->input('nameid'))
            || empty($scope = request()->input('scope'))) {
            throw new AppException('SCRCTL002', 'Data Error.');
        }

        return response()->json([
            'status' => 'good',
            'info' => ScoreTemplate::where([
                ['nameID', '=', $nameID],
                ['type', '=', $scope]
                ])->get(),
        ]);
    }

    public function commitItems()
    {
        if (empty($projectID = trim(request()->input('projectid')))
            || empty($items = request()->input('items'))) {
            throw new AppException('SCRCTL003');
        }

        $project = Project::getIns($projectID);

        if (Gate::forUser($this->loginUser)->denies('score-template-editable', $project)) {
            throw new AppException('SCRCTL004', 'Cannot edit template at current phase.');
        }

        // check if vendor is already assigned
        if (0 == $project->vendors()->count()) {
            throw new AppException('SCRCTL005', 'Vendors have to be assigned.');
        }

        foreach ($items as $item) {
            $entry = new ScoreItem();
            $entry->item = $item['item'];
            $entry->content = nl2br(htmlentities($item['content']));
            $entry->weight = $item['weight'];
            $entry->comment = $item['comment'];
            $project->scoreItems()->save($entry);
        }

        return response()->json(['status' => 'good']);
    }

    public function scorePage($projectID)
    {
        $project = Project::getIns($projectID);

        if (Gate::forUser($this->loginUser)->denies('scorable', $project)) {
            throw new AppException('SCRCTL006', 'Cannot score at current phase.');
        }

        return view('score/scorepage')
                ->with('title', PAGE_NAME_SCORE_PAGE.": ".$project->name)
                ->with('entries', $project->scoreItems)
                ->with('vendors', $project->vendors)
                ->with('project', $project);
    }

    public function submitScore()
    {
        if (empty($projectID = trim(request()->input('projectid')))
            || empty($scoreDetails = request()->input('scoredetails'))) {
            throw new AppException('SCRCTL007');
        }

        $project = Project::getIns($projectID);

        if (Gate::forUser($this->loginUser)->denies('scorable', $project)) {
            throw new AppException('SCRCTL008', 'Cannot score at current phase.');
        }

        $userLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        foreach ($scoreDetails as $entry) {
            $score = new Score();
            $score->vendorID = $entry['vendorid'];
            $score->itemID = $entry['itemid'];
            $score->lanID = $userLanID;
            $score->score = $entry['score'];
            $score->save();
        }

        return response()->json(['status' => 'good']);
    }

    public function overview($projectID)
    {
        $project = Project::getIns($projectID);

        if (Gate::forUser($this->loginUser)->denies('project-visible', $project)) {
            throw new AppException('SCRCTL009', 'Cannot score at current phase.');
        }

        list($scoreMatrix, $sumScoreMatrix, $finalScores) = ScoreHandler::getScoreDetails($project);

        return view('score/overview')
                ->with('title', PAGE_NAME_SCORE_OVERVIEW.": ".$project->name)
                ->with('scoreMatrix', $scoreMatrix)
                ->with('vendors', $project->vendors)
                ->with('scoreItems', $project->scoreItems->keyBy('id'))
                ->with('members', $project->roles()->with('user')->get())
                ->with('sumScoreMatrix', $sumScoreMatrix)
                ->with('finalScores', $finalScores);
    }
}
