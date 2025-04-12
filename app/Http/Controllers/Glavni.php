<?php namespace App\Http\Controllers;

use App\OsnovneMetode;
use App\Security;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
class Glavni extends Controller {

	public function getIndex()
	{
		return view('index');
	}
    public function postPosalji(){
        if(!Security::autentifikacijaTest(2,'min')) return 0;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        mail('kontakt@dusanperisic.com','Poruka sa sajta IS MAGACIN: '.$_POST['naslov'].' korisnik:'.Session::get('id'),$_POST['poruka'].' ip='.$ip);
        return 'Vaša poruka je poslata.';
    }
    public function postDolazakNaPosao(){
        //Input::get('x')." ".Input::get('y')." ".Input::get('u')." ".Input::get('p')." "
        return json_encode(['test'=>1,'podaci'=>['username'=>'duXor','naPoslu'=>0,'firma'=>'Kula Foča']]);
    }

}
