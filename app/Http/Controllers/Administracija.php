<?php namespace App\Http\Controllers;

use App\Magacin as MMagacin;
use App\OsnovneMetode;
use App\Proizvodi;
use App\Security;
use Illuminate\Support\Facades\Input;
use App\Korisnici;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Aplikacija;
class Administracija extends Controller {
//LOG[in,out]
	public function getLogin(){
		if(Security::autentifikacijaTest(2,'min')){
			return redirect('/administracija');
		}
		return view('log.login');
	}
	public function postLogin(){
		$redirect=Security::login(Input::get('username'),Input::get('password'));

		if(Security::autentifikacijaTest(2,'min')){
			//Session::put('prava_pristupa',Korisnici::find(Session::get('id'),['prava_pristupa_id'])->prava_pristupa_id);
			if(Session::get('prava_pristupa')==4) {
				$app=Aplikacija::where('korisnici_id',Session::get('id'))->get(['id','slug'])->first();
				if($app) Session::put('aplikacija', $app->slug);
				if($app) Session::put('aplikacija_id', $app->id);
			}
		}
		return $redirect;
	}
	public function getLogout(){
		return Security::logout();
	}
//_______
	public function getIndex(){
		/*
		Prosleđivanje korisnika na platformu u zavisnosti od:
			# vrste korisnika
			# prava pristupa
			# aktivnosti-egzistencije aplikacije
		Slučaj 1: SuperAdministrator
			[prava_pristupa=Administrator, vrsta_korisnika=Administrator]
			>Pristup administraciji PLATFORME,
				[
					0 kreiranje novih app,
					0 deaktiviranje app,
					0 brisanje app,
					0 kreiranje korisnika sa prava_pristupa=Administrator,
					0 deaktiviranje korisnika
					0 brisanje korirnika
				]
		Slučaj 2: AplikativniAdministrator
			[prava_pristupa=Administrator, vrsta_korisnika=Vlasnik]
			>Pristup administraciji svoje APLIKACIJE
				[
					0 uređivanje svoje app
					0 popunjavanje šifarnika [vrsta_proizvoda, magacin_id, pozicija]
					0 unos proizvoda
					0 ažuriranje proizvoda
					0 dodavanje proizvoda u magacin
					0 ažuriranje stanja magacina [cijene, kolicine na stanju...]
				]
		Slučaj 3: Dobavljač
			>Pristup APLIKACIJI za administraciju narudžbi
				[
					0 Pregled narudžbi
					0 Ažuriranje stanja narudžbenice [U stanju obrade, Čeka se nabavka, Poslato]
				]
		Slučaj 4: Kupac
			>Pristup APLIKACIJI za pregled narudžbi
				[
					0 Pregled narudžbi sa statusom, servisima koji su rađeni, komantarom vlasnika [između ostalog treba da sadrži i garanciju na realizovani proizvod, koju ažurira AplikativniAdministrator]
				]
		*/
		switch(Session::get('prava_pristupa')){
			case 2: return 'Kupac';
			case 3: return 'Dobavljac';
			case 4: return Security::autentifikacija('app-admin.index',null,4);
			case 5: return Security::autentifikacija('super-admin.index',null,5);
		}
		return redirect('/administracija/login');
	}
	public function postUcitajPodatkeZaFakturu(){
		$ispis['podaci']=Aplikacija::where('slug',Session::get('aplikacija'))
			->get(['naziv','adresa','grad','jib','pib','pdv','ziro_racun_1','banka_1','ziro_racun_2','banka_2','registracija',
				'broj_upisa','telefon'])->first()->toArray();
		if(Session::has('podaci')) Session::forget('podaci');
		Session::put('podaci',$ispis['podaci']);
		$ispis['dobavljaci']=Korisnici::join('korisnici_aplikacije as ka','ka.korisnici_id','=','korisnici.id')
			->join('aplikacija as a','ka.aplikacija_id','=','a.id')
			->where('a.slug',Session::get('aplikacija'))
			->where('korisnici.prava_pristupa_id',3)
			->get(['korisnici.prezime','korisnici.ime','korisnici.jmbg','korisnici.naziv','korisnici.pib'])->toArray();
		return json_encode($ispis);
	}

	public function anyUcitajTabeluProizvoda(){
		$proizvodi=[];
		switch($_POST['vrstaKorisnika']){
		//Ukoliko KUPAC kupuje proizvod
			case 2:
				foreach(Session::get('korpa') as $k=>$proizvod){
					$proizvodi[$k]=Proizvodi::join('magacin as m','m.proizvod_id','=','proizvod.id')
						->where('proizvod.id',$proizvod['id'])
						->get(['proizvod.id','proizvod.sifra','proizvod.naziv','proizvod.jedinica_mjere','m.cijena as maloprodajna_cijena'])
						->first()
						->toArray();
					//$proizvodi[$k]['cijena_bez_pdv']=$proizvodi[$k]['maloprodajna_cijena']*0.83;
					//$proizvodi[$k]['cijena_pdv']=$proizvodi[$k]['maloprodajna_cijena']*0.17;
					//$proizvodi[$k]['cijena_sa_pdv']=$proizvodi[$k]['maloprodajna_cijena'];

					$proizvodi[$k]['ukupno_na_stanju']=MMagacin::join('magacin_id as m','m.id','=','magacin.magacin_id_id')
						->where('m.aplikacija_id',Session::get('aplikacija_id'))
						->where('magacin.proizvod_id',$proizvod['id'])
						->groupBy('magacin.proizvod_id')
						->sum('magacin.kolicina_stanje');
				}
			break;
		//Ukoliko se vrsi narudzba od dobavljaca
			case 3:
				foreach(Session::get('korpa') as $k=>$proizvod){
					$proizvodi[$k]=Proizvodi::find($proizvod['id'],['id','sifra','naziv','jedinica_mjere'])->toArray();
				}
			break;
		}
		return json_encode($proizvodi);
	}

}
