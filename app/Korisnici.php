<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
class Korisnici extends  Model{
    protected $table = 'korisnici';
    protected $fillable = ['prezime','ime','username','password','email','token','pravaPristupa_id','created_at','updated_at',
        'naziv','adresa','grad','jib','pib','pdv','ziro_racun_1','banka_1','ziro_racun_2','banka_2',
        'registracija','broj_upisa','telefon','opis','aktivan','jmbg','broj_licne_karte','foto'];
    public static function ukloni($id,$kid){
        //samo za korisnike koji nisu vlasnici app
        Magaciniranje::join('za_narudzbu as z','z.id','=','magaciniranje.za_narudzbu_id')
            ->join('fakture as f','z.fakture_id','=','f.id')->where('f.aplikacija_id',Session::get('aplikacija_id'))
            ->where('f.korisnici_aplikacije_id',$kid)->delete();
        ZaNarudzbu::join('fakture as f','za_narudzbu.fakture_id','=','f.id')->where('f.aplikacija_id',Session::get('aplikacija_id'))
            ->where('f.korisnici_aplikacije_id',$kid)->delete();
        Fakture::where('aplikacija_id',Session::get('aplikacija_id'))->where('korisnici_aplikacije_id',$kid)->delete();
        KorisniciAplikacije::destroy($kid);
        Korisnici::destroy($id);
        return 1;
    }
}