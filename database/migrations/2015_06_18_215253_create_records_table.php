<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecordsTable extends Migration {

	public function up()
	{
		Schema::create('records', function(Blueprint $table) {
			$table->increments('id');
			$table->text('description');
			$table->enum('type', array('consult', 'radiography', 'operation', 'emergency', 'therapy'));
			$table->text('results');
			$table->timestamp('date_from');
			$table->timestamp('date_to');
			$table->integer('doctor_id')->unsigned();
			$table->integer('patient_id')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('records');
	}
}