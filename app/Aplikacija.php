<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Aplikacija extends Model{
    protected $table = 'aplikacija';
    protected $fillable = ['naziv','created_at','updated_at','korisnici_id','opis','napomena','logo','aktivan'];
}