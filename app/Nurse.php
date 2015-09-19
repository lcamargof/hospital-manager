<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nurse extends Model {

	protected $table = 'nurses';
	public $timestamps = true;
	protected $fillable = array('name', 'birth_date', 'id_number', 'shift');

	public function beds()
	{
		return $this->belongsToMany('App\Bed');
	}

}