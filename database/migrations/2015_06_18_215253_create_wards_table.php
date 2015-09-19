<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWardsTable extends Migration {

	public function up()
	{
		Schema::create('wards', function(Blueprint $table) {
			$table->timestamps();
			$table->increments('id');
			$table->smallInteger('capacity')->default('2');
		});
	}

	public function down()
	{
		Schema::drop('wards');
	}
}