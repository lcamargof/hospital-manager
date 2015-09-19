<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model {

	protected $table = 'doctors';
	public $timestamps = true;
	protected $fillable = array('name', 'birth_date', 'id_number', 'specialization', 'time_in', 'time_out');

	public function patientsRecords()
	{
		return $this->belongsToMany('App\Patient', 'records')->groupBy('id')->with('records');
	}

	public function patients() {
		return $this->belongsToMany('App\Patient', 'records')->groupBy('id');
	}

	public function records()
	{
		return $this->hasMany('App\Record')->with('patient')->with('transaction');
	}

	public function user() {
		return $this->hasOne('App\User');
	}

}