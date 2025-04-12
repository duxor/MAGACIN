<?php namespace App\Http\Controllers;

use App\Fakture;
use App\Korisnici;
use App\Log;
use App\Magacin as MMagacin;
use App\Proizvodi;
use App\Security;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\Aplikacija;
use PDF;
use App\Fakture as FFakture;
use App\ZaNarudzbu;
use Illuminate\Support\Facades\App;

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
            switch(Session::get('prava_pristupa')){
                case 2:break;
                case 3:break;
                case 4:Session::put('slug','radnik');break;
                case 5:Session::put('slug','app-admin');break;
                case 6:Session::put('slug','super-admin');break;
            }
			if(in_array(Session::get('prava_pristupa'),[4,5])){
				$app=Session::get('prava_pristupa')==5? Aplikacija::where('korisnici_id',Session::get('id'))->get(['id','slug','jezik'])->first()
                    : Aplikacija::join('korisnici_aplikacije as ka','ka.aplikacija_id','=','aplikacija.id')->join('korisnici as k','k.id','=','ka.korisnici_id')
                        ->where('k.prava_pristupa_id',4)->where('k.id',Session::get('id'))->get(['aplikacija.id','slug','jezik'])->first();
				if($app){
                    Session::put('aplikacija', $app->slug);
                    Session::put('aplikacija_id', $app->id);
                    Session::put('jezik', $app->jezik);
                }
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
        App::setLocale(Session::get('jezik'));
        switch(Session::get('prava_pristupa')){
			case 2: return 'Kupac';
			case 3: return 'Dobavljac';
            case 4: return Security::autentifikacija('radnik.index',null,4);
            case 5: return Security::autentifikacija('app-admin.index',null,5);
			case 6: return Security::autentifikacija('super-admin.index',null,6);
		}
		return redirect('/administracija/login');
	}
	public function getSessions(){dd(Session::all());}

    public function getUputstvo(){
        return Security::autentifikacija('app-admin.ostalo.uputstvo');
    }

    public function getOsnovnaPodesavanja(){
        return Security::autentifikacija('app-admin.ostalo.osnovna-podesavanja');
    }

    public function postOsnovnoNalog(){
        if(!Security::autentifikacijaTest(5)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        $podaci=Aplikacija::find(Session::get('aplikacija_id'),['naziv','adresa','grad','telefon'])->toArray();
        $podaci['logo']='/img/aplikacije/'.Session::get('aplikacija').'/logo.jpg';
        return json_encode($podaci);
    }
    public function postOsnovnoPodaci(){//
        if(!Security::autentifikacijaTest(5)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        $podaci=Aplikacija::find(Session::get('aplikacija_id'),['jib','pib','pdv','ziro_racun_1','banka_1','ziro_racun_2','banka_2','registracija','broj_upisa'])->toArray();
        return json_encode($podaci);
    }
    public function postOsnovnoFakture(){
        if(!Security::autentifikacijaTest(5)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        $podaci=Aplikacija::find(Session::get('aplikacija_id'),['faktura_futer_1','faktura_futer_2','faktura_futer_3'])->toArray();
        return json_encode($podaci);
    }
    public function postOsnovnoSifarnici(){
        if(!Security::autentifikacijaTest(5)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        $podaci=[];
        return json_encode($podaci);
    }

//Aplikacije
    public function getAplikacije(){
        if(!Security::autentifikacijaTest(6)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        return view('super-admin.aplikacije.index');
    }
    public function postUcitajAplikacije(){
        if(!Security::autentifikacijaTest(6)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        return json_encode(Aplikacija::where(function($query) {
            $query->where('naziv', 'Like', '%' . Input::get('pretraga') . '%')->orWhere('slug', 'Like', '%' . Input::get('pretraga') . '%');
        })->get(['id','naziv','slug','korisnici_id','email','napomena','aktivan'])->toArray());
    }
    public function postAplikacijeUcitajKorisnike(){
        if(!Security::autentifikacijaTest(6)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        return json_encode(Korisnici::where('prava_pristupa_id','>',4)->get(['id','prezime','ime'])->toArray());
    }
    public function postAplikacijaPromijeniVlasnistvo(){
        if(!Security::autentifikacijaTest(6)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        Aplikacija::find(Input::get('id'),['id','korisnici_id'])->update(['korisnici_id'=>Input::get('vlasnik')]);
        return json_encode(['msg'=>'Uspješno ažuriranje.','check'=>1]);
    }
    public function postAplikacijaSlugCheck(){
        if(!Security::autentifikacijaTest(6)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        return Aplikacija::where('slug',Input::get('slug'))->exists()?0:1;
    }
    public function postAplikacijaSacuvaj(){
        if(!Security::autentifikacijaTest(6)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        $podaci=json_decode(Input::get('podaci'));
        $app=isset($podaci->id)?Aplikacija::find($podaci->id,['id','logo','naziv','slug','email','korisnici_id','napomena']):new Aplikacija();
        if(isset($podaci->logo)) $app->logo=$podaci->logo;
        $app->naziv=$podaci->naziv;
        $app->slug=$podaci->slug;
        $app->email=$podaci->email;
        $app->korisnici_id=$podaci->korisnici_id;
        $app->napomena=$podaci->napomena;
        $app->save();
        if(!isset($podaci->id)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/img/aplikacije/' . $app->slug . '/fakture','0755',true);
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/img/aplikacije/' . $app->slug . '/narudzbenice','0755');
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/img/aplikacije/' . $app->slug . '/predracuni','0755');
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/img/aplikacije/' . $app->slug . '/ulazi','0755');
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/img/aplikacije/' . $app->slug . '/proizvodi','0755');
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/img/aplikacije/' . $app->slug . '/inicijalno','0755');
            copy($_SERVER['DOCUMENT_ROOT'] . $podaci->logo,$_SERVER['DOCUMENT_ROOT'] . '/img/aplikacije/' . $app->slug. '/logo'.'.'.explode('.', $podaci->logo)[1]);
            unlink($_SERVER['DOCUMENT_ROOT'] . $podaci->logo);
        }
        return json_encode(['msg'=>'Uspješno ste sačuvali podatke.','check'=>1]);
    }
    public function postAplikacijaDeaktiviraj(){
        if(!Security::autentifikacijaTest(6)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        $aktivan=$_POST['aktivan']?0:1;
        Aplikacija::find($_POST['id'],['id','aktivan'])->update(['aktivan'=>$aktivan]);
        return $aktivan;
    }
    public function postUploadLogo(){
        if(!Security::autentifikacijaTest(5,'min')) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        if (empty($_FILES['foto'])) {
            echo json_encode(['error'=>'Nisu pronađeni fajlovi za upload.']);
            return;
        }
        if(Input::get('id')!='undefined' || Session::has('aplikacija')) $folder = 'img/aplikacije/'.(Session::has('aplikacija')?Session::get('aplikacija'):Input::get('slug')).'/logo'.'.'.explode('.', $_FILES['foto']['name'])[1];
        else $folder = 'img/privremeno/logo-'.Input::get('slug').'.'.explode('.', $_FILES['foto']['name'])[1];
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
    public function postAplikacijaOsnovnoSacuvaj(){
        if(!Security::autentifikacijaTest(5)) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        $podaci=json_decode(Input::get('podaci'));$a=1;
        if(isset($podaci->naziv)){
            Aplikacija::find(Session::get('aplikacija_id'),['id','logo','naziv','adresa','grad','telefon'])
                ->update(['logo'=>$podaci->logo,'naziv'=>$podaci->naziv,'adresa'=>$podaci->adresa,'grad'=>$podaci->grad,'telefon'=>$podaci->telefon]);
        }else if(isset($podaci->registracija)){
            Aplikacija::find(Session::get('aplikacija_id'),['id','jib','pib','pdv','ziro_racun_1','banka_1','ziro_racun_2','banka_2','registracija','broj_upisa'])
                ->update(['jib'=>$podaci->jib,'pib'=>$podaci->pib,'pdv'=>$podaci->pdv,'ziro_racun_1'=>$podaci->ziro_racun_1,'banka_1'=>$podaci->banka_1,'ziro_racun_2'=>$podaci->ziro_racun_2,'banka_2'=>$podaci->banka_2,'registracija'=>$podaci->registracija,'broj_upisa'=>$podaci->broj_upisa]);
        }else if(isset($podaci->faktura_futer_1)){$a=44;
            $app=Aplikacija::find(Session::get('aplikacija_id'),['id','faktura_futer_1','faktura_futer_2','faktura_futer_3']);
            $app->faktura_futer_1=$podaci->faktura_futer_1;$app->faktura_futer_2=$podaci->faktura_futer_2;$app->faktura_futer_3=$podaci->faktura_futer_3;
            $app->save();
        }
        return json_encode(['msg'=>'Ažuriranje je izvršeno.','check'=>1]);
    }
    
//Verzioniranje
    public function getVerzioniranje(){
        return Security::autentifikacija('app-admin.ostalo.verzioniranje',null,5,'min');
    }

    public function getVideo() {
        $path = "img/uputstvo/video-uputstvo-fakturisanje.mp4";
        $contentType='mp4';
        $fullsize = filesize($path);
        $size = $fullsize;
        $stream = fopen($path, "r");
        $response_code = 200;
        $headers = array("Content-type" => $contentType);
        $range = Request::header('Range');
        if($range != null) {
            $eqPos = strpos($range, "=");
            $toPos = strpos($range, "-");
            $unit = substr($range, 0, $eqPos);
            $start = intval(substr($range, $eqPos+1, $toPos));
            $success = fseek($stream, $start);
            if($success == 0) {
                $size = $fullsize - $start;
                $response_code = 206;
                $headers["Accept-Ranges"] = $unit;
                $headers["Content-Range"] = $unit . " " . $start . "-" . ($fullsize-1) . "/" . $fullsize;
            }
        }
        $headers["Content-Length"] = $size;
        return Response::stream(function () use ($stream) {
            fpassthru($stream);
        }, $response_code, $headers);
    }
}
