<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Frontend'],
            ['name' => 'Backend'],
            ['name' => 'Databases']
        ];
        $query = "INSERT INTO categories(name) VALUES(:name)";

        foreach ($categories as $key => $value) {
            DB::insert($query, ['name' => $value['name']]);
        }
    }
}
