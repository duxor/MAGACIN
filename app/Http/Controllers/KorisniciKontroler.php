<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Korisnici;
use App\PravaPristupa;
use App\VrstaKorisnika;
use Illuminate\Http\Request;
use App\Security;

class KorisniciKontroler extends Controller {

	public function getIndex(){
		$korisnici=Korisnici::join('vrsta_korisnika as vk','vk.id','=','korisnici.vrsta_korisnika_id')
			->join('prava_pristupa as pp','pp.id','=','korisnici.prava_pristupa_id')
			->whereBetween('vrsta_korisnika_id',[1,3])
			->get(['korisnici.id','prezime','ime','email','prava_pristupa_id','pp.naziv as prava_pristupa_naziv',
					'vrsta_korisnika_id','vk.naziv as vrsta_korisnika_naziv', 'korisnici.naziv','adresa','grad',
					'jib','pib','pdv','ziro_racun_1','banka_1','ziro_racun_2','banka_2','registracija',
					'broj_upisa','telefon'])
			->toArray();
		$vrstaKorisnika=VrstaKorisnika::where('id','<','4')->get(['id','naziv'])->lists('naziv','id');
		$pravaPristupa=PravaPristupa::where('id','<','4')->get(['id','naziv'])->lists('naziv','id');
		return Security::autentifikacija('administracija.korisnici',compact('korisnici','vrstaKorisnika','pravaPristupa'));
	}

	public function postIndex(){
		$podaci=json_decode(Input::get('podaci'));
		return json_encode(['msg'=>'Uspješno ste dodali novog korisnika.','check'=>1]);
	}
}
