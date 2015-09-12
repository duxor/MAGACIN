<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\MagaciniID;
use App\Narudzbenice;
use App\OsnovneMetode;
use App\Pozicija;
use App\Security;
use App\Proizvodi;
use App\Magacin as Skladiste;
use App\ZaNarudzbu;
use Illuminate\Support\Facades\Input;
use Anouar\Fpdf\Facades\Fpdf;
use Illuminate\Support\Facades\Redirect;
use App\VrstaProizvoda;
use Illuminate\Support\Facades\Session;
class Proizvod extends Controller {

	public function getIndex(){
		$proizvodi = Proizvodi::get(['id','sifra','naziv','opis'])->toArray();
		$vrstaProizvoda=VrstaProizvoda::lists('naziv','id');
		return Security::autentifikacija('app-admin.proizvodi.index',compact('proizvodi','vrstaProizvoda'));
	}

	public function postIndex(){
		return json_encode(Proizvodi::join('aplikacija','proizvod.aplikacija_id','=','aplikacija.id')
			->where('aplikacija.slug',Session::get('aplikacija'))->get(['proizvod.id','sifra','proizvod.naziv','proizvod.opis'])->toArray());
	}
	public function getNovi(){
		return Security::autentifikacija('app-admin.proizvodi.index',['novi'=>true]);
	}
	public function postEditSave(){
		if(Security::autentifikacijaTest()){
			$proizvod = Input::get('id') ? Proizvodi::where('id','=',Input::get('id'))->get(['id','naziv','opis'])->first() : new Proizvodi();
			$proizvod->sifra = Input::get('ssifra');
			$proizvod->naziv = Input::get('naziv');
			$proizvod->opis = Input::get('opis');
			$proizvod->bar_kod = Input::get('bar_kod');
			$proizvod->proizvodjac = Input::get('proizvodjac');
			$proizvod->jedinica_mjere = Input::get('jedinica_mjere');
			$proizvod->pakovanje_kolicina = Input::get('pakovanje_kolicina');
			$proizvod->pakovanje_jedinica_mjere = Input::get('pakovanje_jedinica_mjere');
			$proizvod->vrsta_proizvoda_id = Input::get('vrsta_proizvoda_id');
			$proizvod->aplikacija_id = Input::get('aplikacija_id');
			$proizvod->foto = Input::get('imgSrc');
			$proizvod->save();
			return redirect('/administracija/proizvod');
		}
		return Security::rediectToLogin();
	}
	public function getAzuriraj($id){
		$proizvod = Proizvodi::where('id','=',$id)->get(['id','sifra','naziv','opis','cijena_nabavna','cijena_prodajna'])->first()->toArray();
		return Security::autentifikacija('app-admin.proizvodi.index',compact('proizvod'));
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
		return Security::autentifikacija('app-admin.proizvodi.index',compact('umagacin','proizvod_podaci'));
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
	public function getZaNarudzbu(){
		if(Security::autentifikacijaTest()){
			$zaNarudzbu = Skladiste::join('proizvod','proizvod.id','=','magacin.proizvod_id')
				->join('magacinid','magacinid.id','=','magacin.magacinid_id')
				->join('pozicija','pozicija.id','=','magacin.pozicija_id')
				->whereRaw('kolicina_stanje<kolicina_min')
				->where('naruceno','=',0)
				->get(['magacin.id','sifra','proizvod.naziv as naziv_proizvoda',
					'kolicina_stanje','kolicina_min',
					'magacin.magacinid_id','magacinid.naziv as naziv_magacina',
					'magacin.pozicija_id','stolaza','polica','pozicija.pozicija as pozicija_na_stolazi'])
				->toArray();
			return Security::autentifikacija('app-admin.fakture.index',compact('zaNarudzbu'));
		}
		return Security::rediectToLogin();
	}
	public function postNarudzbenica(){
		if(Security::autentifikacijaTest()){
			$proizvodi = json_decode(Input::get('proizvodi'));
			foreach($proizvodi as $k => $proizvod){
				if($proizvod){
					$proizvodi[$k] = Skladiste::join('proizvod','proizvod.id','=','magacin.proizvod_id')
						->where('magacin.id','=',$proizvod)->get(['magacin.id','sifra','naziv','opis','kolicina_stanje','kolicina_min','pozicija_id'])->first()->toArray();//,'cijena'
				}else unset($proizvodi[$k]);
			}
			return Security::autentifikacija('app-admin.fakture.index',compact('proizvodi'));
		}
		return Security::rediectToLogin();
	}
	public function postPrednarudzba(){
		if(Security::autentifikacijaTest()){
			$narudzbenica = new Narudzbenice();
			$narudzbenica->datum_narudzbe = Input::get('datum');
			$narudzbenica->save();

			$prednarudzbenica = [];
			foreach(Input::get('kolicina_narudzba') as $skladiste_id => $kolicina){
				$zaNarudzbu = new ZaNarudzbu();
				$zaNarudzbu->magacin_id = $skladiste_id;
				$zaNarudzbu->kolicina_porucena = $kolicina;
				$zaNarudzbu->narudzbenice_id = $narudzbenica->id;
				$prednarudzbenica[$skladiste_id] = Skladiste::join('proizvod','proizvod.id','=','magacin.proizvod_id')
							->where('magacin.id','=',$skladiste_id)
							->get(['proizvod.id','sifra','naziv'])->first()->toArray();
				$prednarudzbenica[$skladiste_id]['kolicina_naruceno'] = $kolicina;
				$zaNarudzbu->proizvod_id = $prednarudzbenica[$skladiste_id]['id'];
				$zaNarudzbu->save();
			}
			$narudzba = $narudzbenica->id;
			$header = ['R.br','Sifra','Naziv','Kolicina'];
			OsnovneMetode::pdfTabela($header,$prednarudzbenica,'narudzba_'.$narudzba);
			return Security::autentifikacija('app-admin.fakture.index',compact('prednarudzbenica','narudzba'));
		}
		return Security::rediectToLogin();
	}
	public function postNarudzbePotvrdi($id){
		if(Security::autentifikacijaTest()){
			$narudzbenica = Narudzbenice::where('id','=',$id)->get(['id','potvrda'])->first();
			$narudzbenica->potvrda = 1;
			$narudzbenica->save();
			if(Input::has('naruceno')){
				Skladiste::whereIn('id', ZaNarudzbu::where('narudzbenice_id','=',$id)->get(['magacin_id'])->toArray())->update(['naruceno'=>1]);
			}
			return redirect('/administracija/proizvod/narudzbe');
		}
		return Security::rediectToLogin();
	}
	public function getNarudzbeResetuj($id){
		if(Security::autentifikacijaTest()){
			ZaNarudzbu::where('narudzbenice_id','=',$id)->delete();
			Narudzbenice::destroy($id);
			unlink('pdf/narudzba_'.$id.'.pdf');
			return redirect('/administracija/proizvod/za-narudzbu');
		}
		return Security::rediectToLogin();
	}
	public function getNarudzbe(){
		if(Security::autentifikacijaTest()){
			$narudzbeArhiva['neporuceno'] = Narudzbenice::where('potvrda','=',1)->whereNull('datum_isporuke')->orderBy('datum_narudzbe','DESC')->get(['id','datum_narudzbe','datum_isporuke','potvrda'])->toArray();
			$narudzbeArhiva['isporuceno'] = Narudzbenice::where('potvrda','=',1)->whereNotNull('datum_isporuke')->orderBy('datum_isporuke','DESC')->get(['id','datum_narudzbe','datum_isporuke','potvrda'])->toArray();
			foreach($narudzbeArhiva as $ks => $stavka){
				foreach($stavka as $k => $narudzba){
					$narudzbeArhiva[$ks][$k]['pdf'] = 'pdf/narudzba_'.$narudzba['id'].'.pdf';
				}
			}
			return view('app-admin.fakture.index',compact('narudzbeArhiva'));
		}
		return Security::rediectToLogin();
	}
	public function postPretraga(){
		$rezultati = Proizvodi::join('magacin','magacin.proizvod_id','=','proizvod.id')
			->join('magacinid','magacinid.id','=','magacin.magacinid_id')
			->join('pozicija','pozicija.id','=','magacin.pozicija_id')
			->where('sifra','Like','%'.Input::get('sifra').'%')
			->orWhere('proizvod.naziv','Like','%'.Input::get('sifra').'%')
			->orderBy('magacin.id')
		->get(['magacinid.id','magacinid.naziv as nazivmagacina','proizvod.naziv as nazivproizvoda','sifra','kolicina_stanje','stolaza','polica','pozicija'])->toArray();
		return Security::autentifikacija('app-admin.proizvodi.pretraga',compact('rezultati'));
	}
	public function getNarudzbaUredi($id){
		$pristiglo = ZaNarudzbu::join('narudzbenice','narudzbenice.id','=','za_narudzbu.narudzbenice_id')
			->join('proizvod','proizvod.id','=','za_narudzbu.proizvod_id')
			->join('magacin','magacin.id','=','za_narudzbu.magacin_id')
			->join('magacinid','magacinid.id','=','magacin.magacinid_id')
			->where('za_narudzbu.narudzbenice_id','=',$id)
			->get(['za_narudzbu.id','datum_narudzbe','datum_isporuke','kolicina_porucena','kolicina_pristigla','za_narudzbu.magacin_id','proizvod.naziv','proizvod.sifra','magacinid.naziv as magacin','narudzbenice.id as narudzbeniceid'])
			->toArray();
		return Security::autentifikacija('app-admin.fakture.index',compact('pristiglo'));
	}
	public function postNarudzbaUredi($id){
		if(Security::autentifikacijaTest()){
			$zaN = ZaNarudzbu::find($id,['id','kolicina_pristigla']);
			$zaN->kolicina_pristigla += Input::get('kolicina_pristigla');
			$zaN->save();

			$magacin = Skladiste::find(Input::get('magacin_id'),['id','kolicina_stanje','naruceno']);
			$magacin->kolicina_stanje += Input::get('kolicina_pristigla');
			$magacin->naruceno = 0;
			$magacin->save();
			return Redirect::back();
		}
		return Security::rediectToLogin();
	}
	public function postNarudzbaDatumIsporuke($id){
		if(Security::autentifikacijaTest()){
			$narudzba = Narudzbenice::find($id,['id','datum_isporuke']);
			$narudzba->datum_isporuke = Input::get('datum_isporuke');
			$narudzba->save();
			return redirect('/administracija/proizvod/narudzbe');
		}
		return Security::rediectToLogin();
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

		if(!isset($_POST['folder'])) die();
		$folder = 'img/aplikacije/'.$_POST['folder'].'/proizvodi/'.(isset($_POST['id'])?$_POST['id']:(Proizvodi::max('id')+1)).'.'.explode('.', $_FILES['foto']['name'])[1];
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

	public function postEditUcitaj(){
		return json_encode(Proizvodi::find($_POST['id']));
	}
}
