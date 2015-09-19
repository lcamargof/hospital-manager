<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller{
	
	public function doLogin(Request $request)
	{
		$username = mb_strtolower(trim($request->get('user')));
		$password = $request->get('password');
		$remember = ($request->get('remember')) ? true : false;

		if (Auth::attempt(array('user' => $username, 'password'=> $password), $remember)) {
			$acc = User::find(Auth::user()->id);
			$acc->timestamps = false;
			$acc->last_login = date('Y-m-d H:i:s');
			$acc->save();
			return redirect('/home');
		} else {
			return redirect()->back()->with('msg', 'Wrong data, try again.');
		}
	}

	public function rolCheck()	
	{
		switch (Auth::user()->role) {
			case 'master':
				return view('admin.adminlayout', array('location' => 'home'))
								->nest('content', 'userhome', array('user' => 'Master'));
			case 'receptionist':
				return view('reception.receptionlayout', array('location' => 'home'))->nest('content', 'userhome', array('user' => 'Receptionist'));
			case 'doctor':
				return view('doctor.doctorlayout', array('location' => 'home'))->nest('content', 'userhome', array('user' => 'Doctor'));			
			default:
				Auth::logout();
				return redirect('/');
		}
	}

	public function doLogout()	//METODO PARA LOGOUT DE USUARIO
	{
		Auth::logout();
		return redirect('/');
	}
}