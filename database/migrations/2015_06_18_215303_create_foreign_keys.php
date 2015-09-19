<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('beds', function(Blueprint $table) {
			$table->foreign('ward_id')->references('id')->on('wards')
						->onDelete('no action')
						->onUpdate('cascade');
		});
		Schema::table('transactions', function(Blueprint $table) {
			$table->foreign('record_id')->references('id')->on('records')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('records', function(Blueprint $table) {
			$table->foreign('doctor_id')->references('id')->on('doctors')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('records', function(Blueprint $table) {
			$table->foreign('patient_id')->references('id')->on('patients')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('users', function(Blueprint $table) {
			$table->foreign('doctor_id')->references('id')->on('doctors')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('beds', function(Blueprint $table) {
			$table->dropForeign('beds_ward_id_foreign');
		});
		Schema::table('transactions', function(Blueprint $table) {
			$table->dropForeign('transactions_record_id_foreign');
		});
		Schema::table('records', function(Blueprint $table) {
			$table->dropForeign('records_doctor_id_foreign');
		});
		Schema::table('records', function(Blueprint $table) {
			$table->dropForeign('records_patient_id_foreign');
		});
		Schema::table('users', function(Blueprint $table) {
			$table->dropForeign('users_doctor_id_foreign');
		});
	}
}