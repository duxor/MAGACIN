<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Narudzbenice;
use App\Pozicija;
use App\Security;
use App\Proizvodi;
use Illuminate\Support\Facades\Input;
use App\VrstaProizvoda;
use Illuminate\Support\Facades\Session;
use DB;
class Proizvod extends Controller {

	public function getIndex(){
        if(!Security::autentifikacijaTest(4,'min')) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
		$vrstaProizvoda=VrstaProizvoda::join('aplikacija as a','vrsta_proizvoda.aplikacija_id','=','a.id')
			->where('a.slug',Session::get('aplikacija'))->get(['vrsta_proizvoda.naziv','vrsta_proizvoda.id'])->lists('naziv','id');
		return Security::autentifikacija(Session::get('slug').'.proizvodi.index',compact('proizvodi','vrstaProizvoda'));
	}
	public function postIndex(){
        if(!Security::autentifikacijaTest(4,'min')) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
		return json_encode(Proizvodi::join('aplikacija','proizvod.aplikacija_id','=','aplikacija.id')
			->where('aplikacija.slug',Session::get('aplikacija'))
            ->where(function($query){
                $query->where('proizvod.sifra','Like','%'.Input::get('pretraga').'%')->orWhere('proizvod.naziv','Like','%'.Input::get('pretraga').'%');
            })
            ->where('vrsta_proizvoda_id',(Input::get('vrsta')==0||!Input::has('vrsta')?'Like':'='),(Input::get('vrsta')==0||!Input::has('vrsta')?'%%':Input::get('vrsta')))
            ->get(['proizvod.id','sifra','proizvod.naziv','foto'])->toArray());
	}
	/*public function getNovi(){
		return Security::autentifikacija(Session::get('slug').'.proizvodi.index',['novi'=>true]);
	}*/
    public function postEditUcitaj(){
        if(!Security::autentifikacijaTest(4,'min')) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        return json_encode(Proizvodi::find($_POST['id']));
    }
	public function postEditSave(){
        if(!Security::autentifikacijaTest(4,'min')) return json_encode(['msg'=>'Greska #0001!','check'=>0]);
        $podaci=json_decode(Input::get('podaci'));
        $proizvod = isset($podaci->id) ? Proizvodi::find($podaci->id,['id','sifra','naziv','opis','bar_kod','proizvodjac','jedinica_mjere','pakovanje_kolicina','pakovanje_jedinica_mjere',
            'vrsta_proizvoda_id','aplikacija_id','foto']) : new Proizvodi();
		$proizvod->sifra = $podaci->ssifra;
        $proizvod->naziv = $podaci->naziv;
        $proizvod->opis = $podaci->opis;
        $proizvod->bar_kod = $podaci->bar_kod;
        $proizvod->proizvodjac = $podaci->proizvodjac;
        $proizvod->jedinica_mjere = $podaci->jedinica_mjere;
        $proizvod->pakovanje_kolicina = $podaci->pakovanje_kolicina;
        $proizvod->pakovanje_jedinica_mjere = $podaci->pakovanje_jedinica_mjere;
        $proizvod->vrsta_proizvoda_id = $podaci->vrsta_proizvoda_id;
        if(!isset($podaci->id)) $proizvod->aplikacija_id = Session::get('aplikacija_id');
        $proizvod->foto = $podaci->imgSrc;
		$proizvod->save();
        return json_encode(['msg'=>'Proizvod je sačuvan u evidenciji.','check'=>1]);
	}

    /*
	public function getAzuriraj($id){
		$proizvod = Proizvodi::where('id','=',$id)->get(['id','sifra','naziv','opis','cijena_nabavna','cijena_prodajna'])->first()->toArray();
		return Security::autentifikacija(Session::get('slug').'.proizvodi.index',compact('proizvod'));
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
		return Security::autentifikacija(Session::get('slug').'.proizvodi.index',compact('umagacin','proizvod_podaci'));
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
			return Security::autentifikacija(Session::get('slug').'.fakture.index',compact('zaNarudzbu'));
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
			return Security::autentifikacija(Session::get('slug').'.fakture.index',compact('proizvodi'));
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
			return Security::autentifikacija(Session::get('slug').'.fakture.index',compact('prednarudzbenica','narudzba'));
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
			return view(Session::get('slug').'.fakture.index',compact('narudzbeArhiva'));
		}
		return Security::rediectToLogin();
	}*/
	public function postPretraga(){
        if(isset($_POST['idMagacin']))///ukoliko se traze proizvodi iz odredjenog magacina
            $rezultati=Proizvodi::join('magacin as m','m.proizvod_id','=','proizvod.id')
                ->join('magacin_id as mi','mi.id','=','m.magacin_id_id')
                ->where('mi.id',$_POST['idMagacin'])
                ->where('mi.aplikacija_id',Session::get('aplikacija_id'))
                ->where(function($query){
                    $query->where('sifra','Like','%'.$_POST['pretraga'].'%')->orWhere('proizvod.naziv','Like','%'.$_POST['pretraga'].'%');
                })
                ->orderBy('m.id')
                ->get(['proizvod.id as pid','mi.id','m.id as mid','mi.naziv as nazivmagacina','proizvod.naziv as nazivproizvoda','sifra','kolicina_min','foto',
                    DB::raw('(select sum(pm.kolicina) from magacin_pozicija_u_magacinu as pm where pm.magacin_id=magacin_m.id group by pm.magacin_id) as ukupno_na_stanju')])->toArray();
        else
		$rezultati = !isset($_POST['istekZaliha']) ?
            //PROIZODI BEZ OBZIRA NA KOLICINU NA STANJU
			$_POST['samoMagacin']=='true'?
            //proizvodi koji se nalaze u magacinu
			Proizvodi::join('magacin','magacin.proizvod_id','=','proizvod.id')
				->join('magacin_id','magacin_id.id','=','magacin.magacin_id_id')
				->join('aplikacija as a','a.id','=','magacin_id.aplikacija_id')
                ->where('magacin_id.aplikacija_id',Session::get('aplikacija_id'))
                ->where(function($query){
                    $query->where('sifra','Like','%'.$_POST['pretraga'].'%')->orWhere('proizvod.naziv','Like','%'.$_POST['pretraga'].'%');
                })
				->orderBy('magacin.id')
				->get(['proizvod.id as pid','magacin_id.id','magacin.id as mid','magacin_id.naziv as nazivmagacina','proizvod.naziv as nazivproizvoda','sifra','kolicina_min','foto',
                    DB::raw('(select sum(pm.kolicina) from magacin_pozicija_u_magacinu as pm where pm.magacin_id=magacin_magacin.id group by pm.magacin_id) as ukupno_na_stanju')])->toArray()
			:
            //proizvodi bez obzira da li se nalaze u magacinu ili ne
			Proizvodi::join('aplikacija as a','a.id','=','proizvod.aplikacija_id')
				->where('a.slug',Session::get('aplikacija'))
                ->where(function($query){
                    $query->where('sifra','Like','%'.$_POST['pretraga'].'%')->orWhere('proizvod.naziv','Like','%'.$_POST['pretraga'].'%');
                })
				->get(['proizvod.id as pid','proizvod.naziv as nazivproizvoda','sifra','foto'])->toArray()
			:
        //PROIZVODI SA ISTEKOM ZALIHA
		Proizvodi::join('magacin','magacin.proizvod_id','=','proizvod.id')
			->join('magacin_id','magacin_id.id','=','magacin.magacin_id_id')
			->join('aplikacija as a','a.id','=','magacin_id.aplikacija_id')
			->where('a.slug',Session::get('aplikacija'))
            ->whereRaw('magacin_magacin.kolicina_min>=(select sum(pm.kolicina) as kolicina_stanje from magacin_pozicija_u_magacinu as pm where pm.magacin_id=magacin_magacin.id group by pm.magacin_id)')
			->orderBy('magacin.id')
			->select('proizvod.id as pid','magacin_id.id','magacin.id as mid','magacin_id.naziv as nazivmagacina','proizvod.naziv as nazivproizvoda','sifra','kolicina_min','foto',
                DB::raw('(select sum(pm.kolicina) from magacin_pozicija_u_magacinu as pm where pm.magacin_id=magacin_magacin.id group by pm.magacin_id) as ukupno_na_stanju')
            )->get()->toArray();

        if(isset($_POST['istekZaliha']) || (!isset($_POST['istekZaliha'])&&$_POST['samoMagacin']=='true')){
            foreach($rezultati as $k=>$v){
                $rezultati[$k]['pozicije']=Pozicija::join('pozicija_u_magacinu as pm','pm.pozicija_id','=','pozicija.id')
                    ->groupBy('pm.magacin_id')
                    ->groupBy('pm.pozicija_id')
                    ->where('pm.magacin_id',$v['mid'])
                    ->get([DB::raw('sum(magacin_pm.kolicina) as kolicina_stanje'),'stolaza','polica','pozicija'])->toArray();
            }
        }
		return json_encode($rezultati);
	}
	public function postDodajUKorpu(){
		$niz=Session::get('korpa');
		if($niz)
			foreach($niz as $v){
				if($v['id']==$_POST['id']) return 0;
			}
		Session::push('korpa',Proizvodi::find($_POST['id'],['id','naziv','sifra'])->toArray());
		return 1;
	}
	public function anyUcitajKorpu(){
		return json_encode(Session::get('korpa'));
	}

	public function postUkloniIzKorpe(){
		if($_POST['i']=='all') Session::forget('korpa');
		else{
            //Session::forget('korpa.'.$_POST['i']);
            $korpa=Session::get('korpa');
            unset($korpa[$_POST['i']]);
            Session::forget('korpa');
            Session::set('korpa',array_merge($korpa));
        }
		return 1;
	}
    /*
	public function getNarudzbaUredi($id){
		$pristiglo = ZaNarudzbu::join('narudzbenice','narudzbenice.id','=','za_narudzbu.narudzbenice_id')
			->join('proizvod','proizvod.id','=','za_narudzbu.proizvod_id')
			->join('magacin','magacin.id','=','za_narudzbu.magacin_id')
			->join('magacinid','magacinid.id','=','magacin.magacinid_id')
			->where('za_narudzbu.narudzbenice_id','=',$id)
			->get(['za_narudzbu.id','datum_narudzbe','datum_isporuke','kolicina_porucena','kolicina_pristigla','za_narudzbu.magacin_id','proizvod.naziv','proizvod.sifra','magacinid.naziv as magacin','narudzbenice.id as narudzbeniceid'])
			->toArray();
		return Security::autentifikacija(Session::get('slug').'.fakture.index',compact('pristiglo'));
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
	}*/
	public function postUploadFoto(){
		if(!Security::autentifikacijaTest(4,'min') or !Session::has('aplikacija')){
			echo json_encode(['error'=>'Niste prijavljeni na platformu.']);
			return;
		}
		if (empty($_FILES['foto'])) {
			echo json_encode(['error'=>'Nisu pronađeni fajlovi za upload.']);
			return;
		}
		$folder = 'img/aplikacije/'.Session::get('aplikacija').'/proizvodi/'.(isset($_POST['id'])?$_POST['id']:(Proizvodi::max('id')+1)).'.'.explode('.', $_FILES['foto']['name'])[1];
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
}
