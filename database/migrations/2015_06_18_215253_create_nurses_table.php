<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNursesTable extends Migration {

	public function up()
	{
		Schema::create('nurses', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->date('birth_date');
			$table->string('id_number', 15);
			$table->enum('shift', array('diurn', 'nocturne'));
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('nurses');
	}
}