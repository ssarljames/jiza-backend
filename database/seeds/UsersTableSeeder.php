<?php

use App\Models\Project;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $factory = Factory::create();

        $this->command->info('Seeding users...');

        DB::table('users')->insert([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'firstname' => 'Sarl James',
            'lastname' => 'Sebios',
            'email' => 'ssarljames@hotmail.com'
        ]);

        for($i=0; $i < 5; $i++)
            DB::table('users')->insert([
                'username' => 'user' . ($i + 1),
                'password' => bcrypt('password'),
                'firstname' => $factory->firstName,
                'lastname' => $factory->lastName,
                'email' => $factory->email
            ]);


        $this->command->info('Seeding projects...');

        for($i=0; $i < 2; $i++){
           Project::create([
                'title' => 'Project ' . ($i + 1),
                'description' => $factory->realText,
                'user_id' => 1
            ]);

        }

        $this->command->info('Seeding project members...');


        for($i=1; $i <= 2; $i++)
            for($j=2; $j < 5; $j++)
                DB::table('project_members')->insert([
                    'project_id' => $i,
                    'user_id' => $j
                ]);
    }
}
