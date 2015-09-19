<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model {

	protected $table = 'patients';
	public $timestamps = true;
	protected $fillable = array('name', 'birth_date', 'sex', 'id_number', 'phone', 'address', 'blood_type', 'observations', 'allergies');

	public function bed()
	{
		return $this->hasOne('App\Bed')->with('ward');
	}

	public function records()
	{
		return $this->hasMany('App\Record');
	}

	public function doctors()
	{
		return $this->hasManyThrough('App\Doctor', 'App\Record');
	}
}