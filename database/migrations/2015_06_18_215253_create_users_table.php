<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('user', 50)->unique();
			$table->string('password', 60);
			$table->timestamp('last_login')->nullable();
			$table->rememberToken('remember_token');
			$table->integer('doctor_id')->unsigned()->nullable();
			$table->enum('role', array('master', 'doctor', 'receptionist'));
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}