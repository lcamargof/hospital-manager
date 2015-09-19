<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBedsTable extends Migration {

	public function up()
	{
		Schema::create('beds', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('patient_id')->unsigned();
			$table->integer('ward_id')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('beds');
	}
}