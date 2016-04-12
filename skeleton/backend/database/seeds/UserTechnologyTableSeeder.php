<?php

use Illuminate\Database\Seeder;

class UserTechnologyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$skills = [
    						['user_id' => 1, 'technology_id' => 1, 'level' => 2],
    						['user_id' => 1, 'technology_id' => 2, 'level' => 3],
    						['user_id' => 1, 'technology_id' => 3, 'level' => 4],
    						['user_id' => 1, 'technology_id' => 4, 'level' => 5],
    						['user_id' => 1, 'technology_id' => 5, 'level' => 6],
    						['user_id' => 2, 'technology_id' => 2, 'level' => 5],
    						['user_id' => 2, 'technology_id' => 1, 'level' => 7],
    						['user_id' => 5, 'technology_id' => 3, 'level' => 5],
    						['user_id' => 5, 'technology_id' => 4, 'level' => 4],
    						['user_id' => 2, 'technology_id' => 7, 'level' => 2],
    						['user_id' => 3, 'technology_id' => 8, 'level' => 2],
    						['user_id' => 4, 'technology_id' => 1, 'level' => 4],
    						['user_id' => 3, 'technology_id' => 2, 'level' => 9],
    						['user_id' => 3, 'technology_id' => 3, 'level' => 8],
    						['user_id' => 6, 'technology_id' => 7, 'level' => 8],
    						['user_id' => 4, 'technology_id' => 2, 'level' => 5],
    						['user_id' => 6, 'technology_id' => 9, 'level' => 4],
    						['user_id' => 7, 'technology_id' => 2, 'level' => 4],
    						['user_id' => 8, 'technology_id' => 3, 'level' => 4],
    						['user_id' => 9, 'technology_id' => 4, 'level' => 4],
    						['user_id' => 7, 'technology_id' => 5, 'level' => 4],
    						['user_id' => 8, 'technology_id' => 6, 'level' => 4],
    						['user_id' => 9, 'technology_id' => 7, 'level' => 4],
    						['user_id' => 10, 'technology_id' => 8, 'level' => 4],
    						['user_id' => 11, 'technology_id' => 9, 'level' => 4],

    				];
    	$query = "INSERT INTO user_technology(user_id, technology_id, level) VALUES(:user_id, :technology_id, :level)";

    	foreach ($skills as $key => $value) {
            DB::insert($query, ['user_id' => $value['user_id'], 'technology_id' => $value['technology_id'], 'level' => $value['level']]);
    	}
    }
}
