<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Aplikacija extends Model{
    protected $table = 'aplikacija';
    protected $fillable = ['naziv','slug','korisnici_id','opis','napomena','logo','aktivan','created_at','updated_at'];
}