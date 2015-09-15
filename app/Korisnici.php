<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Korisnici extends  Model{
    protected $table = 'korisnici';
    protected $fillable = ['prezime','ime','username','password','email','token','pravaPristupa_id','created_at','updated_at',
        'naziv','adresa','grad','jib','pib','pdv','ziro_racun_1','banka_1','ziro_racun_2','banka_2',
        'registracija','broj_upisa','telefon','opis','aktivan','jmbg','broj_licne_karte','foto'];
}