<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\MagaciniID;
use App\Pozicija;
use App\Security;
use App\Proizvodi;
use App\Magacin as Skladiste;
use Illuminate\Support\Facades\Input;

class Proizvod extends Controller {

	public function getIndex(){
		$proizvodi = Proizvodi::get(['id','sifra','naziv','opis','cijena'])->toArray();
		return Security::autentifikacija('stranice.administracija.proizvodi',compact('proizvodi'));
	}
	public function getNovi(){
		return Security::autentifikacija('stranice.administracija.proizvodi',['novi'=>true]);
	}
	public function postProizvod(){
		if(Security::autentifikacijaTest()){
			$proizvod = Input::get('id') ? Proizvodi::where('id','=',Input::get('id'))->get(['id','naziv','opis'])->first() : new Proizvodi();
			$proizvod->sifra = Input::get('sifra');
			$proizvod->naziv = Input::get('naziv');
			$proizvod->opis = Input::get('opis');
			$proizvod->cijena = Input::get('cijena');
			$proizvod->save();
			return redirect('/administracija/proizvod');
		}
		return Security::rediectToLogin();
	}
	public function getAzuriraj($id){
		$proizvod = Proizvodi::where('id','=',$id)->get(['id','sifra','naziv','opis','cijena'])->first()->toArray();
		return Security::autentifikacija('stranice.administracija.proizvodi',compact('proizvod'));
	}
	public function getUkloni($id){
		if(Security::autentifikacijaTest()){
			Proizvodi::destroy($id);
			return redirect('/administracija/proizvod');
		}
		return Security::rediectToLogin();
	}
	public function getMagacin($id){
		$umagacin = MagaciniID::all()->lists('naziv','id');
		$proizvod_podaci = Proizvodi::where('id','=',$id)->get(['id','sifra','naziv'])->first()->toArray();
		return Security::autentifikacija('stranice.administracija.proizvodi',compact('umagacin','proizvod_podaci'));
	}
	public function postMagacin(){
		if(Security::autentifikacijaTest()){
			$pozicija = new Pozicija();
			$pozicija->stolaza = Input::get('stolaza');
			$pozicija->polica = Input::get('polica');
			$pozicija->pozicija = Input::get('pozicija');
			$pozicija->opis = Input::get('opis');
			$pozicija->save();

			$umagacin = new Skladiste();
			$umagacin->magacinid_id = Input::get('magacinid_id');
			$umagacin->proizvod_id = Input::get('proizvod_id');
			$umagacin->kolicina_stanje = Input::get('kolicina_stanje');
			$umagacin->kolicina_min = Input::get('kolicina_min');
			$umagacin->pozicija_id = $pozicija->id;
			$umagacin->save();
			return redirect('/administracija/magacin/pregled/'.Input::get('magacinid_id'));
		}
		return Security::rediectToLogin();
	}

}
