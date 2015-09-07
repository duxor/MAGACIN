<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Proizvodi extends Model{
    protected $table = 'proizvod';
    protected $fillable = ['sifra','naziv','opis','created_at','updated_at','bar_kod','proizvodjac','jedinica_mjere','pakovanje_kolicina','pakovanje_jedinica_mjere','vrsta_proizvoda_id','aplikacija_id','foto'];
}