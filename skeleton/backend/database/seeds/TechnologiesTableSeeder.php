<?php

use Illuminate\Database\Seeder;

class TechnologiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$technologies = [
    						['name' => 'Java', 'priority' => 8, 'category_id' => 2],
    						['name' => 'SQL',  'priority' => 4, 'category_id' => 3],
    						['name' => 'PHP',  'priority' => 8, 'category_id' => 2],
    						['name' => 'JavaScript', 'priority' => 9, 'category_id' => 1],
    						['name' => 'C++',  'priority' => 5, 'category_id' => 2],
    						['name' => 'NoSQL',  'priority' => 6, 'category_id' => 3],
    						['name' => 'NodeJS',  'priority' => 6, 'category_id' => 2],
    						['name' => 'Ruby',  'priority' => 5, 'category_id' => 2],
    						['name' => 'Python',  'priority' => 4, 'category_id' => 2],
    						['name' => 'MySql',  'priority' => 7, 'category_id' => 3]
    					];
    	$query = "INSERT INTO technologies(name, priority, category_id) VALUES(:name, :priority, :category_id)";

    	foreach ($technologies as $key => $value) {
            DB::insert($query, ['name' => $value['name'], 'priority' => $value['priority'], 'category_id' => $value['category_id']]);
    	}
    }
}
