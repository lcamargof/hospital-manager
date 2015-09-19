<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model {
	protected $table = 'records';
	public $timestamps = true;
	protected $fillable = array('description', 'type', 'type_other', 'date_to', 'doctor_id', 'patient_id');

	public function patient()
	{
		return $this->belongsTo('App\Patient');
	}

	public function doctor()
	{
		return $this->belongsTo('App\Doctor');
	}

	public function transaction()
	{
		return $this->hasOne('App\Transaction');
	}

}