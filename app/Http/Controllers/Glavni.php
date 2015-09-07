<?php namespace App\Http\Controllers;

use App\OsnovneMetode;

class Glavni extends Controller {

	public function getIndex()
	{
		return view('index');
	}

	public function faktura(){
		return OsnovneMetode::faktura();
	}

}
