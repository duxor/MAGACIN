<?php namespace App\Http\Controllers;

use App\OsnovneMetode;
use App\Security;
use Illuminate\Support\Facades\Input;

class Administracija extends Controller {
//LOG[in,out]
	public function getLogin(){
		if(Security::autentifikacijaTest()) return redirect('/administracija');
		return view('administracija.login');
	}
	public function postLogin(){
		return Security::login(Input::get('username'),Input::get('password'));
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
		return Security::autentifikacija('administracija.index',null);
	}

}
