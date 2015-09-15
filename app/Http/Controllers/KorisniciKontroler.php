<?php namespace App\Http\Controllers;

use App\Aplikacija;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Korisnici;
use App\PravaPristupa;
use App\VrstaKorisnika;
use Illuminate\Http\Request;
use App\Security;
use Illuminate\Support\Facades\Session;
use App\KorisniciAplikacije;
class KorisniciKontroler extends Controller {
	public function getIndex(){
		switch(Session::get('prava_pristupa')){
			case 4:
				/*$korisnici=Korisnici::join('prava_pristupa as pp','pp.id','=','korisnici.prava_pristupa_id')
					->whereBetween('pp.id',[1,3])
					->get(['korisnici.id','prezime','ime','email','prava_pristupa_id','pp.naziv as prava_pristupa_naziv',
							'korisnici.naziv','adresa','grad', 'jib','pib','pdv','ziro_racun_1','banka_1','ziro_racun_2',
							'banka_2','registracija', 'broj_upisa','telefon'])
					->toArray();*/
				$pravaPristupa=PravaPristupa::whereBetween('id',[2,3])->get(['id','naziv'])->lists('naziv','id');
				$pravaPristupa[0]='Vrsta korisnika';
				return Security::autentifikacija('app-admin.korisnici.index',compact('korisnici','vrstaKorisnika','pravaPristupa'),4);
			break;

			case 5:
				return Security::autentifikacija('super-admin.korisnici.index',null,5);
			break;
		}
	}
	public function postUcitaj(){
		switch(Session::get('prava_pristupa')){
			case 5:
				return json_encode(Korisnici::where('prava_pristupa_id',4)
					->where(function($query){
					$query->where('korisnici.prezime','Like','%'.(isset($_POST['pretraga'])?$_POST['pretraga']:'').'%')
						->orWhere('korisnici.ime','Like','%'.(isset($_POST['pretraga'])?$_POST['pretraga']:'').'%')
						->orWhere('korisnici.naziv','Like','%'.(isset($_POST['pretraga'])?$_POST['pretraga']:'').'%');
					})
					->get(['korisnici.id','prezime','ime','jmbg','korisnici.adresa','korisnici.grad','korisnici.naziv','korisnici.pib','korisnici.opis','korisnici.aktivan'])
					->toArray());
			break;
			default:
				return json_encode(Korisnici::join('korisnici_aplikacije as ka','korisnici.id','=','ka.korisnici_id')
					->join('aplikacija as a','ka.aplikacija_id','=','a.id')
					->join('prava_pristupa as pp','pp.id','=','korisnici.prava_pristupa_id')
					->where('a.slug','=',Session::get('aplikacija'))
					->where('prava_pristupa_id',(isset($_POST['vrsta'])?$_POST['vrsta']>0?'=':'<':'<'),(isset($_POST['vrsta'])?$_POST['vrsta']>0?$_POST['vrsta']:4:4))
					->where(function($query){
						$query->where('korisnici.prezime','Like','%'.(isset($_POST['pretraga'])?$_POST['pretraga']:'').'%')
							->orWhere('korisnici.ime','Like','%'.(isset($_POST['pretraga'])?$_POST['pretraga']:'').'%')
							->orWhere('korisnici.naziv','Like','%'.(isset($_POST['pretraga'])?$_POST['pretraga']:'').'%');
					})
					->get(['korisnici.id','prezime','ime','jmbg','korisnici.adresa','korisnici.grad','korisnici.naziv','korisnici.pib',
						'korisnici.opis','korisnici.aktivan','pp.naziv as vrsta'])
					->toArray());
				break;
		}

	}
	public function postEditUcitaj(){
		return json_encode(Korisnici::find($_POST['id']));
	}
	public function postDeaktiviraj(){
		$aktivan=$_POST['aktivan']?0:1;
		Korisnici::find($_POST['id'],['id','aktivan'])->update(['aktivan'=>$aktivan]);
		return $aktivan;
	}
	public function postAzuriraj(){
		$podaci=json_decode(Input::get('podaci'));
		//UNOS PODATAKA U BAZU
		//..
		//..FALI [jmbg,broj_licne_karte,pristup_platformi(default=1)]
		$korisnik=isset($podaci->id)? Korisnici::find($podaci->id) : new Korisnici();

		$korisnik->prava_pristupa_id = $podaci->prava_pristupa_id;
		$korisnik->foto = $podaci->imgSrc;
		$korisnik->prezime = $podaci->prezime;
		$korisnik->ime = $podaci->ime;
		$korisnik->username = $podaci->username;
		if($podaci->password) $korisnik->password = Security::generateHashPass($podaci->password);
		$korisnik->email = $podaci->email;
		$korisnik->adresa = $podaci->adresa;
		$korisnik->grad = $podaci->grad;
		$korisnik->telefon = $podaci->telefon;
		$korisnik->jmbg = $podaci->jmbg;
		$korisnik->broj_licne_karte = $podaci->broj_licne_karte;

		$korisnik->naziv = $podaci->naziv;
		$korisnik->opis = $podaci->opis;
		$korisnik->registracija = $podaci->registracija;
		$korisnik->broj_upisa = $podaci->broj_upisa;
		$korisnik->banka_1 = $podaci->banka_1;
		$korisnik->ziro_racun_1 = $podaci->ziro_racun_1;
		$korisnik->banka_2 = $podaci->banka_2;
		$korisnik->ziro_racun_2 = $podaci->ziro_racun_2;
		$korisnik->pdv = $podaci->pdv;
		$korisnik->pib = $podaci->pib;
		$korisnik->jib = $podaci->jib;

		$korisnik->save();

		if(Session::has('aplikacija')){
			KorisniciAplikacije::insert([
				[
					'korisnici_id'=>$korisnik->id,
					'aplikacija_id'=>Aplikacija::where('slug',Session::get('aplikacija'))->get(['id'])->first()->id
				]
			]);
		}
		return json_encode(['msg'=>'Uspješan unos.','check'=>1]);
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
	public function postUploadFoto(){
		/*if(!Security::autentifikacijaTest(2,'min')){
			echo json_encode(['error'=>'Niste prijavljeni na platformu.']);
			return;
		}*/
		if (empty($_FILES['foto'])) {
			echo json_encode(['error'=>'Nisu pronađeni fajlovi za upload.']);
			return;
		}
		$folder = 'img/'.(Session::has('aplikacija')?'aplikacije/'.Session::get('aplikacija').'/':'').'korisnici/'.((isset($_POST['id'])and($_POST['id']!='undefined'))?$_POST['id']:(Korisnici::max('id')+1)).'.'.explode('.', $_FILES['foto']['name'])[1];
		$success = null;
		$paths=null;
		if(file_exists($folder)) unlink($folder);
		if(move_uploaded_file($_FILES['foto']['tmp_name'], $folder)){
			$success = true;
			$paths = $folder.$_FILES['foto']['name'];
		} else {
			$success = false;
		}
		if ($success === true) {
			$output = $folder;
		} elseif ($success === false) {
			$output = ['error'=>'Greška prilikom upload-a. Kontaktirajte tehničku podršku platforme.'];
			unlink($paths);
		} else {
			$output = ['error'=>'Fajlovi nisu procesuirani.'];
		}
		echo json_encode($output);
		return;
	}
	public function postUcitajKorisnike(){
		return json_encode(Korisnici::join('korisnici_aplikacije as ka','ka.korisnici_id','=','korisnici.id')
			->join('aplikacija as a','a.id','=','ka.aplikacija_id')
			->where('a.slug',Session::get('aplikacija'))
			->where('korisnici.prava_pristupa_id',$_POST['vrsta_korisnika'])
			->where(function($query){
				$query->where('korisnici.prezime','Like','%'.$_POST['pretraga'].'%')
					->orWhere('korisnici.ime','Like','%'.$_POST['pretraga'].'%')
					->orWhere('korisnici.naziv','Like','%'.$_POST['pretraga'].'%')
					->orWhere('korisnici.jmbg','Like','%'.$_POST['pretraga'].'%');
			})
			->get(['korisnici.id','korisnici.prezime','korisnici.ime','korisnici.jmbg',
				'korisnici.naziv','korisnici.adresa','korisnici.grad','korisnici.telefon'])
			->toArray());
	}

	public function postIzaberiKorisnika(){
		return json_encode(Korisnici::find($_POST['id'],['korisnici.id','korisnici.prezime','korisnici.ime',
				'korisnici.naziv','korisnici.adresa','korisnici.grad','korisnici.jib','korisnici.pib','korisnici.pdv',
				'korisnici.ziro_racun_1','korisnici.banka_1','korisnici.ziro_racun_2','korisnici.banka_2',
				'korisnici.registracija','korisnici.broj_upisa','korisnici.telefon','korisnici.jmbg','korisnici.broj_licne_karte'])
			->toArray());
	}
}
