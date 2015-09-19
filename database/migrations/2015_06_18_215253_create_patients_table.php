<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientsTable extends Migration {

	public function up()
	{
		Schema::create('patients', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->date('birth_date');
			$table->enum('sex', array('male', 'female'));
			$table->string('id_number', 15);
			$table->string('phone', 20);
			$table->text('address');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('patients');
	}
}