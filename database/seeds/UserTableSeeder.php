<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();

		DB::table('users')->insert([
			[
				'user' => 'master',
				'password' => bcrypt('test'),
				'role' => 'master'
			],
			[
				'user' => 'reception',
				'password' => bcrypt('test'),
				'role' => 'receptionist'        		
			]
		]);
	}
}