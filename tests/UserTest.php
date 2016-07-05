<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase {
	use DatabaseTransactions;

	// Login the reception user and do the actions
	public function testUserActions() {
		// Login as receptionist
		$this->visit('/')
			->type('reception', 'user')
			->type('test', 'password')
			->press('Login')
			->seePageIs('/home')
			->assertTrue(Auth::check() && Auth::user()->user == 'reception' && Auth::user()->role == 'receptionist');

		// Disabling filters
		$this->withoutMiddleware();

		// Patients operations
		$this->patientScrub();

		// Beds operations
		$this->recordScrub();
	}

	// Patients operations
	public function patientScrub() {
		// Add a patient
		$this->post('/patients', [
		   'name' => 'Patient Test',
		   'id_number' => '5365478',
		   'birth_date' => '1995-05-05',
		   'gender' => 'male',
		   'blood_type' => 'O-',
		   'phone' => '26516416',
		   'address' => 'example address'
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'success',
		])->seeInDatabase('patients', [
		   'id_number' => '5365478'
		]);

		// Patient route
		$route = '/patients'.'/'.App\Patient::where('id_number', '5365478')->get()->first()->id;

		// Edit a patient
		$this->put($route, [
		   'name' => 'Patient Test',
		   'id_number' => '5365478',
		   'birth_date' => '1995-05-05',
		   'gender' => 'male',
		   'blood_type' => 'A+',
		   'phone' => '3151515',
		   'address' => 'example address'
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'success',
		])->seeInDatabase('patients', [
		   'id_number' => '5365478'
		]);

		// Delete a patient
		$this->delete($route, [
		   'type' => 'doctor'
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'success'
		])->notSeeInDatabase('patients', [
		   'id_number' => '5365478'
		]);
	}

	// Records operations
	public function recordScrub() {
		// Enter as master
		$this->visit('/logout')
			->seePageIs('/')
			->type('master', 'user')
			->type('test', 'password')
			->press('Login')
			->seePageIs('/home');

		// Add a doctor
		$this->post('/staff', [
		   'type' => 'doctor',
		   'name' => 'Doctor Test',
		   'id_number' => '16156855',
		   'birth_date' => '1980-05-05',
		   'specialization' => 'Unit testing',
		   'time_in' => '08:00:00',
		   'time_out' => '18:00:00'
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'success',
		])->seeInDatabase('doctors', [
		   'id_number' => '16156855'
		]);

		$this->visit('/logout')
			->seePageIs('/')
			->type('reception', 'user')
			->type('test', 'password')
			->press('Login')
			->seePageIs('/home');

		// Add a patient
		$this->post('/patients', [
		   'name' => 'Patient Test',
		   'id_number' => '5165155',
		   'birth_date' => '1995-05-05',
		   'gender' => 'male',
		   'blood_type' => 'O-',
		   'phone' => '26516416',
		   'address' => 'example address'
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'success',
		])->seeInDatabase('patients', [
		   'id_number' => '5165155'
		]);

		$doctor_id = App\Doctor::where('id_number', '16156855')->get()->first()->id;

		$patient_id = App\Patient::where('id_number', '5165155')->get()->first()->id;

		// Add a record
		$this->post('/records', [
		   'description' => 'Test record',
		   'type' => 'consult',
		   'date_to' => '2015-10-10 10:00:00',
		   'doctor_id' => $doctor_id,
		   'patient_id' => $patient_id
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'success',
		])->seeInDatabase('records', [
		   'date_to' => '2015-10-10 10:00:00',
		   'doctor_id' => $doctor_id,
		   'patient_id' => $patient_id
		]);

		$record_id = App\Record::where('doctor_id', $doctor_id)->where('date_to', '2015-10-10 10:00:00')->where('patient_id', $patient_id)->get()->first()->id;

		// Edit a patient
		$this->put('records'.'/'.$record_id, [
		   'description' => 'Test record',
		   'type' => 'consult',
		   'date_to' => '2015-10-10 11:00:00',
		   'doctor_id' => $doctor_id,
		   'patient_id' => $patient_id
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'success',
		])->seeInDatabase('records', [
		   'date_to' => '2015-10-10 11:00:00',
		   'doctor_id' => $doctor_id,
		   'patient_id' => $patient_id
		]);

		// Add a record
		$this->post('/transactions', [
		   'amount' => 1000,
		   'method' => 'cash',
		   'record_id' => $record_id
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'success',
		])->seeInDatabase('transactions', [
		   'record_id' => $record_id
		]);

		// Delete a patient
		$this->delete('records'.'/'.$record_id, [
		   'test' => 'test'
		], [
		   'HTTP_X-Requested-With' => 'XMLHttpRequest'
		])->seeJson([
		   'result' => 'error'
		]);
	}
}

?>
