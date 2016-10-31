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
            ['2', 'luc1', 'ITD'],
            ['3', 'luc1', 'ITD'],
            ['4', 'luc1', 'ITD'],
            ['5', 'luc1', 'ITD'],
            ['6', 'luc1', 'ITD'],
            ['7', 'luc1', 'ITD'],
            ['8', 'luc1', 'ITD'],
            ['9', 'luc1', 'ITD'],
            ['10', 'luc1', 'ITD'],
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
