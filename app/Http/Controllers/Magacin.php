<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Security;
use App\MagaciniID;
use App\Magacin as Skladiste;
use Illuminate\Support\Facades\Input;

class Magacin extends Controller {

	public function getIndex(){
		$magacini = MagaciniID::get(['id','naziv','opis'])->toArray();
		return Security::autentifikacija('stranice.administracija.magacini',compact('magacini'));
	}
	public function getNovi(){
		return Security::autentifikacija('stranice.administracija.magacini',['novi'=>true]);
	}
	public function postMagacin(){
		if(Security::autentifikacijaTest()){
			$magacin = Input::get('id') ? MagaciniID::where('id','=',Input::get('id'))->get(['id','naziv','opis'])->first() : new MagaciniID();
			$magacin->naziv = Input::get('naziv');
			$magacin->opis = Input::get('opis');
			$magacin->save();
			return redirect('/administracija/magacin');
		}
		return Security::rediectToLogin();
	}
	public function getAzuriraj($id){
		$magacin = MagaciniID::where('id','=',$id)->get(['id','naziv','opis'])->first()->toArray();
		return Security::autentifikacija('stranice.administracija.magacini',compact('magacin'));
	}
	public function getUkloni($id){
		if(Security::autentifikacijaTest()){
			MagaciniID::destroy($id);
			return redirect('/administracija/magacin');
		}
		return Security::rediectToLogin();
	}
	public function getPregled($id){
		$magacin = MagaciniID::where('id','=',$id)->get(['id','naziv'])->first()->toArray();
		$umagacinu = Skladiste::join('proizvod','proizvod.id','=','magacin.proizvod_id')
			->where('magacinid_id','=',$id)->get(['magacinid_id','proizvod.sifra','proizvod.naziv','kolicina_stanje','kolicina_min','pozicija_id'])->toArray();
		return Security::autentifikacija('stranice.administracija.magacin',compact('magacin','umagacinu'));
	}

}
