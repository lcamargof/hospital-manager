<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDoctorsTable extends Migration {

	public function up()
	{
		Schema::create('doctors', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->date('birth_date');
			$table->string('id_number', 15);
			$table->string('specialization', 60);
			$table->boolean('active')->default(1);
			$table->time('time_in');
			$table->time('time_out');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('doctors');
	}
}