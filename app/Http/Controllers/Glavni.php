<?php namespace App\Http\Controllers;

class Glavni extends Controller {

	public function getIndex()
	{
		return view('stranice.index');
	}

}
