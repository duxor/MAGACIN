<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Aplikacija extends Model{
    protected $table = 'aplikacija';
    protected $fillable = ['naziv','slug','korisnici_id','opis','napomena','logo','aktivan','created_at','updated_at','adresa','grad','jib','pib','pdv','ziro_racun_1','banka_1','ziro_racun_2','banka_2','registracija','broj_upisa','telefon'];
}