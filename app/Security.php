<?php
/**
 * Klasa za obezbjeđenje bezbijednosti sistema administracije
 * User: Dušan Perišić
 * Date: 2/9/2015
 * Time: 12:20 AM
 *
 * Početna podešavanja:
 * * $salt - proizvoljan niz znakova za povećanje bezbijednosti jačine password-a
 * * $daminLogURL - adresa do login stranice za pristup administrativnom panelu
 * * korisnici - tabela u kojoj se nalaze korisnici sa poljima [id, username, password, token]
 * * log - tabela [id,korisnici_id,created_at]
 */

namespace App;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Korisnici;
use Illuminate\Support\Facades\DB;
class Security {
    private $id;
    private $username;
    private $password;
    private $salt='ix501^@)5MwfP39ijJDr27g';
    private static $userID=2;
    private static $modID=4;
    private static $adminID=5;
    private static $kreatorID=6;
    public static $adminURL='/administracija';
    public static $userURL='/administracija';
    public static $modURL='/administracija';
    public static $logURL='/administracija/login';
    private $token;
    private $redirectURL;
    private $minLenPass=4;//minimalna duzina sifre i korisnickog imena
    private $prava_pristupa;//prava_pristupa_id

//SETERI[$redirectURL, $username, $password, $token, $_SESSION[token,id,username]]
    public function setRedirectURL($url){
        $this->redirectURL = $url;
    }
    private function setUsername($username){
        $this->username = $username;
    }
    private function setPass($pass){
        $this->password = $pass;
    }
    public function setToken($token){
        $this->token = $token;
    }
    private function setSessions(){
        Session::put('token', $this->token);
        Session::put('id', $this->id);
        Session::put('username', $this->username);
        Session::put('prava_pristupa', $this->prava_pristupa);
    }
//GENERATORI[hashPass, token]
    public static function generateHashPass($pass){
        $sec = new Security();
        $sec->setPass(password_hash($pass.$sec->salt, PASSWORD_BCRYPT, ['cost' => 12]));
        return $sec->password;
    }
    private function generateToken(){
        $this->setToken(hash('haval256,5', $this->salt.uniqid().openssl_random_pseudo_bytes(50), false));
        return $this->token;
    }
    public static function registracija($username,$email,$password,$password_potvrda,$prezime=null,$ime=null,$return_to_url=null){
        $validator=Validator::make([
            'username'=>$username,
            'email'=>$email,
            'password'=>$password,
            'password_confirmation'=>$password_potvrda
        ],[
            'username'=>'required|min:5|unique:korisnici,username',
            'email'=>'required|email|unique:korisnici,email',
            'password'=>'required|min:5|confirmed',
            'password_confirmation'=>'required|min:5'
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
            'password.confirmed'=>'Unesene šifre se ne poklapaju.',
            //pass_conf
            'password_confirmation.required'=>'Obavezan unos password-a.',
            'password_confirmation.min'=>'Minimalna duzina password-a je :min.'
        ]);
        if($validator->fails())if($return_to_url)  return redirect()->back()->withGreska($validator->errors()->toArray())->with(['return_to_url'=>$return_to_url]);
        else return redirect()->back()->withGreska($validator->errors()->toArray())->withInput();

        $korisnik=new Korisnici();
        $korisnik->username=$username;
        $korisnik->email=$email;
        $korisnik->password=Security::generateHashPass($password);
        $korisnik->prezime=$prezime;
        $korisnik->ime=$ime;
        $korisnik->prava_pristupa_id=2;
        $korisnik->aktivan=1;
        $korisnik->save();
        return Redirect::to('/log/login')->withPotvrda('Uspešno ste izvršili registraciju. Možete da se prijavite na platformu.')->with(['return_to_url'=>$return_to_url]);
    }
    public static function comeFromUrl(){
        return isset($_SERVER['HTTP_REFERER'])?parse_url($_SERVER['HTTP_REFERER'])['path']:null;
    }
    public static function forgetFromUrl(){
        Session::forget('return_to_url');
    }
//FUNKCIONALNOSTI

//#TESTERI[autentifikacija, input, login]
    public static function autentifikacijaTest($prava=2,$min=null){
        if (Session::has('id') and Session::has('token') and Session::has('prava_pristupa')) {
            return Korisnici::where('id',Session::get('id'))->where('username',Session::get('username'))->where('token', Session::get('token'))->where('prava_pristupa_id',($min=='min'?'>':'').'=',$prava)->exists();// $korisnik ? true : false;
        } else return false;
    }
    private function inputTest($in){
        return strlen($in)>$this->minLenPass;
    }
    public static function login($username, $password, $return_to_url=null,$mobile=false){
        $sec = new Security();

        if($sec->inputTest($username) and $sec->inputTest($password)){
            $sec->setUsername($username);
            $sec->setPass($password);

            $korisnik = Korisnici::where('username',$sec->username)->where('aktivan',1)->get(['id','username','password','prava_pristupa_id'])->first();
            $test = $korisnik ? password_verify($sec->password.$sec->salt, $korisnik->password) : false;

            if ($test){
                $sec->id = $korisnik->id;
                $sec->username = $korisnik->username;
                $sec->prava_pristupa = $korisnik->prava_pristupa_id;
                $sec->generateToken();
                Korisnici::where('id', $sec->id)->update(['token' => $sec->token]);
                $sec->setSessions();
                Log::insert(['korisnici_id'=>$korisnik->id]);
            }else Korisnici::where('id', $sec->id)->update(['token' => null]);
        }
        if($mobile) return $test ? true : false;
        if($return_to_url&&strcmp(substr($return_to_url,0,5),'/log/')!=0) return redirect($return_to_url);
        return Security::rediectToLogin();
    }
//#REDIRECTORI[autentifikacija, logout, redirect, redirectToLogin]
    public static function autentifikacija($target,$dodaci=null,$prava=null,$min=null){
        if(!$prava) $prava=Session::has('prava_pristupa')?Session::get('prava_pristupa'):2;
        return Security::autentifikacijaTest($prava,$min) ? $dodaci ? view($target, $dodaci) : view($target) : Security::rediectToLogin();
    }
    public static function logout($end=null){
        if(Session::has('id')){
            $korisnik = Korisnici::all(['id','token'])->find(Session::get('id'));
            $korisnik->token = null;
            $korisnik->save();
        }
        Session::flush();
        //$url_to_redirect=Security::comeFromUrl();
        //if($url_to_redirect&&!$end) return redirect($url_to_redirect);
        return redirect('/');
    }
    public function redirect(){
        return redirect($this->redirectURL);
    }
    public static function rediectToLogin(){
        if(Session::has('prava_pristupa'))
            if(Security::autentifikacijaTest(Session::get('prava_pristupa'))){
                switch(Session::get('prava_pristupa')){
                    case Security::$userID:return redirect(Security::$userURL);break;
                    case Security::$modID:return redirect(Security::$modURL);break;
                    case Security::$adminID:
                    case Security::$kreatorID:
                        return redirect(Security::$adminURL);break;
                }
            }
        return redirect(Security::$logURL);
    }
}