<?php
/**
 * Klasa za obeybjeđenje bezbijednosti sistema administracije
 * User: Dušan Perišić
 * Date: 2/9/2015
 * Time: 12:20 AM
 *
 * Početna podešavanja:
 * * $salt - proizvoljan niz znakova za povećanje bezbijednosti jačine password-a
 * * $daminLogURL - adresa do login stranice za pristup administrativnom panelu
 * * $korisnici - tabela u kojoj se nalaze korisnici sa poljima [id, username, password, token]
 */


namespace App;

use App\Korisnici;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class Security {
    private $id;
    private $username;
    private $password;
    private $salt = 'ix501^@)5MwfP39ijJDr27g';
    public static $adminLogURL = '/administracija/login';
    private $korisnici = 'korisnici';
    private $token;
    private $redirectURL;
    private $minLenPass = 4; //minimalna duzina sifre i korisnickog imena
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
//FUNKCIONALNOSTI

//#TESTERI[autentifikacija, input, login]
    public static function autentifikacijaTest()
    {
        if (Session::has('id') and Session::has('token')) {
            $korisnik = Korisnici::all(['id', 'token'])->where('id', Session::get('id'))->where('token', Session::get('token'))->first();
            return $korisnik ? true : false;
        } else return false;
    }
    private function inputTest($in){
        return strlen($in)>$this->minLenPass;
    }
    public static function login($username, $password){
        $sec = new Security();

        if($sec->inputTest($username) and $sec->inputTest($password)){
            $sec->setUsername($username);
            $sec->setPass($password);

            $korisnik = Korisnici::all(['id','username', 'password'])->where('username',$sec->username)->first();
            $test = $korisnik ? password_verify($sec->password.$sec->salt, $korisnik->password) : false;

            if ($test){
                $sec->id = $korisnik->id;
                $sec->username = $korisnik->username;
                $sec->generateToken();
                DB::table($sec->korisnici)->where('id', $sec->id)->update(['token' => $sec->token]);
                $sec->setSessions();
            }else DB::table($sec->korisnici)->where('id', $sec->id)->update(['token' => null]);
        }
        return Security::rediectToLogin();
    }
//#REDIRECTORI[autentifikacija, logout, redirect, redirectToLogin]
    public static function autentifikacija($target, $dodaci){
        return Security::autentifikacijaTest() ? $dodaci ? view($target, $dodaci) : view($target) : Security::rediectToLogin();
    }
    public static function logout(){
        if(Session::has('id')){
            $korisnik = Korisnici::all(['id', 'token'])->find(Session::get('id'));
            $korisnik->token = null;
            $korisnik->save();
        }
        Session::flush();
        return Security::rediectToLogin();
    }
    public function redirect(){
        return redirect($this->redirectURL);
    }
    public static function rediectToLogin(){
        return redirect(Security::$adminLogURL);
    }
}