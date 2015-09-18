<?php namespace App\Http\Controllers;

use App\Fakture;
use App\Magacin as MMagacin;
use App\Proizvodi;
use App\Security;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Aplikacija;
use PDF;
use App\Fakture as FFakture;
use App\ZaNarudzbu;
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
		if(Session::has('faktura')) Session::forget('faktura');
		Session::put('faktura.mojiPodaci',$ispis['podaci']);
		return json_encode($ispis);
	}
	public function postUcitajTabeluProizvoda(){
		$proizvodi=[];
		if($_POST['vrstaKorisnika']=='NaN'){
			Session::put('faktura.vrsta_korisnika','predracun');
			Session::put('faktura.vrsta_fakture',3);//fakture.vrsta_fakture_id=3->>Predracun
			$_POST['vrstaKorisnika']=2;
		} else Session::put('faktura.vrsta_fakture',1);//fakture.vrsta_fakture_id=1->>Faktura
		switch($_POST['vrstaKorisnika']){
		//Ukoliko KUPAC kupuje proizvod
			case 2:
				$ukupno=0;
				foreach(Session::get('korpa') as $k=>$proizvod){
					$proizvodi[$k]=Proizvodi::join('magacin as m','m.proizvod_id','=','proizvod.id')
						->where('proizvod.id',$proizvod['id'])
						->get(['proizvod.id','proizvod.sifra','proizvod.naziv','proizvod.jedinica_mjere','m.cijena as maloprodajna_cijena'])
						->first()
						->toArray();
					$proizvodi[$k]['cijena_bez_pdv']=$proizvodi[$k]['maloprodajna_cijena']*0.83;
					$proizvodi[$k]['cijena_pdv']=$proizvodi[$k]['maloprodajna_cijena']*0.17;
					$proizvodi[$k]['cijena_sa_pdv']=$proizvodi[$k]['maloprodajna_cijena'];

					$ukupno+=$proizvodi[$k]['cijena_sa_pdv'];

					$proizvodi[$k]['ukupno_na_stanju']=MMagacin::join('magacin_id as m','m.id','=','magacin.magacin_id_id')
						->where('m.aplikacija_id',Session::get('aplikacija_id'))
						->where('magacin.proizvod_id',$proizvod['id'])
						->groupBy('magacin.proizvod_id')
						->sum('magacin.kolicina_stanje');
				}
			break;
		//Ukoliko se vrsi narudzba od dobavljaca
			case 3:
				Session::put('faktura.vrsta_fakture',2);//fakture.vrsta_fakture_id=2->>Narudzbenica
				foreach(Session::get('korpa') as $k=>$proizvod){
					$proizvodi[$k]=Proizvodi::find($proizvod['id'],['id','sifra','naziv','jedinica_mjere'])->toArray();
				}
			break;
		}
		if(Session::has('faktura.proizvodi')) Session::forget('faktura.proizvodi');
		Session::put('faktura.proizvodi',$proizvodi);
		return json_encode(['proizvodi'=>$proizvodi,'vrsta_fakture'=>Session::get('faktura.vrsta_fakture')]);
	}
	public function postPripremiFakturu(){
		$podaci=json_decode($_POST['faktura']);
		if(Session::get('faktura.vrsta_korisnika')==2 or Session::get('faktura.vrsta_korisnika')=='predracun'){
			foreach($podaci->proizvodi as $k=>$v){
				Session::put('faktura.proizvodi.'.$k.'.kolicina',$v->kolicina);
				Session::put('faktura.proizvodi.'.$k.'.cijena_sa_pdv',$v->cijena_sa_pdv);
				Session::put('faktura.proizvodi.'.$k.'.cijena_bez_pdv',$v->cijena_bez_pdv);
				Session::put('faktura.proizvodi.'.$k.'.cijena_pdv',$v->cijena_pdv);
			}
			Session::put('faktura.ukupno.ukupno_sa_pdv',$podaci->ukupno->ukupno_sa_pdv);
			Session::put('faktura.ukupno.ukupno_bez_pdv',$podaci->ukupno->ukupno_bez_pdv);
			Session::put('faktura.ukupno.ukupno_pdv',$podaci->ukupno->ukupno_pdv);
		}else{
			foreach($podaci->proizvodi as $k=>$v)
				Session::put('faktura.proizvodi.'.$k.'.kolicina',$v->kolicina);
		}
		Session::put('faktura.datum',$podaci->datum);
		if(Session::get('faktura.vrsta_korisnika')!='predracun') {
			Session::put('faktura.na_osnovu', $podaci->na_osnovu);
			Session::put('faktura.placanje', $podaci->placanje);
		}
		Session::put('faktura.napomena', $podaci->napomena);
		return json_encode(['broj_fakture'=>$this->postKreirajBrojFakture()]);
	}
	public function postKreirajBrojFakture(){
		$broj_fakture=Fakture::where('aplikacija_id',Session::get('aplikacija_id'))
			->where('vrsta_fakture_id',Session::get('faktura.vrsta_fakture'))
			->where(DB::raw('YEAR(datum_narudzbe)'),'=',date('Y',strtotime(Session::get('faktura.datum'))))
			->max('broj_fakture');
		$broj_fakture=$broj_fakture+1;//?$broj_fakture+1:1;
		if(Session::has('faktura.broj_fakture')) Session::forget('faktura.broj_fakture');
		Session::put('faktura.broj_fakture',$broj_fakture);
		return $broj_fakture;
	}
	public function getSessions(){dd(Session::all());}
	public function postFaktura(){
		$link=$this->ispisiFakturu();
		$faktura=new FFakture();
			$faktura->datum_narudzbe=Session::get('faktura.datum');
			$faktura->vrsta_fakture_id=Session::get('faktura.vrsta_fakture');
			$faktura->broj_fakture=Session::get('faktura.broj_fakture');
			$faktura->aplikacija_id=Session::get('aplikacija_id');
			$faktura->korisnici_aplikacije_id=Session::get('faktura.korisnik.ka_id');
			$faktura->pdf_link=$link;
		$faktura->save();
		foreach(Session::get('faktura.proizvodi') as $proizvod){
			ZaNarudzbu::insert([
				'kolicina_porucena'=>$proizvod['kolicina'],
				'fakture_id'=>$faktura->id,
				'proizvod_id'=>$proizvod['id']
			]);
		}
		Session::forget('faktura');
		return json_encode(['link'=>$link]);
	}
	private function ispisiFakturu(){
		//osnovne
		Pdf::setMargins(10,35,10,true);
		Pdf::SetAutoPageBreak(true, 20);
		//informacije o dokumentu
		Pdf::SetCreator('IS MAGACIN');
		Pdf::SetAuthor('Dušan Perišić');
		Pdf::SetTitle('Test verzija Foča');
		Pdf::SetSubject('Naslov Subject Foča');
		Pdf::SetKeywords('Ključne riječi');
		//HEADER
		Pdf::setHeaderFont(['freeserif','B',14],['freeserif','B',11]);
		Pdf::setHeaderMargin(10);
		Pdf::setHeaderData('/img/aplikacije/'. Session::get('aplikacija') .'/logo.jpg', 40, Session::get('faktura.mojiPodaci.naziv'), Session::get('faktura.mojiPodaci.adresa')."\n".Session::get('faktura.mojiPodaci.grad'));
		//GLAVNI DIO
		Pdf::SetFont('freeserif','',10);
		Pdf::AddPage();

        $ispis='<style>
                table .prodavackupac{width: 40%}
                table tr .d1{width:32%}
                table tr .d2{width:60%}
                .proizvodi{border-top: 2.5px solid black}
                .proizvodi tr td{border-bottom: 0.1px dashed black;border-right: 1.5px solid black}
                .proizvodi .header{border-bottom: 1.5px solid black}
                .proizvodi .topborder{border-top: 1.5px solid black}
            </style>
            <table class="prodavackupac">
                <tr><td>
                    <table>'.
						(Session::has('faktura.mojiPodaci.jib')?'<tr><td class="d1">JIB:</td><td class="d2">'.Session::get('faktura.mojiPodaci.jib').'</td></tr>':'').
						(Session::has('faktura.mojiPodaci.pdv')?'<tr><td class="d1">PDV:</td><td class="d2">'.Session::get('faktura.mojiPodaci.pdv').'</td></tr>':'').
						(Session::has('faktura.mojiPodaci.ziro_racun_1')?'<tr><td class="d1">Žiro račun:</td><td class="d2">'.Session::get('faktura.mojiPodaci.ziro_racun_1').'</td></tr>':'').
						(Session::has('faktura.mojiPodaci.banka_1')?'<tr><td class="d1">Banka:</td><td class="d2">'.Session::get('faktura.mojiPodaci.banka_1').'</td></tr>':'').
						(Session::has('faktura.mojiPodaci.ziro_racun_2')?'<tr><td class="d1">Žiro račun:</td><td class="d2">'.Session::get('faktura.mojiPodaci.ziro_racun_2').'</td></tr>':'').
						(Session::has('faktura.mojiPodaci.banka_2')?'<tr><td class="d1">Banka:</td><td class="d2">'.Session::get('faktura.mojiPodaci.banka_2').'</td></tr>':'').
						(Session::has('faktura.mojiPodaci.registracija')?'<tr><td class="d1">Registracija:</td><td class="d2">'.Session::get('faktura.mojiPodaci.registracija').'</td></tr>':'').
						(Session::has('faktura.mojiPodaci.broj_upisa')?'<tr><td class="d1">Broj upisa:</td><td class="d2">'.Session::get('faktura.mojiPodaci.broj_upisa').'</td></tr>':'').
                    '</table>
                </td>'.
				(Session::get('faktura.vrsta_fakture')!=3?
                	'<td>
					<b style="font-size: 130%">'.(Session::get('faktura.vrsta_fakture')==1?'Kupac':'Dobavljač').':</b>
                    <br>
                    <table>'.
						(Session::has('faktura.korisnik.prezime')?'<tr><td class="d1">Prezime i Ime</td><td class="d2">'.Session::get('faktura.korisnik.prezime').' '.Session::get('faktura.korisnik.ime').'</td></tr>':'').
						(Session::has('faktura.korisnik.jmbg')?'<tr><td class="d1">JMBG:</td><td class="d2">'.Session::get('faktura.korisnik.jmbg').'</td></tr>':'').
						(Session::has('faktura.korisnik.broj_licne_karte')?'<tr><td class="d1">Broj licne karte:</td><td class="d2">'.Session::get('faktura.korisnik.broj_licne_karte').'</td></tr>':'').
						(Session::has('faktura.korisnik.adresa')?'<tr><td class="d1">Adresa:</td><td class="d2">'.Session::get('faktura.korisnik.adresa').' '.Session::get('faktura.korisnik.grad').'</td></tr>':'').
						(Session::has('faktura.korisnik.telefon')?'<tr><td class="d1">Telefon:</td><td class="d2">'.Session::get('faktura.korisnik.telefon').'</td></tr>':'').
						(Session::has('faktura.korisnik.jib')?'<tr><td class="d1">JIB:</td><td class="d2">'.Session::get('faktura.korisnik.jib').'</td></tr>':'').
						(Session::has('faktura.korisnik.pdv')?'<tr><td class="d1">PDV:</td><td class="d2">'.Session::get('faktura.korisnik.pdv').'</td></tr>':'').
						(Session::has('faktura.korisnik.ziro_racun_1')?'<tr><td class="d1">Žiro račun:</td><td class="d2">'.Session::get('faktura.korisnik.ziro_racun_1').'</td></tr>':'').
						(Session::has('faktura.korisnik.banka_1')?'<tr><td class="d1">Banka:</td><td class="d2">'.Session::get('faktura.korisnik.banka_1').'</td></tr>':'').
						(Session::has('faktura.korisnik.ziro_racun_2')?'<tr><td class="d1">Žiro račun:</td><td class="d2">'.Session::get('faktura.korisnik.ziro_racun_2').'</td></tr>':'').
						(Session::has('faktura.korisnik.banka_2')?'<tr><td class="d1">Banka:</td><td class="d2">'.Session::get('faktura.korisnik.banka_2').'</td></tr>':'').
						(Session::has('faktura.korisnik.registracija')?'<tr><td class="d1">Registracija:</td><td class="d2">'.Session::get('faktura.korisnik.registracija').'</td></tr>':'').
						(Session::has('faktura.korisnik.broj_upisa')?'<tr><td class="d1">Broj upisa:</td><td class="d2">'.Session::get('faktura.korisnik.broj_upisa').'</td></tr>':'').
					'</table>
                </td>':'').
                '</tr>
            </table>
            <br>

            <p><b>Datum: <u>'.date('d.m.Y',strtotime(Session::get('faktura.datum'))).'</u></b></p>
            <h2>'.(Session::get('faktura.vrsta_fakture')==1?'Faktura':Session::get('faktura.vrsta_fakture')==2?'Narudžbenica':'Predračun').' broj <u>'. Session::get('faktura.broj_fakture') .'/'. date('Y',strtotime(Session::get('faktura.datum'))) .'</u></h2>
            <p>'.(Session::get('faktura.na_osnovu')?'Na osnovu: <u> '.Session::get('faktura.na_osnovu').' </u>':'').'
            	'.(Session::get('faktura.placanje')?'<br>Plaćanje: <u> '.Session::get('faktura.placanje').' </u>':'').'</p>
            <table class="proizvodi" align="center">
                <thead>
                    <tr>
                        <td class="header" style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'25px':'60px').';border-left: 2.5px solid black">Redni broj</td>
                        <td class="header" style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'50px':'100px').'">Šifra proizvoda</td>
                        <td class="header" style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'195px':'250px').'">Naziv</td>
                        <td class="header" style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'30px':'60px').'">Kol.</td>
                        <td class="header" style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'30px':'60px;border-right: 2.5px solid black').'">Jed. mjere</td>'.
					(Session::get('faktura.vrsta_fakture')!=2?
							'<td class="header" style="width:50px">Maloprod. cijena</td>
                        	<td class="header" style="width:50px">Iznos bez PDV-a</td>
                        	<td class="header" style="width:50px">PDV</td>
                        	<td class="header" style="width:50px;border-right: 2.5px solid black">Iznos sa PDV-om</td>':'').
                    '</tr>
                </thead>
                <tbody>';
		foreach(Session::get('faktura.proizvodi') as $i=>$proizvod)
        	$ispis.='<tr>
                        <td style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'25px':'60px').';border-left: 2.5px solid black">'.($i+1).'</td>
                        <td style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'50px':'100px').'">'.$proizvod['sifra'].'</td>
                        <td style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'195px':'250px').'">'.$proizvod['naziv'].'</td>
                        <td style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'30px':'60px').'">'.$proizvod['kolicina'].'</td>
                        <td style="width:'.(Session::get('faktura.vrsta_fakture')!=2?'30px':'60px;border-right: 2.5px solid black;').'">'.$proizvod['jedinica_mjere'].'</td>'.
				(Session::get('faktura.vrsta_fakture')!=2?
                        '<td style="width:50px">'.$proizvod['maloprodajna_cijena'].'</td>
							<td style="width:50px">'.$proizvod['cijena_bez_pdv'].'</td>
							<td style="width:50px">'.$proizvod['cijena_pdv'].'</td>
							<td style="width:50px;border-right: 2.5px solid black">'.$proizvod['cijena_sa_pdv'].'</td>':'').
                    '</tr>';

        $ispis.='</tbody>
                <tfoot>
                    <tr>
                        <td colspan="'.(Session::get('faktura.vrsta_fakture')!=2?'4':'5').'" rowspan="10" style="border-top: 2.5px solid black;border-bottom: none;border-right: none;text-align:left">
                        '.(Session::get('faktura.napomena')?'<b>Napomena:</b>
                            <br>'.Session::get('faktura.napomena').'
                        ':'').
                        '</td>'.
						(Session::get('faktura.vrsta_fakture')!=2?
								'<td colspan="3" style="border-left: 2.5px solid black;border-top: 1.5px solid black;border-bottom:none">Ukupan iznos bez PDV-а</td>
								<td colspan="2" style="border-right: 2.5px solid black;border-top: 1.5px solid black;border-bottom:none">'.Session::get('faktura.ukupno.ukupno_bez_pdv').'</td>
							</tr>
							<tr>
								<td colspan="3" style="border-left: 2.5px solid black;border-top: 0.1px solid black;border-bottom:none">PDV 17%</td>
								<td colspan="2" style="border-right: 2.5px solid black;border-top: 0.1px solid black;border-bottom:none">'.Session::get('faktura.ukupno.ukupno_pdv').'</td>
							</tr>
							<tr>
								<td colspan="3" style="border-left: 2.5px solid black;border-top: 0.1px solid black;border-bottom:none">Ukupan iznos sa PDV-om</td>
								<td colspan="2" style="border-right: 2.5px solid black;border-top: 0.1px solid black;border-bottom:none">'.Session::get('faktura.ukupno.ukupno_sa_pdv').'</td>
							</tr>
							<tr>
								<td colspan="3" style="border-left: 2.5px solid black;border-bottom: 2.5px solid black;border-top: 0.1px solid black"><b>Ukupan iznos za uplatu (KM)</b></td>
								<td colspan="2" style="border-right: 2.5px solid black;border-bottom: 2.5px solid black;border-top: 0.1px solid black"><b>'.Session::get('faktura.ukupno.ukupno_sa_pdv').'</b></td>
							</tr>':'</tr>').
                    '<tr><td colspan="5" style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"><b>Potpis i pečat</b></td></tr>
                    <tr><td style="border-bottom:none;border-right:none"></td><td colspan="'.(Session::get('faktura.vrsta_fakture')!=2?'3':'2').'" style="border-bottom:0.1px solid black;border-right:none"></td><td style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"></td></tr>

                    <tr><td colspan="'.(Session::get('faktura.vrsta_fakture')!=2?'9':'5').'" style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="'.(Session::get('faktura.vrsta_fakture')!=2?'9':'5').'" style="border-bottom:none;border-right:none;text-align:left">Reklamacije se uvažavaju u roku od 8 dana po prijemu robe i usluge</td></tr>
                    <tr><td colspan="'.(Session::get('faktura.vrsta_fakture')!=2?'9':'5').'" style="border-bottom:none;border-right:none;text-align:left">Za sve sporove nadležan je Osnovni sud u Foči</td></tr>
                    <tr><td colspan="'.(Session::get('faktura.vrsta_fakture')!=2?'9':'5').'" style="border-bottom:none;border-right:none;text-align:right">Hvala na povjerenju!</td></tr>
                </tfoot>
            </table>';
		Pdf::writeHTMLCell(0, 0, '', '', $ispis, 0, 1, 0, true, '', true);
		$str=Session::get('faktura.vrsta_fakture')==1?'fakture':(Session::get('faktura.vrsta_fakture')==2?'narudzbenice':'predracuni');
		$link='/img/aplikacije/'.Session::get('aplikacija').'/'.$str.'/'.Session::get('faktura.datum').'-'.$str.'-'.Session::get('faktura.broj_fakture').'.pdf';
		Pdf::Output($_SERVER['DOCUMENT_ROOT'].$link,'F');
		Pdf::Close();//exit;
		return $link;
	}

}
