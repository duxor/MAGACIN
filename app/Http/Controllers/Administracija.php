<?php namespace App\Http\Controllers;

use App\MagaciniID;
use App\Security;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class Administracija extends Controller {
//LOG[in,out]
	public function getLogin(){
		if(Security::autentifikacijaTest()) return redirect('/administracija');
		return view('stranice.administracija.login');
	}
	public function postLogin(){
		return Security::login(Input::get('username'),Input::get('password'));
	}
	public function getLogout(){
		return Security::logout();
	}
//_______
	public function getIndex(){
		return Security::autentifikacija('stranice.administracija.index',null);
	}

}
