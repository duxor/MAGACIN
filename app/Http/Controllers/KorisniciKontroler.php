<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
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
		 $validator=Validator::make([
            'username'=>$podaci->username,
            'email'=>$podaci->email,
            'password'=>$podaci->password,
        ],[
            'username'=>'required|min:4|unique:korisnici,username',
            'email'=>'required|email|unique:korisnici,email',
            'password'=>'required|min:4',

        ],[
            //username
            'username.required'=>'Obavezan unos username-a.',
            'username.min'=>'Minimalna duzina username-a je :min.',
            'username.unique'=>'Navedeni username je u upotrebi.',
            //email
            'email.email'=>'Pogrešno unesen email.',
            'email.required'=>'Obavezan unos email-a.',
            'email.unique'=>'Navedeni email je u upotrebi.',
            //pass
            'password.required'=>'Obavezan unos password-a.',
            'password.min'=>'Minimalna duzina password-a je :min.',
        ]);
		if($validator->fails()){
			$msg='<p>Dogodila se greška: <br><ol>';
			foreach($validator->errors()->toArray() as $greske)
				foreach($greske as $greska)
					$msg.='<li>'.$greska.'</li>';
			$msg.='</ol>';
			return json_encode(['msg'=>$msg,'check'=>0]);
		}

		$novi = new Korisnici();
		$novi->prezime=$podaci->prezime;
		$novi->ime=$podaci->ime;
		$novi->username=$podaci->username;
		$novi->password=Security::generateHashPass($podaci->password);
		$novi->email=$podaci->email;
		$novi->prava_pristupa_id=$podaci->prava_pristupa_id;
		$novi->vrsta_korisnika_id=$podaci->vrsta_korisnika_id;
		$novi->naziv=$podaci->naziv;
		$novi->adresa=$podaci->adresa;
		$novi->grad=$podaci->grad;
		$novi->jib=$podaci->jib;
		$novi->pib=$podaci->pib;
		$novi->pdv=$podaci->pdv;
		$novi->ziro_racun_1=$podaci->ziro_racun_1;
		$novi->ziro_racun_2=$podaci->ziro_racun_2;
		$novi->banka_1=$podaci->banka_1;
		$novi->banka_2=$podaci->banka_2;
		$novi->registracija=$podaci->registracija;
		$novi->broj_upisa=$podaci->broj_upisa;
		$novi->telefon=$podaci->telefon;
		$novi->opis=$podaci->opis;
		$novi->save();
		return json_encode(['msg'=>'Uspješno ste dodali novog korisnika.','check'=>1]);


	}
}
