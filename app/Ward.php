<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model {

	protected $table = 'wards';
	public $timestamps = true;

	public function beds()
	{
		return $this->hasMany('App\Bed');
	}

}