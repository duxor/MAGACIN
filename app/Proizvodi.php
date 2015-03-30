<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Proizvodi extends Model{
    protected $table = 'proizvod';
    protected $fillable = ['sifra','naziv','opis','cijena','created_at','updated_at'];
}