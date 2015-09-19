<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model {

	protected $table = 'beds';
	public $timestamps = true;

	public function ward()
	{
		return $this->belongsTo('App\Ward');
	}

	public function patient()
	{
		return $this->belongsTo('App\Patient');
	}

	public function nurse()
	{
		return $this->belongsToMany('App\Nurse');
	}

}