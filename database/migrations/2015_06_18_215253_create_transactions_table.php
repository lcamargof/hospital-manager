<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration {

	public function up()
	{
		Schema::create('transactions', function(Blueprint $table) {
			$table->increments('id');
			$table->boolean('payed')->default(0);
			$table->decimal('amount');
			$table->enum('method', array('debit', 'credit'));
			$table->integer('record_id')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('transactions');
	}
}