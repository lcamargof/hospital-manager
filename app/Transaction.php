<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

	protected $table = 'transactions';
	public $timestamps = true;
	protected $fillable = array('amount', 'method', 'record_id');

	public function record()
	{
		return $this->belongsTo('App\Record');
	}

}