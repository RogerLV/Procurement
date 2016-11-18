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
            'name' => 'Testing Project',
            'stage' => STAGE_ID_DUE_DILIGENCE,
            'background' => 'some background',
            'budget' => '80K SGD',
            'involveReview' => 1,
            'memberAmount' => 4,
            'approach' => 'SingleSourcing',
        ]);

        \App\Models\ProjectRoleDepartment::insert([
            'projectID' => 1,
            'dept' => 'ITD',
        ]);

        \App\Models\ProjectRole::insert([
            ['roleDeptID' => 1, 'lanID' => 'LUC1'],
            ['roleDeptID' => 1, 'lanID' => 'ZLJ1'],
            ['roleDeptID' => 1, 'lanID' => 'QSG1'],
            ['roleDeptID' => 1, 'lanID' => 'JTA1'],
        ]);

        echo "Seeding Finish.".PHP_EOL;
    }
}
