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
            ['6', 'LUC1', 'ITD'],
            ['7', 'LUC1', 'ITD'],
            ['8', 'LUC1', 'ITD'],
            ['9', 'LUC1', 'ITD'],
            ['10', 'LUC1', 'ITD'],
            ['10', 'ZLJ1', 'ITD'],
            ['10', 'QSG1', 'ITD'],
            ['9', 'QTN1', 'FMD'],
            ['9', 'WHN1', 'FMD'],
            ['1', 'CHOW', 'ITD'],
        ];

        foreach ($testRole as $entry){
            $role = new SystemRole();
            $role->roleID = $entry[0];
            $role->lanID = $entry[1];
            $role->dept = $entry[2];

            $role->save();
        }

        echo "Seeding Finish.".PHP_EOL;
    }
}
