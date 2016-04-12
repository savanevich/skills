<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = [
    						['username' => 'demcore', 'first_name' => 'Petya', 'second_name' => 'Petrov',
							 'email' => 'demcore@aa.a', 'password' => Hash::make('secret')],
    						['username' => 'nano', 'first_name' => 'Petya', 'second_name' => 'Petrov',
							 'email' => 'nano@aa.a', 'password' => Hash::make('secret')],
    						['username' => 'german', 'first_name' => 'Petya', 'second_name' => 'Petrov',
							 'email' => 'german@aa.a', 'password' => Hash::make('secret')],
    						['username' => 'Torvalds', 'first_name' => 'Petya', 'second_name' => 'Petrov',
							 'email' => 'Torvalds@aa.a', 'password' => Hash::make('secret')],
    						['username' => 'Tanenbaum', 'first_name' => 'Petya', 'second_name' => 'Petrov',
							 'email' => 'Tanenbaum@aa.a', 'password' => Hash::make('Secret1')],
    						['username' => 'Zandstra', 'first_name' => 'Petya', 'second_name' => 'Petrov',
							 'email' => 'Zandstra@aa.a', 'password' => Hash::make('secret')],
                            ['username' => 'john', 'first_name' => 'John', 'second_name' => 'McClain',
                                'email' => 'john.mcain@ny.com', 'password' => Hash::make('secret')],
                            ['username' => 'alyssa', 'first_name' => 'Alyssa', 'second_name' => 'Milano',
                                'email' => 'alysa@milano.com', 'password' => Hash::make('secret')],
                            ['username' => 'benny', 'first_name' => 'Ben', 'second_name' => 'Afflek',
                                'email' => 'ben_afflek@gmail.com', 'password' => Hash::make('secret')],
                            ['username' => 'brittany', 'first_name' => 'Brittany', 'second_name' => 'Robertson',
                                'email' => 'brittany@gmail.com', 'password' => Hash::make('secret')],
                            ['username' => 'aniston', 'first_name' => 'Jen', 'second_name' => 'Aniston',
                                 'email' => 'jen_aniston@gmail.com', 'password' => Hash::make('secret')],
    					    ];
      $query = "INSERT INTO users(username, first_name, second_name, email, password) 
                VALUES(:username, :first_name, :second_name, :email, :password)";

    	foreach ($users as $key => $value) {
            DB::insert($query, [
                'username' => $value['username'],
                'first_name' => $value['first_name'],
                'second_name' => $value['second_name'],
                'email' => $value['email'], 
                'password' => $value['password']
            ]);
    	}
    }
}
