<?php namespace App\Http\Controllers;

use App\Aplikacija;
use App\Http\Requests;
use App\ProizvodMagacin;
use App\Security;
use App\MagaciniID;
use App\Magacin as Skladiste;
use App\ZaNarudzbu;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Magacin as MMagacin;
class Magacin extends Controller {

	public function getIndex(){
		//$magacini = MagaciniID::get(['id','naziv','opis'])->toArray();
		return Security::autentifikacija('app-admin.magacin.index',null,4);
	}

	public function postUcitaj(){
		return json_encode(MagaciniID::join('aplikacija as a','magacin_id.aplikacija_id','=','a.id')->where('a.slug',Session::get('aplikacija'))
			->get(['magacin_id.id','magacin_id.naziv','magacin_id.opis'])->toArray());
	}
//	public function getNovi(){
//		return Security::autentifikacija('app-admin.magacin.index',['novi'=>true]);
//	}
	public function postAzuriraj(){
		if(Security::autentifikacijaTest(4)){
			$podaci=json_decode(Input::get('podaci'));
			$magacin = isset($podaci->id) ? MagaciniID::where('id','=',$podaci->id)->get(['id','naziv','opis'])->first() : new MagaciniID();
			$magacin->naziv = $podaci->naziv;
			$magacin->opis = $podaci->opis;
			$magacin->aplikacija_id=Aplikacija::where('slug',Session::get('aplikacija'))->get(['id'])->first()->id;
			$magacin->save();
			return json_encode(['msg'=>"Uspješno ste sačuvali podatke o magacinu.",'check'=>1]);// redirect('/administracija/magacin');
		}
		return json_encode(['msg'=>"Dogodila se greška. Pokušajte ponovo i kontaktirajte tehničku podršku.",'check'=>0]);//Security::rediectToLogin();
	}
	public function postEditUcitaj(){
		return json_encode(MagaciniID::find($_POST['id']));
	}
//	public function getAzuriraj($id){
//		$magacin = MagaciniID::where('id','=',$id)->get(['id','naziv','opis'])->first()->toArray();
//		return Security::autentifikacija('app-admin.magacin.index',compact('magacin'));
//	}
	public function postUkloni(){
		if(Security::autentifikacijaTest(4)){
			ProizvodMagacin::join('magacin as m','proizvod_iz_magacina.magacin_id','=','m.id')->where('m.magacin_id_id',$_POST['id'])->delete();
			MMagacin::where('magacin_id_id',$_POST['id'])->delete();
			MagaciniID::destroy($_POST['id']);
			return;
		}
		return Security::rediectToLogin();
	}
//	public function getPregled($id){
//		$magacin = MagaciniID::where('id','=',$id)->get(['id','naziv'])->first()->toArray();
//		$umagacinu = Skladiste::join('proizvod','proizvod.id','=','magacin.proizvod_id')
//			->where('magacin_id_id','=',$id)
//			->get(['magacin.id','magacin_id_id','proizvod.sifra','proizvod.naziv','kolicina_stanje','kolicina_min','pozicija_id'])->toArray();
//		return Security::autentifikacija('app-admin.magacin.edit',compact('magacin','umagacinu'));
//	}
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
