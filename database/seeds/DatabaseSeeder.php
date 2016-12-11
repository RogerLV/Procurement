<?php

use Illuminate\Database\Seeder;
use App\Models\SystemRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $testRole = [
            ['1', 'LUC1', 'FMD'],
            ['1', 'LUC1', 'ITD'],
            ['2', 'LUC1', 'ITD'],
            ['3', 'LUC1', 'ITD'],
            ['4', 'LUC1', 'ITD'],
            ['5', 'LUC1', 'ITD'],
            ['5', 'ZLJ1', 'ITD'],
            ['6', 'LUC1', 'ITD'],
            ['6', 'ZLJ1', 'ITD'],
            ['6', 'QSG1', 'ITD'],
            ['7', 'LUC1', 'ITD'],
            ['8', 'LUC1', 'ITD'],
            ['9', 'LUC1', 'ITD'],
            ['10', 'LUC1', 'ITD'],
            ['10', 'ZLJ1', 'ITD'],
            ['10', 'QSG1', 'ITD'],
            ['11', 'LUC1', 'ITD'],
            ['11', 'QSG1', 'ITD'],
            ['9', 'QTN1', 'FMD'],
            ['9', 'WHN1', 'FMD'],
            ['1', 'CHOW', 'ITD'],
            ['1', 'ZLJ1', 'ITD'],
            ['1', 'QSG1', 'ITD'],
            ['1', 'JTA1', 'ITD'],
        ];

        foreach ($testRole as $entry){
            $role = new SystemRole();
            $role->roleID = $entry[0];
            $role->lanID = $entry[1];
            $role->dept = $entry[2];

            $role->save();
        }

        // Initialize template
        $entries = DB::connection('backup')->table('ScoreTemplate')->get();
        foreach ($entries as $entry) {
            unset($entry->id);
            \App\Models\ScoreTemplate::insert((array)$entry);
        }

        // add testing project
        \App\Models\Project::insert([
            'year' => date('Y'),
            'lanID' => 'LUC1',
            'dept' => 'ITD',
            'scope' => 'goods',
            'name' => 'Testing Project 1',
            'stage' => STAGE_ID_REVIEW,
            'background' => 'some background',
            'budget' => '80K SGD',
            'involveReview' => 1,
            'approach' => 'SingleSourcing',
        ]);

        \App\Models\Project::insert([
            'year' => date('Y'),
            'lanID' => 'ZLJ1',
            'dept' => 'ITD',
            'scope' => 'goods',
            'name' => 'Testing Project 2',
            'stage' => STAGE_ID_REVIEW,
            'background' => 'some background',
            'budget' => '80K SGD',
            'involveReview' => 1,
            'approach' => 'SingleSourcing',
        ]);

        \App\Models\Project::insert([
            'year' => date('Y'),
            'lanID' => 'QTN1',
            'dept' => 'FMD',
            'scope' => 'goods',
            'name' => 'Testing Project 3',
            'stage' => STAGE_ID_REVIEW,
            'background' => 'some background',
            'budget' => '80K SGD',
            'involveReview' => 1,
            'approach' => 'SingleSourcing',
        ]);

        \App\Models\Project::insert([
            'year' => date('Y'),
            'lanID' => 'LUC1',
            'dept' => 'ITD',
            'scope' => 'goods',
            'name' => 'Testing Project 4',
            'stage' => STAGE_ID_PASS_SIGN,
            'background' => 'some background',
            'budget' => '80K SGD',
            'involveReview' => 1,
            'approach' => 'SingleSourcing',
        ]);

        $project = \App\Models\Project::find(4);

        $log = new \App\Models\StageLog();
        $log->fromStage = STAGE_ID_PASS_SIGN;
        $log->toStage = STAGE_ID_PASS_SIGN;
        $log->data1 = 'approve';
        $log->lanID = 'LUC1';
        $project->log()->save($log);

        $log = new \App\Models\StageLog();
        $log->fromStage = STAGE_ID_PASS_SIGN;
        $log->toStage = STAGE_ID_PASS_SIGN;
        $log->data1 = 'approve';
        $log->lanID = 'ZLJ1';
        $project->log()->save($log);

        \App\Models\Project::insert([
            'year' => date('Y'),
            'lanID' => 'ZLJ1',
            'dept' => 'ITD',
            'scope' => 'goods',
            'name' => 'Testing Project 5',
            'stage' => STAGE_ID_PASS_SIGN,
            'background' => 'some background',
            'budget' => '80K SGD',
            'involveReview' => 1,
            'approach' => 'SingleSourcing',
        ]);

        $project = \App\Models\Project::find(5);

        $log = new \App\Models\StageLog();
        $log->fromStage = STAGE_ID_PASS_SIGN;
        $log->toStage = STAGE_ID_PASS_SIGN;
        $log->data1 = 'approve';
        $log->lanID = 'LUC1';
        $project->log()->save($log);

        $log = new \App\Models\StageLog();
        $log->fromStage = STAGE_ID_PASS_SIGN;
        $log->toStage = STAGE_ID_PASS_SIGN;
        $log->data1 = 'reject';
        $log->lanID = 'ZLJ1';
        $project->log()->save($log);


        \App\Models\Project::insert([
            'year' => date('Y'),
            'lanID' => 'QTN1',
            'dept' => 'FMD',
            'scope' => 'goods',
            'name' => 'Testing Project 6',
            'stage' => STAGE_ID_PASS_SIGN,
            'background' => 'some background',
            'budget' => '80K SGD',
            'involveReview' => 1,
            'approach' => 'SingleSourcing',
        ]);

        $project = \App\Models\Project::find(6);

        $log = new \App\Models\StageLog();
        $log->fromStage = STAGE_ID_PASS_SIGN;
        $log->toStage = STAGE_ID_PASS_SIGN;
        $log->data1 = 'approve';
        $log->lanID = 'LUC1';
        $project->log()->save($log);

        $log = new \App\Models\StageLog();
        $log->fromStage = STAGE_ID_PASS_SIGN;
        $log->toStage = STAGE_ID_PASS_SIGN;
        $log->data1 = 'reject';
        $log->lanID = 'ZLJ1';
        $project->log()->save($log);

        \App\Models\ProjectRole::insert([
            ['roleDeptID' => 1, 'lanID' => 'LUC1'],
            ['roleDeptID' => 1, 'lanID' => 'ZLJ1'],
            ['roleDeptID' => 1, 'lanID' => 'QSG1'],
            ['roleDeptID' => 1, 'lanID' => 'JTA1'],
        ]);

        echo "Seeding Finish.".PHP_EOL;
    }
}
