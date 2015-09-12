<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Security;
use App\MagaciniID;
use App\Magacin as Skladiste;
use App\ZaNarudzbu;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class Magacin extends Controller {

	public function getIndex(){
		$magacini = MagaciniID::get(['id','naziv','opis'])->toArray();
		return Security::autentifikacija('app-admin.magacin.index',compact('magacini'));
	}
	public function getNovi(){
		return Security::autentifikacija('app-admin.magacin.index',['novi'=>true]);
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
		return Security::autentifikacija('app-admin.magacin.index',compact('magacin'));
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
			->where('magacin_id_id','=',$id)
			->get(['magacin.id','magacin_id_id','proizvod.sifra','proizvod.naziv','kolicina_stanje','kolicina_min','pozicija_id'])->toArray();
		return Security::autentifikacija('app-admin.magacin.edit',compact('magacin','umagacinu'));
	}
	public function postProizvod(){
		if(Security::autentifikacijaTest()){
			$magacin = Skladiste::where('id','=',Input::get('magacin_id'))->get(['id','kolicina_stanje','kolicina_min'])->first();
			switch(Input::get('znak')){
				case '+': $magacin->kolicina_stanje = $magacin->kolicina_stanje+Input::get('kolicina_stanje');
					break;
				case '-': $magacin->kolicina_stanje = $magacin->kolicina_stanje-Input::get('kolicina_stanje');
					break;
				case 'min': $magacin->kolicina_min = Input::get('kolicina_stanje');
					break;
			}
			$magacin->naruceno = 0;
			$magacin->save();
			return Redirect::back();
		}
		return Security::rediectToLogin();
	}
	public function getProizvodUkloni($id){
		if(Security::autentifikacijaTest()){
			ZaNarudzbu::where('magacin_id','=',$id)->delete();
			Skladiste::destroy($id);
			return Redirect::back();
		}
		return Security::rediectToLogin();
	}

}
